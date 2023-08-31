<?php

namespace App\Http\Controllers\Backend\Auth\Referral;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Referral;
use App\Http\Requests\Backend\Referral\StoreReferralRequest;
use App\AppRinger\ImageUtils;

class ReferralController extends Controller
{

	public function index()
	{
	 if(!auth()->user()->can('view_referral')){
        abort(403);
      }
        return view('backend.auth.referrals.index');
	}

	public function referralList(Request $request){
         if(!auth()->user()->can('view_referral')){
            abort(403);
          }
        $query = Referral::withTrashed();

        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('program_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('program_description', 'LIKE', '%' . $search . '%');
            });
        }
        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('updated_at', 'desc');
        }
        
        $referrals = $query->orderBy('updated_at', 'desc')->paginate(10);
        $table = view('backend.auth.referrals.referral-table',compact('referrals'))->render();
        $pagination = view('backend.auth.referrals.referral-table-pagination',compact('referrals'))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }


    public function create()
    {
        if(!auth()->user()->can('add_referral')){
            abort(403);
          }
    	$referral = new Referral();
    	return view('backend.auth.referrals.add-edit', compact('referral'));
    }

    public function store(StoreReferralRequest $request)
    {
        if(!auth()->user()->can('add_referral')){
            abort(403);
        }
    	$img_url = '';  
    	$img = $request->file('img'); 
        if (env('IS_S3_UPLOAD') && $img !==null) {
            
            $file = $request->file('img');
            $filename = 'TT Referral' . "-" . $request->program_name . "." . $request->img->extension();
            $key = date('m-Y') . '/' . 'referral/' . $request->program_name . '/' . $filename;
            $s3Url = ImageUtils::uploadImageOnS3($file, $key);
            $img_url = $key;
        }
        $request->request->add(['program_image' => $img_url]);
        Referral::add($request);
        return redirect()->route('admin.auth.referrals.index')->withFlashSuccess(__('alerts.backend.referral.created'));
    }

    public function edit($id)
    {
        if(!auth()->user()->can('update_referral')){
            abort(403);
        }
    	$referral = Referral::find($id);

    	return view('backend.auth.referrals.add-edit', compact('referral','id'));
    }

    public function update($id, StoreReferralRequest $request)
    {
        if(!auth()->user()->can('update_referral')){
            abort(403);
        }
    	$img = $request->file('img'); 
        if (env('IS_S3_UPLOAD') && $img !==null) {
    		$img_url = '';  
            
            $file = $request->file('img');
            $filename = 'TT Referral' . "-" . $request->program_name . "." . $request->img->extension();
            $key = date('m-Y') . '/' . 'referral/' . $request->program_name . '/' . $filename;
            $s3Url = ImageUtils::uploadImageOnS3($file, $key);
            $img_url = $key;
        	$request->request->add(['program_image' => $img_url]);
        }
        Referral::updateData($id,$request);
        
        return redirect()->route('admin.auth.referrals.index')->withFlashSuccess(__('alerts.backend.referral.updated'));
    }
}
