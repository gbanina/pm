<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::guest())
        return Redirect::to('login');
    else
        return Redirect::to(Auth::user()->current_acc . '/board');
});

Route::get('showcase', function () {
    return View::make('showcase.index');
});

Auth::routes();

Route::resource('register-invite', 'Auth\RegisterInviteController');
Route::get('/activate/{activationCode}', 'Auth\RegisterController@confirm');


Route::group(['middleware' => ['auth']], function() {
    Route::resource('help', 'HelpController');
    Route::resource('settings', 'SettingsController');
    Route::resource('profile', 'ProfileController');
    Route::get('/invite/{inviteHash}', 'User\InviteController@accept');
    Route::get('account-rename', 'AccountController@rename');
});

Route::group(['middleware' => ['auth', 'acc_verify:accountId'], 'prefix' => '{accountId}', 'as' => '{accountId}.'], function()
{
    Route::resource('project', 'Project\ProjectController');
    Route::resource('project-plan', 'Project\PlanController');
    Route::get('project-plan/run/{runId}', 'Project\PlanController@run');
    Route::resource('project-rights/{project_id}/', 'Project\ProjectRightController');
    Route::resource('task', 'TaskController');
    Route::put('task-close/{closeId}', 'TaskController@close');
    Route::get('task-close/{closeId2}', 'TaskController@close');
    Route::get('task-reopen/{reopenId}', 'TaskController@reopen');

    Route::resource('users', 'User\UsersController');

    Route::resource('admin/role', 'Admin\RoleController');
    Route::resource('admin/task-type', 'Admin\TaskTypeController');
    Route::resource('admin/project-type', 'Admin\ProjectTypeController');
    Route::resource('admin/task-view', 'Admin\TaskViewController');
    Route::put('admin/task-view-unpublish/{unpublishId}', 'Admin\TaskViewController@unpublish');

    Route::get('admin/task-view/copy/{cpSource}/{cpDestination}', 'Admin\TaskViewController@copy');
    Route::get('admin/task-view/duplicate/{dpcId}', 'Admin\TaskViewController@duplicate');

    Route::resource('admin/status', 'Admin\StatusController');
    Route::resource('admin/priority', 'Admin\PriorityController');
    Route::resource('admin/field', 'Admin\TaskFieldController');
    Route::resource('admin/right', 'Admin\RightController');

    Route::post('admin/status-reorder', 'Admin\StatusController@reorder');
    Route::post('admin/priority-reorder', 'Admin\PriorityController@reorder');
    Route::post('admin/task-type-reorder', 'Admin\TaskTypeController@reorder');

    Route::resource('board', 'BoardController');
    Route::resource('account', 'AccountController');
    Route::post('work-save', 'Work\WorkController@storeAjax');
    Route::post('work-add-time/{taskIDtime}', 'Work\WorkController@addTime');
    Route::post('work-edit-time/{taskIDEditRime}/{workTimeId}', 'Work\WorkController@editTime');
    Route::resource('work', 'Work\WorkController');
    Route::resource('team', 'My\TeamController');

    Route::resource('comment', 'CommentController');

    Route::get('switch/{id}', 'AccountChaingeController@switchTo');
    Route::get('morph/{roleId}', 'MorphController@switchTo');
    Route::get('morph-return', 'MorphController@returnFromMorph');

    Route::get('workingon/start/{startWTaskId}', 'My\WorkingOnController@start');
    Route::get('workingon/end/{endWTaskId}', 'My\WorkingOnController@end');

    Route::post('file-upload/{uploadTaskId}', 'FileController@upload');
});
