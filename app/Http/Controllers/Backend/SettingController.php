<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\LocationsImport;

class SettingController extends Controller
{
    public function locationImport()
    {
    	return view('backend.location_import');
    }

    public function locationImportFile()
    {
    	$import  = new LocationsImport();
        \Excel::import($import, request()->file('file'));
        $response = $import->getResponse();
        return view('backend.location_import',compact('response'));
    }
}
