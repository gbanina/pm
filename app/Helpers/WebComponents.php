<?php

namespace App\Helpers;

use DateTime;
use App\Providers\Admin\TaskStatusServiceProvider;
use App\Providers\Admin\TaskTypeServiceProvider;
use App\Providers\Admin\ProjectTypeServiceProvider;
use App\Providers\Admin\RoleServiceProvider;
use App\Providers\BoardServiceProvider;
use App\Providers\Admin\UserServiceProvider;
use App\Models\UserPreference;
use App\Models\Dashboard;
use Form;
use URL;
use Auth;
Use Redirect;

class WebComponents{

    public static function usersSelect(){
        $service = new UserServiceProvider();
        $users = $service->all()->pluck('user.name', 'user.id')->prepend('Choose...', '');
        return Form::select('user_id', $users, '', array('id' => 'user_id', 'class' => 'form-control'));
    }
    public static function users(){
        $service = new UserServiceProvider();
        return $service->all()->pluck('user.name', 'user.id')->prepend('N/A', '');
    }

    public static function status(){
        $service = new TaskStatusServiceProvider();
        $statuses = $service->all()->pluck('name', 'id')->prepend('Choose...', '');
        return Form::select('status_id', $statuses, '', array('id' => 'status_id', 'class' => 'form-control'));
    }
    public static function statusOverview(){
        $service = new TaskStatusServiceProvider();
        $statuses = $service->all()->pluck('name', 'name')->prepend('Choose...', '');
        return Form::select('status_id', $statuses, '', array('id' => 'status_id-filter', 'class' => 'form-control'));
    }
    public static function taskType(){
        $service = new TaskTypeServiceProvider();
        $types = $service->all()->pluck('name', 'id')->prepend('Choose...', '');
        return Form::select('type_id', $types, '', array('id' => 'type_id', 'class' => 'form-control'));
    }
    public static function taskTypeOverview(){
        $service = new TaskTypeServiceProvider();
        $types = $service->all()->pluck('name', 'name')->prepend('Choose...', '');
        return Form::select('type_id', $types, '', array('id' => 'type_id-filter', 'class' => 'form-control'));
    }
    public static function closedOverview(){
        $array = array('' => 'All', 'Yes' => 'Yes', 'No' => 'No');
        return Form::select('closed-filter', $array, '', array('id' => 'closed-filter', 'class' => 'form-control'));
    }
    public static function projectType(){
        $service = new ProjectTypeServiceProvider();
        $types =  $service->all()->pluck('label', 'label')->prepend('Project Type', '');
        return Form::select('type_id', $types, '', array('id' => 'type_id-filter', 'class' => 'form-control'));
    }
    public static function roles(){
        $service = new RoleServiceProvider();
        $roles =  $service->all()->pluck('name', 'name')->prepend('Role', '');
        return Form::select('role_id', $roles, '', array('id' => 'role_id-filter', 'class' => 'form-control'));
    }
    public static function userTypes(){
        $types =  array('' => 'Type', 'OWNER' => 'OWNER', 'ADMIN' => 'ADMIN', 'MEMBER' => 'MEMBER');
        return Form::select('type_id', $types, '', array('id' => 'type_id-filter', 'class' => 'form-control'));
    }
    public static function myTasks($taskID) {
        $tasks = Auth::user()->myTasks()->pluck('name', 'id')->prepend('Choose task', '');
        return Form::select('task_id', $tasks, $taskID, array('id' => 'tasks-filter', 'class' => 'form-control'));
    }

    public static function backUrl(){
        //return URL::previous();
        if(URL::current() == \Session::get('real-last-url'))
            return \Session::get('real-previous-url');

        return \Session::get('real-last-url');
    }
    public static function redirectBack(){
        return Redirect::to(\Session::get('real-previous-url'));
    }
    public static function boardEvents() {
        $boardService = new BoardServiceProvider();
        $newCount = $boardService->countUnseen();

        if($newCount != 0)
            return '<span class="label label-success pull-right">'.$newCount.' New Events</span>';

        return '';
    }
}
