<?php

namespace App\Http\Controllers\Admin;

use View;
Use Redirect;
use App\Models\TaskField;
use App\Helpers\PMTypesHelper;
use App\Providers\Admin\TaskFieldServiceProvider;
use Session;
use App\Http\Requests\StoreTaskField;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TaskFieldController extends BaseController {

    protected $service;

    public function __construct()
    {
        $this->middleware('admin_access');
        $this->service = new TaskFieldServiceProvider();
    }

    public function index()
    {
        $view = View::make('admin.field.index')->with('fields', $this->service->all())
            ->with('typeSelect', PMTypesHelper::fieldTypeSelect());

        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('admin.field.create')->with('taskTypes', $this->service->getTaskTypes())
                ->with('belongsToTaskType', array())->with('typeSelect', PMTypesHelper::fieldTypeSelect());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($account, StoreTaskField $request)
    {
        $this->service->store(Input::all());
        $request->session()->flash('alert-success', 'Field was successfuly created!');
        return Redirect::to($account . '/admin/field');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($account, $id)
    {
        return View::make('admin.field.edit')->with('taskTypes', $this->service->getTaskTypes())
                ->with('field', $this->service->getTaskField($id))
                    ->with('belongsToTaskType', $this->service->belongsToTaskType($id))
                        ->with('typeSelect', PMTypesHelper::fieldTypeSelect());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($account, $id, StoreTaskField $request)
    {
        $this->service->update($id, Input::all());
        $request->session()->flash('alert-success', 'Task Field was successfuly updated!');
        return Redirect::to($account . '/admin/field');
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
        $request->session()->flash('alert-success', 'Task Field was successfuly deleted!');
        return Redirect::to($account . '/admin/field');
    }
}
