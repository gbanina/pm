<?php

namespace App\Models;

use App\Models\TaskType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\PMTypesHelper;
use DB;

class Task extends Model {
    use SoftDeletes;

    public function getTypeAttribute()
    {
        //Todo : refactor this!!!
        //$type =  $this->hasOne('App\Models\ProjectTypes', 'fk_tasks_task_types1_idx');
        //$result = TaskType::find($this->task_types_id)->get();
        $type = DB::table('task_types')->find($this->task_types_id);
        return $type->name;
    }

    public function getProjectAttribute()
    {
        //Todo : refactor this!!!
        //$type =  $this->hasOne('App\Models\ProjectTypes', 'fk_tasks_task_types1_idx');
        //$result = TaskType::find($this->task_types_id)->get();
        $project = DB::table('projects')->find($this->projects_id);
        return $project->name;
    }
    public function getEstimatedStartDateAttribute()
    {
        return PMTypesHelper::dateToHuman($this->attributes['estimated_start_date']);
    }
    public function getEstimatedEndDateAttribute()
    {
        return PMTypesHelper::dateToHuman($this->attributes['estimated_end_date']);
    }
    public function getCreationDateAttribute()
    {
        return $this->attributes['created_at'];
    }
}
