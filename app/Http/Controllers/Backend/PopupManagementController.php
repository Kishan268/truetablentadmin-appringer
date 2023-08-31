<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Popup;
use App\Models\MasterData;
use App\AppRinger\ImageUtils;
use Auth;
class PopupManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function popupManagement(Request $request)
    {
         if ($request->method() == 'GET') {
            $popup = Popup::where('type','home')->first();
            return view('backend.auth.popup-management.popup-management-save', compact('popup'));
        }
        $authId = Auth::user()->id;
        $img = $request->file('img');
        $data = Popup::where('type','home')->first();
        $dateTime = date('dmYhm');
        if($data){
            $data->updated_by = $authId;
        }else{
            $data = new Popup();
            $data->created_by = $authId;
            $data->updated_by = $authId;

        }
        $data->button1_text = $request->button1_text;
        $data->button1_action = $request->button1_action;
        $data->button2_text = $request->button2_text;
        $data->button2_action = $request->button2_action;
        $data->duration = $request->duration;
        $data->type = 'home';
        
        if (env('IS_S3_UPLOAD') && $img !==null) {
            $file = $request->file('img');
            $filename = $img . "." . $request->img->extension();
            $filename = 'TT' . str_pad($data->id, 5, '0', STR_PAD_LEFT) . "." . $request->img->extension();
            $date = date('m-d-Y h:i:s a', time());
            $key = date('m-Y') . '/' . 'popup/' . $dateTime. '/' . $filename;
            $s3Url = ImageUtils::uploadImageOnS3($file, $key);
            $data->img = $key ? $key :'';
        }
       $data->save();
        return redirect()->route('admin.popup_management')->withFlashSuccess(__('alerts.backend.popup_management_save'));
    }

}
