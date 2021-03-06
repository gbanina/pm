<?php

namespace App\Http\Controllers\Admin;

use View;
Use Redirect;
use Session;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Providers\Admin\TaskViewServiceProvider;
use Illuminate\Support\Facades\Log;
use App\Providers\PredefinedDataServiceProvider;

class TaskViewController extends BaseController {

    protected $service;

    public function __construct()
    {
        $this->middleware('admin_access');
        $this->service = new TaskViewServiceProvider();
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $view = View::make('admin.task-view.index')->with('taskTypes', $this->service->all());
        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        return View::make('admin.task-view.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($account, Request $request)
    {
        $taskType = $this->service->store( Input::get('view-name') );
        $request->session()->flash('alert-success', 'View was successfuly created!');
        return Redirect::to($account . '/admin/task-view/' . $taskType->id . '/edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($account, $id)
    {
        $fields = $this->service->edit($id);
        return View::make('admin.task-view.edit')
            ->with('taskType', $fields['taskType'])->with('taskFields', $fields['taskFields'])
                ->with('users', $fields['users'])->with('taskTypeFields', $fields['taskTypeFields'])
                    ->with('fields', $fields['fields'])->with('viewId', $id)
                        ->with('usersO', $fields['usersO'])
                            ->with('status', $fields['status'])
                                ->with('priorities', $fields['priorities'])
                                    ->with('types', $fields['types']);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($account, $id, Request $request)
    {
        $this->service->update($id, Input::get('data'), Input::get('view_name'), Input::get('published'));
        return $request->all();
    }

    public function duplicate($account, $sourceId, Request $request)
    {
        $fields = $this->service->duplicate($sourceId);
        $request->session()->flash('alert-success', 'View was successfuly duplicated!');
        return Redirect::to($account . '/admin/task-view/');
    }
    public function unpublish($account, $unpublishId, Request $request)
    {
        $children = $this->service->children($unpublishId);
        return $children;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($account, $id, Request $request)
    {
        $this->service->destroy($id);
        $request->session()->flash('alert-success', 'View was successful deleted!');
        return Redirect::to($account . '/admin/task-view/');
    }
}
