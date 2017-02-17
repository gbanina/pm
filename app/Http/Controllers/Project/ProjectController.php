<?php

namespace App\Http\Controllers\Project;

use DB;
use View;
Use Redirect;
use App\User;
use App\Models\Project;
use App\Models\ProjectTypes;
use App\Models\UserProject;
use Session;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ProjectController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $projects = Project::all();
        $view = View::make('project.index')->with('projects',$projects);
        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $users = User::all()->pluck('name', 'id')->prepend('Choose user', '');
        $pt = ProjectTypes::all()->pluck('label', 'id')->prepend('Choose Project Type', '');
        return View::make('project.create')->with('users',$users)->with('projectTypes',$pt);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'project_name' => 'required',
            'project_manager' => 'required',
            'default_responsible' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        $project = new Project;
        $project->accounts_id = 1;
        $project->project_types_id = Input::get('project_type');
        $project->name = Input::get('project_name');
        $project->default_responsible = Input::get('default_responsible');
        $project->save();

        $up = New UserProject;
        $up->users_id = Input::get('project_manager');
        $up->projects_id = $project->id;
        $up->save();

        return Redirect::to('project');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
        $pm = DB::table('user_projects')->where('projects_id', '=', $project->id)->first();
        if($pm == null) $pm = '';
        else $pm = $pm->users_id;

        $users = User::all()->pluck('name', 'id')->prepend('Choose user', '');
        $pt = ProjectTypes::all()->pluck('label', 'id')->prepend('Choose Project Type', '');
        return View::make('project.edit')->with('users',$users)
                                    ->with('projectTypes',$pt)
                                        ->with('project_manager', $pm)
                                            ->with('project', $project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $rules = array(
            'project_name' => 'required',
            'project_manager' => 'required',
            'default_responsible' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        $project = Project::find($id);
        $project->accounts_id = 1;
        $project->project_types_id = Input::get('project_type');
        $project->name = Input::get('project_name');
        $project->default_responsible = Input::get('default_responsible');
        $project->update();

        $pm = UserProject::where('projects_id', '=', $id)->first();
        $pm->users_id = Input::get('project_manager');
        $pm->update();

        return Redirect::to('project');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {

    }

}
