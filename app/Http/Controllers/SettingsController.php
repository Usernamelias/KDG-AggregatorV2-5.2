<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use Response;

/**
 * Class for handling the setting page.
 * @author Elias Falconi
 */
class SettingsController extends Controller
{
    /**
     * Display settings page
     * @return view
     */
    public function showSettingsPage(){
        return view('pages.settings');
    }

    /**
     * Function to reset password
     * @param Request
     */
    public function resetPassword(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        if ($validator->passes()){
            $user->password = bcrypt($request->password);

            $user->save();

            return Response::json(['success' => 'Successful password save!']);
        }
        
        
        return Response::json(['errors' => $validator->errors()]);
    }
}

