<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use GuzzleHttp\Client;
use App\User;
use Auth;

/**
 * Class for handling auth tokens.
 * 
 * @author Elias Falconi
 */
class AuthTokenController extends Controller
{
    /**
     * Display auth token page.
     * @return redirect|view
     */
    public function showAuthTokenPage(){
        return view('pages.authtoken');
    }

    /**
     * Function to save user's auth token.
     * @param Request
     */
    public function saveAuthToken(Request $request){
        $authtoken = $request->authtoken;

        $client = new Client(['base_uri' => env('BASE_URI')]);
        try{
            $response = $client->request('GET', 'portal/kyledavidgroup/users/?AUTHTOKEN='.$authtoken.'&RESULT=TRUE');
        }catch(Exception $e){
            return response()->json(['error'=> 'Error!']); 
        }
            
        $code = $response->getStatusCode();
    
        if($code === 200){
            $user = User::find(Auth::user()->id);
            $user->authtoken = $authtoken;
            $user->save();

            return response()->json(['success'=> 'Success!']);
        }else{
            throw new Exception("Something bad happened");
            return response()->json(['error'=> 'Error!']); 
        }       
    }
}

