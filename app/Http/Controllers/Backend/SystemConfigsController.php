<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemConfig;
 
class SystemConfigsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $systemConfigs = SystemConfig::all();
        return view('backend.auth.system_configs.index', compact('systemConfigs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $systemConfig = SystemConfig::find($id);
        return view('backend.auth.system_configs.edit', compact('systemConfig'));
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
         $request->validate([
            'value' => 'required_if:key,!=,WHITELISTED_EMAILS',
        ]);
        $systemConfig = SystemConfig::where('id',$request->id)->update(['value'=>$request->value]);

        return redirect()->route('admin.system-config.index')->withFlashSuccess(__('Value updated successfully'));
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
    public function systemConfigList(Request $request)
    {
        $query = SystemConfig::select('system_configs.*');
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('key', 'LIKE', '%' . $search . '%') 
                    ->orWhere('value', 'LIKE', '%' . $search . '%');
            });
        }
        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('updated_at', 'desc');
        }
        $type = 'payment-list';
        $system_configs = $query->orderBy('updated_at', 'desc')->paginate(10);
        $table = view('backend.auth.system_configs.system-configs-table',compact('system_configs','type'))->render();
        $pagination = view('backend.auth.system_configs.system-configs-table-pagination',compact('system_configs','type'))->withUsers($system_configs->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }
}
