<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model {
    use SoftDeletes;

        public function task(){
        return $this->belongsTo('App\Models\Task');
    }
}
