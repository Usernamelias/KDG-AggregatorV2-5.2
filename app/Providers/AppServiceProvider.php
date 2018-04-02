<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\TimeEntryController;
//use App\TimeEntry;
use Validator;
//use Auth;

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

        // Validator::extend('over24', function($attribute, $value, $parameters, $validator) {
        //     $timeEntryObj = new TimeEntryController();
        //     $request = $timeEntryObj->getRequest();
        //     $total = 0;
        //     $duration;
        //     $totalWithCurrentEntry = 0;
        //
        //     $timeEntries = TimeEntry::where('user_id', Auth::user()->id)->select('duration')->get();
        //
        //     foreach($timeEntries as $timeEntry){
        //       $total = $total + $timeEntry->duration;
        //     }
        //
        //     $duration = round((strtotime($request['end_time']) - strtotime($request['start_time'])) / 60,2);
        //
        //     $totalWithCurrentEntry = $total + $duration;
        //
        //     return $total >= 1440 || $totalWithCurrentEntry >= 1440;
        // });
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
