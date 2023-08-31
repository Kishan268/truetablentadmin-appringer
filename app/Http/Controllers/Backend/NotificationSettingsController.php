<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationSetting;
class NotificationSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.auth.notification-system.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.auth.notification-system.add-edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notificationSetting = NotificationSetting::findOrFail($request->id);
        $notificationSetting->name = $request->name;
        $notificationSetting->subject = $request->subject;
        $notificationSetting->mail_body = $request->mail_body;
        $notificationSetting->sms_body = $request->sms_body;
        $notificationSetting->wa_body = $request->wa_body;
        $notificationSetting->is_mail_enabled = $request->is_mail_enabled ? $request->is_mail_enabled : 0;
        $notificationSetting->is_sms_enabled = $request->is_sms_enabled ? $request->is_sms_enabled : 0;
        $notificationSetting->is_wa_enabled = $request->is_wa_enabled ? $request->is_wa_enabled : 0;
        $notificationSetting->created_by = auth()->user()->id;
        $notificationSetting->updated_by = auth()->user()->id;
        $notificationSetting->save();
        return redirect()->route('admin.auth.notification.index')->withFlashSuccess(__('Notification successfully saved!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $notifications = NotificationSetting::findOrFail($id);
        return view('backend.auth.notification-system.add-edit',compact('notifications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getNotificationList(Request $request){
       $query = NotificationSetting::select('notification_settings.*');

        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            if($search === 'yes'){
                $search = 1;
            }elseif ($search === 'no') {
                $search = 0;
            }else{
                $search = $request->input('q');
            }
            $query->where(function ($q) use ($search) {
                $q->Where('notification_settings.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('notification_settings.subject', 'LIKE', '%' . $search . '%')
                    ->orWhere('notification_settings.is_mail_enabled', 'LIKE', '%' . $search . '%')
                    ->orWhere('notification_settings.is_sms_enabled', 'LIKE', '%' . $search . '%')
                    ->orWhere('notification_settings.is_wa_enabled', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('notification_settings.id', 'asc');
        }

        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('notification_settings.id', 'asc');
        }
        $orderId =  $request->input('type');
        $type = 'allreportedgigslist';
        $notifications = $query->orderBy('notification_settings.id', 'asc')->paginate(10);
        $table = view('backend.auth.notification-system.table',compact('notifications','type','orderId'))->render();
        $pagination = view('backend.auth.notification-system.table-pagination',compact('notifications','type','orderId'))->withUsers($notifications->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
        // NotificationSetting::
    }
}
