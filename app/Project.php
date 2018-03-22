<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function usersProjectsEnabled()
    {
        return $this->belongsToMany('App\User')->wherePivot('enabled', 1)->withTimestamps();
    }

    public function usersProjectsDisabled()
    {
        return $this->belongsToMany('App\User')->wherePivot('enabled', 0)->withTimestamps();
    }

}
