<?php

namespace App\Providers;

use DB;
use Auth;
use Illuminate\Support\ServiceProvider;
use App\User;
use App\Models\Project;
use App\Models\ProjectTypes;
use App\Models\UserProject;
use App\Models\UserAccounts;
use App\Models\Comment;

class ProjectServiceProvider extends ServiceProvider
{

    public function __construct($user){
    }

    public function all()
    {
        return Project::all()->where('account_id', Auth::user()->current_acc)
                                ->where('permission','!=', 'NONE');
    }
    public function fillCreate()
    {
        $resutl = array();
        $result['users'] = UserAccounts::where('account_id', Auth::user()->current_acc)
                            ->with('user')->get()
                            ->pluck('user.name', 'user_id')->prepend('Choose user', '');
        $result['projectTypes'] = ProjectTypes::where('account_id', Auth::user()->current_acc)->pluck('label', 'id')->prepend('Choose Project Type', '');

        return $result;
    }
    public function store($args)
    {
        $project = new Project;
        $project->account_id = Auth::user()->current_acc;
        $project->project_type_id = $args['project_type'];
        $project->internal_id = Auth::user()->currentacc->nextProjectId();
        $project->name = $args['project_name'];
        $project->default_responsible = $args['default_responsible'];
        $project->created_by = Auth::user()->id;
        $project->save();

        $up = New UserProject;
        $up->user_id = $args['project_manager'];
        $up->project_id = $project->id;
        $up->save();
    }

    public function edit($id)
    {
        $result = array();

        $project = Project::find($id);
        $projectManager = DB::table('user_projects')->where('project_id', '=', $project->id)->first();
        if($projectManager == null) $projectManager = '';
        else $projectManager = $projectManager->user_id;

        $users = UserAccounts::where('account_id', Auth::user()->current_acc)
                            ->with('user')->get()
                            ->pluck('user.name', 'user_id');

        $projectTypes = ProjectTypes::where('account_id', Auth::user()->current_acc);
        $taskTypes = array();
        foreach($projectTypes->get() as $type){
            $taskTypes[$type->id] = $type->posibleTaskTypes()->get()->toArray();
        }

        $typesSelect = $projectTypes->pluck('label', 'id')->prepend('Choose Project Type', '');

        $result['project'] = $project;
        $result['projectManager'] = $projectManager;
        $result['users'] = $users;
        $result['projectTypes'] = $projectTypes;
        $result['taskTypes'] = $taskTypes;
        $result['typesSelect'] = $typesSelect;
        $result['global_css'] = '';
        $result['comments'] = $this->comments($id);

        if($project->getPermissionAttribute() == 'READ')
            $result['global_css'] = 'DISABLED';

        return $result;
    }
    //done
    public function update($id, $args)
    {
        $project = Project::find($id);
        $project->account_id = Auth::user()->current_acc;
        $project->name = $args['project_name'];
        $project->default_responsible = $args['default_responsible'];
        $project->update();

        $pm = UserProject::where('project_id', '=', $id)->first();
        if($pm == null) $pm = new UserProject;
        $pm->project_id = $id;
        $pm->user_id = $args['project_manager'];
        $pm->save();
    }

    public function delete($id)
    {
        $project = Project::find($id);
        $project->delete();
    }

    public function comments($projectId)
    {
        return Comment::where('entity_id', $projectId)->where('entity_type', 'PROJECT')->orderBy('id', 'desc')->get();
    }
}
