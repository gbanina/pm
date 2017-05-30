<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPreference extends Model {
    use SoftDeletes;
    protected   $fillable = ['user_id', 'account_id', 'key', 'value'];
}
