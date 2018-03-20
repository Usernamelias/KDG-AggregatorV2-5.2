<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class TimeEntry extends Model
{
    use SoftDeletes;

    /**
     * Including deleted_at column to time_entries table.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        /**
         * Set the created_by and updated_by field when creating a record.
         */
        static::creating(function($model)
        {
            $model->created_by = (Auth::check() ? Auth::user()->id : 1);
            $model->updated_by = (Auth::check() ? Auth::user()->id : 1);
        });

        /**
         * Set the updated_by field when saving a record.
         */
        static::updating(function($model)
        {
            $model->updated_by = (Auth::check() ? Auth::user()->id : 1);
        });
        
        /**
         * Set the deleted_by field when deleting a record.
         */
        static::deleting(function ($model)
        {
            $model->deleted_by = (Auth::check() ? Auth::user()->id : 1);
            $model->save();
        });
    }
}
