<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use App\Models\Companies;
use App\Models\Job;
use App\Models\ProfileViewTransactions;
use App\Models\ReportedJobs;
use App\Models\PaymentTransactions;
use App\Models\MasterData;
use App\Http\Requests\Backend\Company\StoreCompanyRequest;
use App\Http\Requests\Backend\Company\StoreCompanyJobRequest;
use App\Models\Auth\User;
use App\Models\CompanyJobDetail;
use App\Models\CompanyGig;
use App\Models\Chat;
use App\Models\FeaturedJob;
use App\Models\FeaturedGig;
use App\Models\HomepageLogo;
use App\AppRinger\ChatGPT;
use App\AppRinger\ImageUtils;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\UserRepository;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportCompanies;
use App\Exports\ExportJobs;
use Response;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;
use App\Constants\StringConstants;

class CompanyController extends Controller{
    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request){
        if(!auth()->user()->can('view_company')){
            abort(403);
        }

        $query = Companies::withTrashed()->select('companies.*','location_table.name AS location_name','industry_domain_table.name AS industry_domain_name')
                ->leftJoin('master_data AS location_table', 'location_table.id', '=', 'companies.location_id')
                ->leftJoin('master_data AS industry_domain_table', 'industry_domain_table.id', '=', 'companies.industry_domain_id');

        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('companies.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('companies.website', 'LIKE', '%' . $search . '%');
            });
        }

        $companies = $query->orderBy('companies.updated_at', 'desc')->paginate(10);
        $status = '';
        if ($request->has('status') && $request->input('status') != '') {
            $status = $request->input('status');
        }
        return view('backend.auth.company.index', compact('companies','status'));
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jobs(ManageUserRequest $request){
        if(!auth()->user()->can('view_job')){
            abort(403);
        }
        $query = Job::select('company_jobs.*','companies.name','companies.website')->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id');

        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('company_jobs.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_jobs.description', 'LIKE', '%' . $search . '%');
            });
        }

        $jobs = $query->orderBy('updated_at', 'desc')->paginate(25);

        return view('backend.auth.company.jobs', compact('jobs'));
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reportedJobs(ManageUserRequest $request){
        if(!auth()->user()->can('view_reported_job')){
            abort(403);
        }

        $query = ReportedJobs::join('company_jobs', 'company_jobs.id', '=', 'reported_jobs.job_id');

        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('company_jobs.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_jobs.description', 'LIKE', '%' . $search . '%');
            });
        }



        $jobs = $query->orderBy('reported_jobs.updated_at', 'desc')->paginate(25);

        return view('backend.auth.company.reportedJobs', compact('jobs'));
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jobBlock(ManageUserRequest $request){
        try{
            $job = Job::withTrashed()->whereId($request->get('job_id'))->first();
            $opt = null;
            if($job->trashed()){
                $opt = 1;
                $job->restore();
            }else{
                $opt = 0;
                $job->delete();
            }
            if($opt === null){
                return "error";
            }
            return ['opt' => $opt];
        }catch(\Exception $e){
            return "error";
        }
    }

    public function deactivateCompany(ManageUserRequest $request){
        try{
            $company = Companies::withTrashed()->whereId($request->get('company_id'))->first();
            $opt = null;
            if($company->trashed()){
                $opt = 1;
                $company->restore();
            }else{
                $opt = 0;
                $company->delete();
            }
            if($opt === null){
                return "error";
            }
            return ['opt' => $opt];
        }catch(\Exception $e){
            return "error";
        }
    }

    public function deleteCompanyData(ManageUserRequest $request){
        try{

            $data = [];
            $data['jobs_count'] = Job::where('company_id',$request->company_id)->count();
            $data['gigs_count'] = CompanyGig::where('company_id',$request->company_id)->count();
            $data['recruiters_count'] = User::role('company user')->where('company_id',$request->company_id)->count();
            
            return ['data' => $data];
        }catch(\Exception $e){
            return "error";
        }
    }

    public function deleteCompany(ManageUserRequest $request)
    {
        if(!auth()->user()->can('delete_company')){
            abort(403);
        }
        try{

            $company_id = $request->company_id;
            $company_jobs = Job::where('company_id',$company_id)->pluck('id')->toArray();
            $company_gigs = CompanyGig::where('company_id',$company_id)->pluck('id')->toArray();
            $company_users = User::where('company_id',$company_id)->pluck('id')->toArray();

            $featured_jobs_delete = FeaturedJob::whereIn('job_id',$company_jobs)->delete();
            $featured_gigs_delete = FeaturedGig::whereIn('gig_id',$company_gigs)->delete();
            $featured_logo_delete = HomepageLogo::where('company_id',$company_id)->delete();

            $users_delete = User::where('company_id',$company_id)->delete();
            $jobs_delete = Job::where('company_id',$company_id)->delete();
            $gigs_delete = CompanyGig::where('company_id',$company_id)->delete();
            $update_deleted = Companies::find($company_id)->update([
                'is_deleted' => '1'
            ]);
            $company_delete = Companies::find($company_id)->delete();
            $chat_delete = Chat::whereIn('recruiter_id',$company_users)->delete();

            return ['opt' => true];


        }catch(\Exception $e){
            return "error";
        }
    }


    public function restoreCompany(ManageUserRequest $request)
    {
        try{

            $company_id = $request->company_id;
            $company_jobs = Job::withTrashed()->where('company_id',$company_id)->pluck('id')->toArray();
            $company_gigs = CompanyGig::withTrashed()->where('company_id',$company_id)->pluck('id')->toArray();
            $company_users = User::where('company_id',$company_id)->pluck('id')->toArray();

            $featured_jobs_delete = FeaturedJob::withTrashed()->where('order','!=',null)->whereIn('job_id',$company_jobs)->restore();
            $featured_gigs_delete = FeaturedGig::withTrashed()->where('order','!=',null)->whereIn('gig_id',$company_gigs)->restore();
            $featured_logo_delete = HomepageLogo::withTrashed()->where('order','!=',null)->where('company_id',$company_id)->restore();

            $users_delete = User::where('company_id',$company_id)->restore();
            $jobs_delete = Job::where('company_id',$company_id)->restore();
            $gigs_delete = CompanyGig::where('company_id',$company_id)->restore();
            $update_deleted = Companies::withTrashed()->find($company_id)->update([
                'is_deleted' => '0'
            ]);
            $company_delete = Companies::withTrashed()->find($company_id)->restore();
            $chat_delete = Chat::whereIn('recruiter_id',$company_users)->restore();

            return ['opt' => true];


        }catch(\Exception $e){
            return "error";
        }
    }

    public function deactivateJob(ManageUserRequest $request)
    {
        try {
            $job = Job::find($request->get('job_id'));
            $opt = null;
            if ($job->status == __('status.job.published')) {
                $opt = 0;
                $job->status = __('status.job.blocked');
            }else{
                $opt = 1;
                $job->status = __('status.job.published');
            }
            if($opt === null){
                return "error";
            }else{
                $job->save();
                return ['opt' => $opt];
            }
            
            
        } catch (\Exception $e) {
            echo $e->getMessage();
            return "error";
            
        }
        
    }
    public function addCash(ManageUserRequest $request){
        if($request->has('amount') && $request->get('amount') > 0 && $request->has('cid')){
            try{
                DB::beginTransaction();
                $remaining_views_db = ProfileViewTransactions::where(['company_id' => $request->get('cid')])->orderBy('id', 'desc')->first('remaining');
                $remaining_views = 0;
                if($remaining_views_db != null){ $remaining_views = $remaining_views_db['remaining']; }
                $remaining_views += $request->get('amount');
					
                PaymentTransactions::create([
                    'company_id' => $request->get('cid'),
                    'user_id' => Auth()->user()->id,
                    'amount' => $request->get('amount'),
                    'transaction_id' => 'TT',
                    'transaction_details' => ''
                ]);
                ProfileViewTransactions::create([
                    'company_id' => $request->get('cid'),
                    'user_id' => auth()->user()->id,
                    'type' => 'credit',
                    'amount' => $request->get('amount'),
                    'remaining' => $remaining_views,
                    'by' => auth()->user()->id
                ]);
                DB::commit();

                return ['msg' => "success", 'amount' => $remaining_views];
            }catch(\Exception $e){
                DB::rollBack();
                return "error";
            }
        }
        return "error";
    }

    public function paymentsList(Request $request){
        if(!auth()->user()->can('view_payment')){
            abort(403);
        }
        $query = PaymentTransactions::select('*');
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'LIKE', '%' . $search . '%');
            });
        }
        $payments = $query->paginate(25);
        return view('backend.auth.company.paymentsList', compact('payments'));
    }


    public function create()
    {
        if(!auth()->user()->can('add_company')){
            abort(403);
        }
        $data_arr = ['industry_domains','company_sizes','location'];
        $data = MasterData::getMasterData($data_arr,'name');

        return view('backend.auth.company.create', compact('data'));
    }

    public function store(StoreCompanyRequest $request)
    {
        if(!auth()->user()->can('add_company')){
            abort(403);
        }
        $input = $request->all();
        $site = $input['website'];
        if (strpos($site, 'http') === false && strpos($site, 'https') === false) {
            $input['website'] = 'http://' . $request->website;
        }

        $url = str_replace('www.', '', parse_url($input['website'])['host']);
        $existing = Companies::where('website', 'like', '%' . $url . '%')->select('id', 'name')->first();

        if ($existing) {
            return redirect()->back()->withErrors(['website' => 'Company already exists'])->withInput($request->all());
        }
        $logo = $request->file('logo');
        $cover_pic_image = $request->file('cover_pic');
        $input['logo']= '';
        $logo_img_url= '';
        $data = [
            'company_name'=>$input['company_name'],
            'website'=>$input['website'],
            'location'=>$input['location'],
            'company_size'=>$input['company_size'],
            'industry_domain'=>$input['industry_domain'],
            // 'logo'=>$logo_img_url,
            // 'cover_pic'=>$logo_img_url_cover,

        ];
        $company_id = Companies::createCompany($data);
        if (env('IS_S3_UPLOAD') && $logo !==null) {
            
            $file = $request->file('logo');
            $filename = 'TT' . str_pad($company_id, 5, '0', STR_PAD_LEFT) . "-" . $input['company_name'] . "." . $request->logo->extension();
            $key = date('m-Y') . '/' . 'company_logo/' . $company_id . '/' . $filename;
            $s3Url = ImageUtils::uploadImageOnS3($file, $key);
            Companies::updateLogo($key, $company_id);
        }
        $logo_img_url_cover  = '';
        if (env('IS_S3_UPLOAD') && $cover_pic_image !==null) {
            
            $file = $request->file('cover_pic');
            $filename = 'TT' . str_pad($company_id, 5, '0', STR_PAD_LEFT) . "-" . $input['company_name'] . "." . $request->cover_pic->extension();
            $key = date('m-Y') . '/' . 'company_cover_pic/' . $company_id . '/' . $filename;
            $s3Url = ImageUtils::uploadImageOnS3($file, $key);
            $cover_pic = Companies::updateCompany(['cover_pic' => $key], $company_id);
        }
        
        return redirect()->route('admin.auth.user.create',['type'=>'company admin','company_id' => $company_id])->withFlashSuccess(__('alerts.backend.company.created'));
        // return redirect()->route('admin.auth.allcompany.index')->withFlashSuccess(__('alerts.backend.company.created'));
    }

    public function edit($id){
        if(!auth()->user()->can('update_company')){
            abort(403);
        }
        $data_arr = ['industry_domains','company_sizes','location'];
        $data = MasterData::getMasterData($data_arr,'name');
        // $company = Companies::where('id', $id)->first();
        $query = Companies::withTrashed()->select('companies.*','location_table.name AS location_name','industry_domain_table.name AS industry_domain_name')
                ->leftJoin('master_data AS location_table', 'location_table.id', '=', 'companies.location_id')
                ->leftJoin('master_data AS industry_domain_table', 'industry_domain_table.id', '=', 'companies.industry_domain_id');

        
        $company = $query->where('companies.id', $id)->first();
        // dd($company);

        return view('backend.auth.company.edit', compact('data','company'));
    }
    public function update(Request $request){
        if(!auth()->user()->can('update_company')){
            abort(403);
        }
        $company = Companies::select('companies.*')->where('companies.id', $request->id)->first();
        
        $input = $request->all();
        $site = $input['website'];

        if (strpos($site, 'http') === false && strpos($site, 'https') === false) {
            $input['website'] = 'http://' . $request->website;
        }

        $url = str_replace('www.', '', parse_url($input['website'])['host']);
        // $existing = Companies::where('website', 'like', '%' . $url . '%')->select('id', 'name')->first();

        // if ($existing) {
        //     return redirect()->back()->withErrors(['website' => 'Company already exists'])->withInput($request->all());
        // }
        
       $company_id = $request->id;

        $logo = $request->file('logo');
        $cover_pic_image = $request->file('cover_pic');
        $logo_img_url = '';
        $key_logo = '';
        
        $data = [
            'name'=>$input['company_name'],
            'website'=>$input['website'],
            'location_id'=>$input['location'],
            'size_id'=>$input['company_size'],
            'industry_domain_id'=>$input['industry_domain'],
            // 'logo'=>$logo_img_url,
            // 'cover_pic'=>$key_cover_pic_image,

        ];
        Companies::updateCompany($data,$request->id);

        if (env('IS_S3_UPLOAD') && $logo !==null) {
            $file = $request->file('logo');
            $filename = 'TT' . str_pad($company_id, 5, '0', STR_PAD_LEFT) . "-" . $input['company_name'] . "." . $request->logo->extension();
            $key = date('m-Y') . '/' . 'company_logo/' . $company_id . '/' . $filename;
            $s3Url = ImageUtils::uploadImageOnS3($file, $key);
            Companies::updateLogo($key, $company_id);
        }
        $logo_img_url_cover  = '';
        if (env('IS_S3_UPLOAD') && $cover_pic_image !==null) {
            $file = $request->file('cover_pic');
            $filename = 'TT' . str_pad($company_id, 5, '0', STR_PAD_LEFT) . "-" . $input['company_name'] . "." . $request->cover_pic->extension();
            $key = date('m-Y') . '/' . 'company_cover_pic/' . $company_id . '/' . $filename;
            $s3Url = ImageUtils::uploadImageOnS3($file, $key);
            $cover_pic = Companies::updateCompany(['cover_pic' => $key], $company_id);
        }

        return redirect()->route('admin.auth.allcompany.index')->withFlashSuccess(__('The company successfully updated'));

    }

    public function createJob(RoleRepository $roleRepository,  User $user)
    {
        if(!auth()->user()->can('add_job')){
            abort(403);
        }
        $data_arr = ['industry_domains','company_sizes','location','job_types','salary_types','benefits','work_authorizations','joining_preferences','job_durations'];

        $data = MasterData::getMasterData($data_arr,'name');

        $data['skills'] = MasterData::select('id', 'type', 'name','value', 'description')->groupBy('name')->where('type','skills')->orderByRaw('CHAR_LENGTH(name)')->get();
        
        $companies = Companies::all();
        $usersWithRoles = User::with('roles')->get();
        // dd($usersWithRoles[0]);
        return view('backend.auth.company.job-create', compact('data','companies','usersWithRoles'));
    }

    public function storeJob(StoreCompanyJobRequest $request)
    {

        if(!auth()->user()->can('add_job')){
            abort(403);
        }
        
        $company_id = $request->company_id;
        $request['min_salary'] = $request->min_salary ? str_replace(',', '',$request->min_salary) : '';
        $request['max_salary'] = $request->max_salary ? str_replace(',', '',$request->max_salary) : '';
        $company_admin = User::role('company admin')->where('company_id',$company_id)->first();
        if ($company_admin) {
            $minimum_experience_required = $request->minimum_experience_required * 12;
            $maximum_experience_required = $request->maximum_experience_required * 12;
            $request->merge([
                'minimum_experience_required' => $minimum_experience_required,
                'maximum_experience_required' => $maximum_experience_required,
            ]);
            $job_id = Job::addUpdateJob($request, $request->user_id, $company_id);
            if ($job_id != '') {
                if ($request->has('required_skills') && count($request->required_skills) > 0) {
                    $required_skills =  MasterData::whereIn('name',$request->required_skills)->groupBy('name')->get();
                    $required_skills_ids =[];
                    foreach ($required_skills as $key => $requiredSkill) {
                        $required_skills_ids[] =$requiredSkill->id;
                    }
                    foreach ( $required_skills_ids as $required_skill) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $required_skill;
                        $temp_array['type']             = 'required_skills';

                        CompanyJobDetail::add($temp_array);
                    }
                }

                if ($request->has('additional_skills') && count($request->additional_skills) > 0) {
                    $additional_skills =  MasterData::whereIn('name',$request->additional_skills)->groupBy('name')->get();
                    $additional_skills_ids = [];
                    foreach ($additional_skills as $key => $additionalSkill) {
                        $additional_skills_ids[] = $additionalSkill->id;
                    }
                    foreach ($additional_skills_ids as $additional_skill) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $additional_skill;
                        $temp_array['type']             = 'additional_skills';

                        CompanyJobDetail::add($temp_array);
                    }

                }

                if ($request->has('work_locations') && count($request->work_locations) > 0) {

                    foreach ($request->work_locations as $work_location) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $work_location;
                        $temp_array['type']             = 'locations';

                        CompanyJobDetail::add($temp_array);
                    }
                }

                if ($request->has('benefits') && count($request->benefits) > 0) {

                    foreach ($request->benefits as $benefit) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $benefit;
                        $temp_array['type']             = 'benefits';

                        CompanyJobDetail::add($temp_array);
                    }
                }
                Job::updateSearchableHash($job_id);

                return redirect()->route('admin.auth.company.alljobs')->withFlashSuccess(__('alerts.backend.job.created'));
            } else {
                return redirect()->back()->withErrors(['company_id' => 'Something went wrong'])->withInput($request->all());
            }
        }else{
            return redirect()->back()->withErrors(['company_id' => 'No Company Admin exists in selected company'])->withInput($request->all());
        }
        
    }

    public function getDescriptioByChatGPT(Request $request)
    {
        try {
            $description = '';
            $title = $request->title;
            $title_type = $request->type ? $request->type : 'job';

            $command = 'Write '.$title_type.' description for '.$title.'?';
            $description = ChatGPT::getAIDescription($command);
            $description = explode("\n",$description);
            $description = implode("<br />", $description);
            return ['description' => $description];
            
        } catch (Exception $e) {
            return "error";
        }
    }

    public function companyList(Request $request){

        if(!auth()->user()->can('view_company')){
            abort(403);
        }
        $query = Companies::withTrashed()->select('companies.*','location_table.name AS location_name','industry_domain_table.name AS industry_domain_name',DB::raw("SUM(profile_view_transactions.amount) as amount"))
                ->leftJoin('master_data AS location_table', 'location_table.id', '=', 'companies.location_id')
                ->leftJoin('master_data AS industry_domain_table', 'industry_domain_table.id', '=', 'companies.industry_domain_id')
                ->leftJoin('profile_view_transactions', 'profile_view_transactions.company_id', '=', 'companies.id')
                ->groupBy('companies.id');

        $status = '';
        if ($request->has('status') && $request->status == 'Activated') {
            $query->whereNull('companies.deleted_at');
        }
        if ($request->has('status') && $request->status == 'Deactivated') {
            $query->onlyTrashed();
        }

        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('companies.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('companies.website', 'LIKE', '%' . $search . '%')
                    ->orWhere('website', 'LIKE', '%' . $search . '%')
                    ->orWhere('companies.updated_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('industry_domain_table.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('location_table.name', 'LIKE', '%' . $search . '%');
            });
        }
        if ($request->from_date && $request->to_date) {
             $from_date =date('Y-m-d 00:00:01', strtotime($request->from_date));
             $to_date = date('Y-m-d 23:59:59', strtotime($request->to_date));
             $query->whereBetween('companies.created_at', [$from_date, $to_date]);
        }
        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('updated_at', 'desc');
        }
        $type = 'companies';
        $companies = $query->orderBy('companies.updated_at', 'desc')->paginate(10);
        $table = view('backend.auth.company.company-table',compact('companies','type'))->render();
        $pagination = view('backend.auth.company.company-table-pagination',compact('companies','type'))->withUsers($companies->appends($request->except('page')))->render();

        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }
    public function jobsList(Request $request){

        if(!auth()->user()->can('view_job')){
            abort(403);
        }

        $query = Job::select('company_jobs.*','companies.name','companies.website')->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id');
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('min_salary', 'LIKE', '%' . $search . '%')
                    ->orWhere('companies.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%')
                    ->orWhere('max_salary', 'LIKE', '%' . $search . '%');

            });
        }
        if ($request->from_date && $request->to_date) {
             $from_date =date('Y-m-d 00:00:01', strtotime($request->from_date));
             $to_date = date('Y-m-d 23:59:59', strtotime($request->to_date));
             $query->whereBetween('company_jobs.created_at', [$from_date, $to_date]);
        }
        $status = '';
        if ($request->has('status') && $request->status == 'Activated') {
            $query->where('company_jobs.status','published');
        }
        if ($request->has('status') && $request->status == 'Deactivated') {
            $query->where('company_jobs.status','closed');
        }
        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('updated_at', 'desc');
        }
        $type = 'jobs';
        $jobs = $query->orderBy('updated_at', 'desc')->paginate(10);
        // dd($jobs[0] );
        $table = view('backend.auth.company.jobs-pagination.jobs-table',compact('jobs','type'))->render();
        $pagination = view('backend.auth.company.jobs-pagination.jobs-table-pagination',compact('jobs','type'))->withUsers($jobs->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);

    }
    public function ReportedJobsList(Request $request){
        if(!auth()->user()->can('view_reported_job')){
            abort(403);
        }
        $query = ReportedJobs::select('reported_jobs.*','company_jobs.title','company_jobs.status','company_jobs.id','master_data.name','users.first_name','users.last_name')
        ->leftJoin('company_jobs', 'company_jobs.id', '=', 'reported_jobs.job_id')
        ->leftJoin('users', 'users.id', '=', 'reported_jobs.user_id')
        ->leftJoin('master_data', 'master_data.id', '=', 'reported_jobs.issue_id');
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('company_jobs.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_jobs.id', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.last_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('master_data.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_jobs.status', 'LIKE', '%' . $search . '%')
                    ->orWhere('flag_msg', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $column = $request->input('column');
            $sort = $request->input('sort');
            $query->orderBy($column, $sort);
        }else{
            $query->orderBy('reported_jobs.updated_at', 'desc');
        }
        $type = 'jobs';
        $jobs = $query->orderBy('reported_jobs.updated_at', 'desc')->paginate(10);
        $table = view('backend.auth.company.reported-jobs-pagination.reported-jobs-table',compact('jobs','type'))->render();
        $pagination = view('backend.auth.company.reported-jobs-pagination.reported-jobs-table-pagination',compact('jobs','type'))->withUsers($jobs->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }

     public function paymentsListPagination(Request $request){
        $query = PaymentTransactions::select('payment_transactions.*','companies.name','users.first_name')
               ->leftJoin('companies', 'companies.id', '=', 'payment_transactions.company_id')
               ->leftJoin('users', 'users.id', '=', 'payment_transactions.user_id');
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'LIKE', '%' . $search . '%') 
                    ->orWhere('companies.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('payment_transactions.created_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('amount', 'LIKE', '%' . $search . '%');
            });
        }
        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('payment_transactions.updated_at', 'desc');
        }
        $type = 'payment-list';
        $payments = $query->orderBy('payment_transactions.updated_at', 'desc')->paginate(10);
        $table = view('backend.auth.company.payment-pagination.payment-table',compact('payments','type'))->render();
        $pagination = view('backend.auth.company.payment-pagination.payment-table-pagination',compact('payments','type'))->withUsers($payments->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }

    public function getCompanyUsers(Request $request){
        if ($request['company_id'] !=null) {
            $usersWithRoles = User::role(['company admin','company user'])->with('roles')->where('company_id', $request['company_id'])->get();
            return response()->json($usersWithRoles);
        }else{
            return 'error';
        }
    }
    public function draftJobEdit($id){
        if(!auth()->user()->can('update_job')){
            abort(403);
        }

        $data_arr = ['industry_domains','company_sizes','location','job_types','salary_types','benefits','work_authorizations','joining_preferences','job_durations'];

        $data = MasterData::getMasterData($data_arr,'name');

        $data['skills'] = MasterData::select('id', 'type', 'name','value', 'description')->groupBy('name')->where('type','skills')->orderByRaw('CHAR_LENGTH(name)')->get();
        $companies = Companies::all();
        $usersWithRoles = User::with('roles')->get();
        $jobs = Job::with('company_jod_detail')->find($id);
        $required_skills = [];
        $additional_skills = [];
        $locationsId = [];
        if (isset($jobs->company_jod_detail)) {
            foreach ($jobs->company_jod_detail as $key => $company_jod_detail) {
                if ($company_jod_detail->type === 'required_skills') {
                    $required_skills[] =  $company_jod_detail->data_id;
                }
                if ($company_jod_detail->type === 'additional_skills') {
                    $additional_skills[] =  $company_jod_detail->data_id;
                }
                if ($company_jod_detail->type === 'locations') {
                    $locationsId[] =  $company_jod_detail->data_id;
                }
            }
        }
        $selectedSkills = MasterData::select('id', 'name')->groupBy('name')->whereIn('id',$required_skills)->get();
        $additionalSkills = MasterData::select('id', 'name')->groupBy('name')->whereIn('id',$additional_skills)->get();
        $locations = MasterData::select('id', 'name')->groupBy('name')->whereIn('id',$locationsId)->get();
        $usersWithRoles = User::role(['company admin','company user'])->with('roles')->where('company_id', $jobs->company_id)->get();
   
        return view('backend.auth.company.job-edit', compact('data','companies','usersWithRoles','jobs','selectedSkills','additionalSkills','locations'));

    }

     public function draftJobUpdate(StoreCompanyJobRequest $request){

        if(!auth()->user()->can('update_job')){
            abort(403);
        }
        $company_id = $request->company_id;
        $request['min_salary'] = $request->min_salary ? str_replace(',', '',$request->min_salary) : '';
        $request['max_salary'] = $request->max_salary ? str_replace(',', '',$request->max_salary) : '';
        $company_admin = User::role('company admin')->where('company_id',$company_id)->first();
        if ($company_admin) {
            $minimum_experience_required = $request->minimum_experience_required * 12;
            $maximum_experience_required = $request->maximum_experience_required * 12;
            $request->merge([
                'minimum_experience_required' => $minimum_experience_required,
                'maximum_experience_required' => $maximum_experience_required,
            ]);
            $job_id = Job::addUpdateJob($request, $request->user_id, $company_id);
            if ($job_id != '') {
                if ($request->has('required_skills') && count($request->required_skills) > 0) {
                    $required_skills =  MasterData::whereIn('name',$request->required_skills)->select('id')->groupBy('name')->get();
                    $required_skills_ids =[];
                    foreach ($required_skills as $key => $requiredSkill) {
                        $required_skills_ids[] =$requiredSkill->id;
                    }
                   $delete =  CompanyJobDetail::where(['type'=>'required_skills','company_job_id'=>$job_id])->delete();
                    foreach ($required_skills_ids as $required_skill) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $required_skill;
                        $temp_array['type']             = 'required_skills';
                        CompanyJobDetail::add($temp_array);
                    }
                }else{
                    CompanyJobDetail::where(['type'=>'required_skills','company_job_id'=>$job_id])->delete();
                }

                if ($request->has('additional_skills') && count($request->additional_skills) > 0) {
                      $additional_skills =  MasterData::whereIn('name',$request->additional_skills)->groupBy('name')->get();
                       $additional_skills_ids =[];
                       foreach ($additional_skills as $key => $additionalSkill) {
                            $additional_skills_ids[] =$additionalSkill->id;
                       }
                    $delete = CompanyJobDetail::where(['type'=>'additional_skills','company_job_id'=>$job_id])->delete();
                    foreach ($additional_skills_ids as $additional_skill) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $additional_skill;
                        $temp_array['type']             = 'additional_skills';
                        CompanyJobDetail::add($temp_array);
                    }
                }else{
                    CompanyJobDetail::where(['type'=>'additional_skills','company_job_id'=>$job_id])->delete();
                }

                if ($request->has('work_locations') && count($request->work_locations) > 0) {
                    CompanyJobDetail::where(['type'=>'locations','company_job_id'=>$job_id])->delete();
                    foreach ($request->work_locations as $work_location) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $work_location;
                        $temp_array['type']             = 'locations';
                        // CompanyJobDetail::deleteData($temp_array['company_job_id']);
                        CompanyJobDetail::add($temp_array);
                    }
                }else{
                    CompanyJobDetail::where(['type'=>'locations','company_job_id'=>$job_id])->delete();
                }

                if ($request->has('benefits') && count($request->benefits) > 0) {

                    foreach ($request->benefits as $benefit) {
                        $temp_array = array();
                        $temp_array['company_job_id']   = $job_id;
                        $temp_array['data_id']          = $benefit;
                        $temp_array['type']             = 'benefits';
                        // CompanyJobDetail::deleteData($temp_array['company_job_id']);
                        CompanyJobDetail::add($temp_array);
                    }
                }

                Job::updateSearchableHash($job_id);


                return redirect()->route('admin.auth.company.alljobs')->withFlashSuccess(__('Job successfully updated.'));
            } else {
                return redirect()->back()->withErrors(['company_id' => 'Something went wrong'])->withInput($request->all());
            }
        }else{
            return redirect()->back()->withErrors(['company_id' => 'No Company Admin exists in selected company'])->withInput($request->all());
        }
    }

    public function exportCompanies(Request $request){
        $from_date =date('Y-m-d', strtotime($request->from_date)).' 00:00:01';
        $to_date = date('Y-m-d', strtotime($request->to_date)).' 23:59:59';
        $companies = Companies::withTrashed()->select('companies.*','location_table.name AS location_name','industry_domain_table.name AS industry_domain_name',DB::raw("SUM(profile_view_transactions.amount) as amount"),DB::raw('count(company_jobs.company_id) as company_jobs_count'),DB::raw('count(company_gigs.company_id) as company_gigs_count'),DB::raw('count(users.company_id) as members_count') )
                ->leftJoin('master_data AS location_table', 'location_table.id', '=', 'companies.location_id')
                ->leftJoin('master_data AS industry_domain_table', 'industry_domain_table.id', '=', 'companies.industry_domain_id')
                ->leftJoin('profile_view_transactions', 'profile_view_transactions.company_id', '=', 'companies.id')
                ->leftJoin('company_jobs', 'company_jobs.company_id', '=', 'companies.id')
                ->leftJoin('company_gigs', 'company_gigs.company_id', '=', 'companies.id')
                ->leftJoin('users', 'users.company_id', '=', 'companies.id')
                ->groupBy('companies.id')
                ->whereBetween('companies.created_at', [$from_date, $to_date])
                ->get();
        $result = array();

        foreach ($companies as $key => $company) {
            $result[] = array(
              'name'                  => $company ? $company->name :'',
              'members_count'         => $company->members_count > 0 ? $company->members_count : '0',
              'company_jobs_count'    => $company->company_jobs_count > 0 ? $company->company_jobs_count : '0',
              'company_gigs_count'    => $company->company_gigs_count > 0 ? $company->company_gigs_count : '0',
              'size_id'               => $company->size ? @$company->size->name:'',
              'location_name'    => $company->location_name  ? $company->location_name : '',
              'industry_domain_name'    => $company->industry_domain_name  ? $company->industry_domain_name : '',
              'amount'    => $company->amount !=null  ? $company->amount : '0',
              'created_at'            => date('d-m-Y', strtotime($company->created_at)),
            );
        }
        $export = new ExportCompanies($result);
        return Excel::download($export, 'companies-'.$from_date.'.xlsx');
    }

    public function addSkills(Request $request){
        try {
            $checkSkillExists = MasterData::checkSkillExists($request->latest_text);
            if ($checkSkillExists) {
                $error_msg = '';
                 $data = Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::ADD_SKILL_SUCCESS_MSG, MasterData::getSkills()));
                 return ($data);

                 return 'success';
            }

            $add_skill = MasterData::addSkill($request->latest_text);
                Communicator::returnResponse(ResponseMessages::SUCCESS(StringConstants::ADD_SKILL_SUCCESS_MSG, MasterData::getSkills()));
                return 'success';
        } catch (Exception $e) {
            return 'error';//Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }

    public function duplicateJob($id)
    {
        try {
            if(Job::duplicateJob($id))
                return redirect()->route('admin.auth.company.alljobs')->withFlashSuccess(__('Job duplicated successfully.'));
            else
                return redirect()->route('admin.auth.company.alljobs')->withFlashError(__('Something went wrong.'));

        } catch (Exception $e) {
            return redirect()->route('admin.auth.company.alljobs')->withFlashError(__('Something went wrong.'));
        }
    }
    public function exportJobs(Request $request){
      
        $from_date =date('Y-m-d', strtotime($request->from_date)).' 00:00:01';
        $to_date = date('Y-m-d', strtotime($request->to_date)).' 23:59:59';
        $jobs =  Job::select('company_jobs.title AS job_title','company_jobs.status AS status','company_jobs.created_at AS posted_on','companies.name AS company_name',DB::Raw("CONCAT(users.first_name, ' ', users.last_name) AS posted_by") ,DB::raw('count(candidate_jobs.job_id) as applicants') )
            ->leftJoin('companies', 'companies.id', '=', 'company_jobs.company_id')
            ->leftJoin('users', 'users.id', '=', 'company_jobs.user_id')
            ->leftJoin('candidate_jobs', 'candidate_jobs.job_id', '=', 'company_jobs.id')
            ->whereBetween('company_jobs.created_at', [$from_date, $to_date])
            ->groupBy('company_jobs.id')
            ->get();
      
        $result = array();
        foreach ($jobs as $key => $job) {
            $result[] = array(
              'Company Name'         => $job ? $job->company_name :'',
              'job Title'            => $job ? $job->job_title :'',
              'Applicants'           => $job->applicants > 0 ? $job->applicants : '0',
              'Posted By'            => $job ? $job->posted_by: '',
              'Posted On'            => $job ? date('d-m-Y', strtotime($job->posted_on))  : '',
              'status'               => $job ? $job->status:''
            );
        }

        $export = new ExportJobs($result);
        return Excel::download($export, 'jobs-'.$from_date.'.xlsx');
    }
}
