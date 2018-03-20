<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Jobs\ZohoAPIJob;

/**
 * Class for handling the app's api calls for updating tables.
 * @author Elias Falconi
 */
class ZohoAPIController extends Controller
{
    /**
     * This function will call the dispatch method of ZohoAPIJob
     */
    public function updateTables(){

        $this->dispatch(new ZohoAPIJob());
    }
}