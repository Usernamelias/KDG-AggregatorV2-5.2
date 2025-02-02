<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Extensions\AppEngineStorageSessionHandler;
use Auth;

/**
 * This class is for holding utility functions.
 *
 * @author Elias Falconi
 */
class UtilityController extends Controller{
    /**
     * Takes total minutes and converts it to hours:minutes form. For example,
     * 123 minutes would be 2:03.
     * @param int
     * @return string
     */
    public static function makeDurationDisplayReady($totalMinutes){
        $minutesToHours = floor($totalMinutes/60);
        $remainder = $totalMinutes % 60;

        if ($remainder < 10) {
            return $minutesToHours.":"."0".$remainder;
        } else {
            return $minutesToHours.":".$remainder;
        }
    }

    /**
     * Takes the display version of duration and converts it to minutes
     * @param string
     * @return int
     */
    public static function durationToTotalMinutes($duration){
        $hoursAndMinutes = explode(":", $duration);

        if ($hoursAndMinutes[0] == "") {
            $hoursAndMinutes[0] = 0;
        }
        if (array_key_exists(1, $hoursAndMinutes) === false) {
            $hoursAndMinutes[1] = $hoursAndMinutes[0];
            $hoursAndMinutes[0] = 0;
        }
        return $hoursAndMinutes[0]*60 + $hoursAndMinutes[1];
    }

    /**
     * For starting sessions by passing in a session handler.
     * @return void
     */
    public static function startSession(){
        $session = new AppEngineStorageSessionHandler();
        session_set_save_handler($session, true);

        session_start();
    }

    /**
     * Function for handling non-existent pages for which there's a route.
     * @return redirect Returns previous page.
     */
    public function redirectBack(){
        return redirect()->back();
    }

    public static function checkSession(){
      $success = '0';
      if(Auth::guest()){
        $success = '1';
      }

      return json_encode(['success' => $success]);
    }
}
