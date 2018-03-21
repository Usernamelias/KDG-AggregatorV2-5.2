<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\TimeEntryController;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('one_or_the_other', function($attribute, $value, $parameters, $validator) {
            $timeEntryObj = new TimeEntryController();
            $request = $timeEntryObj->getRequest();

            return $attribute !== null && $request['start_time'] !== null && $request['end_time'] !== null;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
