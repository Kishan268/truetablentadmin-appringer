<?php

namespace App\Http\Controllers\Backend\Auth\HomePageSettings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\FeaturedJob;
use App\Models\HomepageLogo;
use App\Models\Companies;
use App\Models\Job;
use App\Models\SystemSettings;
use App\AppRinger\ImageUtils;
use Auth;
use App\Helpers\SiteHelper;
class HomePageSettingsController extends Controller{
    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {
      if(!auth()->user()->can('view_featured_job')){
        abort(403);
      }
        $jobs = FeaturedJob::getFeaturedJobs();
        $jobIds = [];
        foreach ($jobs as $key => $job) {
            $jobIds[] = $job->job_id;
        }
        $jobsDatas = Job::where('status','published')->whereNotIn('id',$jobIds)->with('company_details','user_details')->get();
        $companies = Companies::with('jobs')->get();
        return view('backend.auth.homepage_settings.featured_jobs.index', compact('jobs','jobsDatas','companies'));
    }

    public function getFeaturedJobs()
    {

     if(!auth()->user()->can('view_featured_job')){
        abort(403);
      }
        return view('backend.auth.homepage_settings.featured_jobs.index');
    }

    public function createJob()
    {
        if(!auth()->user()->can('create_featured_job')){
            abort(403);
          }
        $companies = Companies::all();
        return view('backend.auth.homepage_settings.featured_jobs.create', compact('companies'));
    }

    public function companyJobs(Request $request)
    {
        if($request->has('company_id')){
            $jobs = Job::where('company_id',$request->company_id)->get();

        }else{
            $jobs = Job::all();
        }

        $html = view('backend.auth.homepage_settings.featured_jobs.company-jobs-dropdown', compact('jobs'))->render();
        return response()->json([
            'status' => true,
            'html' => $html,
        ]);
    }

    public function storeJob(Request $request)
    {
        if(!auth()->user()->can('add_featured_job')){
            abort(403);
        }
        if($request->has('order') && $request->order != ''){
            $order = $request->order;
        }else{
            $get_order = FeaturedJob::orderBy('order','DESC')->skip(0)->take(1)->first();
            $order = $get_order && $get_order->order != null ? $get_order->order + 1 : 1;
        }
        $addJob = FeaturedJob::create(['job_id' => $request->job_id,'order' => $order]);
       return "success";
    }
    public function orderChange(Request $request)
    {
        if(!auth()->user()->can('update_featured_job')){
            abort(403);
        }
        $ids = count($request['ids']);
        $data =[];
        for ($i=0; $i <=$ids ; $i++) { 
            $id = $i+1;
            if (@$request['ids'][$i] !=null) {
                if ($id > 18) {
                \Log::error('Manage featured jobs order wrong!');
                return "error";
                }
                try {
                    FeaturedJob::where('job_id',@$request['ids'][$i])->update(['order'=>$id]);
                } catch (Exception $e) {
                \Log::error($e);
                return $e;
               }
            }
        }
        return "success";


    }
    public function logoOrderChange(Request $request)
    {
        if(!auth()->user()->can('update_featured_logo')){
            abort(403);
        }
        $ids = count($request['ids']);
        $data =[];
        for ($i=0; $i <=$ids ; $i++) { 
            $id = $i+1;
            if (@$request['ids'][$i] !=null) {
                HomepageLogo::where('id',@$request['ids'][$i])->update(['order'=>$id]);
            }
        }
       
        return "success";
    }

    public function sequenceJobs()
    {
         if(!auth()->user()->can('update_featured_job')){
            abort(403);
        }
        $jobs = FeaturedJob::getOrderableFeaturedJobs();
        return view('backend.auth.homepage_settings.featured_jobs.sequence', compact('jobs'));
    }

    public function updateJobsSequence(Request $request)
    {
        if(!auth()->user()->can('update_featured_job')){
            abort(403);
        }

        $menu_order = $request->menu;
        for ($i = 0; $i < count($menu_order); $i++) { 
            $remove_order = FeaturedJob::where('id',$menu_order[$i])->update(['order' => $i+1]);
        }
        return redirect()->route('admin.auth.featured-jobs.index')->withFlashSuccess(__('alerts.backend.featured_jobs.ordered'));
    }

    public function deleteJob(Request $request)
    {
        if(!auth()->user()->can('delete_featured_job')){
            abort(403);
        }
        try {
            $job_id = $request->get('id');
            $remove_order = FeaturedJob::where('job_id',$job_id)->update(['order' => null]);
            $job = FeaturedJob::where('job_id',$job_id)->delete();
            return "success";
            
            
        } catch (\Exception $e) {
            echo $e->getMessage();
            return "error";
            
        }
    }

    public function logos()
    {
         if(!auth()->user()->can('view_featured_logo')){
            abort(403);
        }
        $logos = HomepageLogo::orderBy('order','ASC')->whereNotNull('order')->with('company')->get();
        $companies = Companies::all();
        return view('backend.auth.homepage_settings.logos.index', compact('logos','companies'));
    }

    public function createLogo()
    {
        return view('backend.auth.homepage_settings.logos.create');
    }

    public function storeLogo(Request $request)
    {
         if(!auth()->user()->can('add_featured_logo')){
            abort(403);
        }
        $request->validate([
           'website_url'=> "required|url",
           'logo'=> "required",
        ]);
        $file = $request->file('logo');
        $filename = \Carbon\Carbon::now()->timestamp.$request->logo->getClientOriginalName();
        $key = '';
        if (env('IS_S3_UPLOAD')) {
            $key = date('m-Y').'/'.'homepage_logos/'.$filename;
            $img_url = ImageUtils::uploadImageOnS3($file,$key);
        }else{
            $img_upload = ImageUtils::uploadImage($file,'homepage_logos/',$filename);
            $key = url($img_upload);
        }
        if($key != '')
        {
            if($request->has('order') && $request->order != ''){
                $order = $request->order;
            }else{
                
                $get_order = HomepageLogo::orderBy('order','DESC')->skip(0)->take(1)->first();
                $order = $get_order && $get_order->order != null ? $get_order->order + 1 : 1;

            }

            $addLogo = HomepageLogo::create(
                [
                    'logo_url' => $key, 
                    'website_link' => $request->website_url, 
                    'order' => $order
                ]);
            return redirect()->route('admin.auth.homepage-logos.index')->withFlashSuccess(__('alerts.backend.homepage_logos.created'));
        }
    }

    public function editLogo($id)
    {
        if(!auth()->user()->can('update_featured_logo')){
            abort(403);
        }
        $logo = HomepageLogo::find($id);
        return view('backend.auth.homepage_settings.logos.edit',compact('logo'));
    }

    public function updateLogo($id, Request $request)
    {
        if(!auth()->user()->can('update_featured_logo')){
            abort(403);
        }
        $logo = HomepageLogo::find($id);
        $key  = $logo->logo_url;
        if ($request->hasFile('logo')) {
           
            $file = $request->file('logo');
            $filename = \Carbon\Carbon::now()->timestamp.$request->logo->getClientOriginalName();
            $key = '';
            if (env('IS_S3_UPLOAD')) {
                $key = date('m-Y').'/'.'homepage_logos/'.$filename;
                $img_url = ImageUtils::uploadImageOnS3($file,$key);
            }else{
                $img_upload = ImageUtils::uploadImage($file,'homepage_logos/',$filename);
                $key = url($img_upload);
            }
        }


        if($key != '')
        {

            $addLogo = HomepageLogo::find($id)->update(
                [
                    'logo_url' => $key, 
                    'website_link' => $request->website_url
                ]);
            return redirect()->route('admin.auth.homepage-logos.index')->withFlashSuccess(__('alerts.backend.homepage_logos.updated'));
        }
    }

    public function deleteLogo(Request $request)
    {
        if(!auth()->user()->can('delete_featured_logo')){
            abort(403);
        }
        try {
            $logo_id = $request->get('id');
            $remove_order = HomepageLogo::where('id',$logo_id)->update(['order' => null]);
            $logo = HomepageLogo::find($logo_id)->delete();
            return "success";
            
            
        } catch (\Exception $e) {
            echo $e->getMessage();
            return "error";
            
        }
    }

    public function sequenceLogos()
    {
         if(!auth()->user()->can('update_featured_logo')){
            abort(403);
        }
        $logos = HomepageLogo::getOrderableLogos();
        return view('backend.auth.homepage_settings.logos.sequence', compact('logos'));
    }

    public function updateLogosSequence(Request $request)
    {
        if(!auth()->user()->can('update_featured_logo')){
            abort(403);
        }
        $menu_order = $request->menu;
        for ($i = 0; $i < count($menu_order); $i++) { 
            $remove_order = HomepageLogo::where('id',$menu_order[$i])->update(['order' => $i+1]);
        }
        return redirect()->route('admin.auth.homepage-logos.index')->withFlashSuccess(__('alerts.backend.homepage_logos.ordered'));
    }
    public function allFeaturedJobsList(Request $request)
    {
         if(!auth()->user()->can('create_featured_job')){
            abort(403);
        }
        $featuredJobs = FeaturedJob::getFeaturedJobs();
        $featuredGigIds = [];
        foreach ($featuredJobs as $key => $featuredGig) {
            $featuredGigIds[] = $featuredGig->id;
        }
        $query = Job::whereNotIn('company_jobs.id',$featuredGigIds)->where('company_jobs.status','published')->select('company_jobs.*','companies.name AS company_name','companies.id AS company_id','companies.logo','users.first_name','users.id AS user_id','users.last_name')
        ->leftJoin('companies', 'companies.id', '=', 'company_jobs.company_id')
        ->leftJoin('users', 'users.id', '=', 'company_jobs.user_id');
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('company_jobs.id', 'LIKE', '%' . $search . '%')
                    ->orWhere('companies.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_jobs.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_jobs.created_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.first_name', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('company_jobs.updated_at', 'desc');
        }
        $orderId =  $request->input('type');
        $type = 'allgigslist';
        $jobsDatas = $query->paginate(10);
        $table = view('backend.auth.homepage_settings.featured_jobs.all-featured-jobs.featured-jobs-table',compact('jobsDatas','type','orderId'))->render();
        $pagination = view('backend.auth.homepage_settings.featured_jobs.all-featured-jobs.featured-jobs-pagination',compact('jobsDatas','type','orderId'))->withUsers($jobsDatas->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);

    } 
    public function logosCompanyList(Request $request)
    {
        if(!auth()->user()->can('add_featured_logo')){
            abort(403);
        }
        $companiesIds = [];
        $companiesIds = HomepageLogo::where('company_id','!=',null)->pluck('company_id')->toArray();
        // foreach ($companies as $key => $company) {
        //     $companiesIds[] = $company->company_id;
        // }

        $query = Companies::whereNotIn('id',$companiesIds)->select('companies.*');
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('companies.name', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('companies.updated_at', 'desc');
        }
        $orderId =  $request->input('type');
        $type = 'logoCompanylist';
        $companies = $query->paginate(10);

        $table = view('backend.auth.homepage_settings.logos.logo-companies.logo-companies-table',compact('companies','type','orderId'))->render();
        $pagination = view('backend.auth.homepage_settings.logos.logo-companies.logo-companies-pagination',compact('companies','type','orderId'))->withUsers($companies->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);

    }
    public function storeCompanyLogo(Request $request){
         if(!auth()->user()->can('add_featured_logo')){
            abort(403);
        }
        $data = [
            'company_id' => $request->company_id, 
            'order' => $request->order, 
        ];
        $duplicateOrder = HomepageLogo::where('order',$request->order)->get();
        if(count($duplicateOrder) > 0){
            // HomepageLogo::where('order',$request->order)->delete();
            HomepageLogo::where('order',$request->order)->update(['company_id'=>$request->company_id]);
            return 'replace';
        }else{
         $addLogo = HomepageLogo::create($data);
         return 'create';
        }
    }
}
