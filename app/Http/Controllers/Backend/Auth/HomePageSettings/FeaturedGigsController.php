<?php

namespace App\Http\Controllers\Backend\Auth\HomePageSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeaturedGig;
use App\Models\CompanyGig;
use App\Models\MasterData;
use App\Models\Companies;
use App\Models\Job;
use App\Models\Auth\User;
use App\Models\ReportedGig;
use App\Exports\ExportGigs;
use Maatwebsite\Excel\Facades\Excel;
use App\Constants\StringConstants;
use App\Http\Requests\API\AddEditGigRequest;
use App\Models\CompanyGigDetail;
use Illuminate\Support\Facades\DB;
use Auth;
class FeaturedGigsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->can('view_featured_gigs')){
            abort(403);
        }
        $featuredGigs = FeaturedGig::getFeaturedGigs();
        $featuredGigIds = [];
        foreach ($featuredGigs as $key => $featuredGig) {
            $featuredGigIds[] = $featuredGig->id;
        }
        $companyGigs = CompanyGig::where('status','published')->whereNotIn('id',$featuredGigIds)->with('user','company')->get();
        return view('backend.auth.homepage_settings.featured_gigs.index', compact('featuredGigs','companyGigs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.auth.homepage_settings.featured_gigs.create-edit', compact('featuredGigs','companyGigs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->can('add_featured_gigs')){
            abort(403);
        }
        if($request->has('order') && $request->order != ''){
            $order = $request->order;
        }else{
            $get_order = FeaturedGig::orderBy('order','DESC')->skip(0)->take(1)->first();
            $order = $get_order && $get_order->order != null ? $get_order->order + 1 : 1;

        }
        $addJob = FeaturedGig::create(['gig_id' => $request->gig_ids,'order' => $order]);
       return "success";
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!auth()->user()->can('delete_featured_gigs')){
            abort(403);
        }
        try {
            $gig_id = $request->get('id');
            $remove_order = FeaturedGig::where('gig_id',$gig_id)->update(['order' => null]);
            $gig = FeaturedGig::where('gig_id',$gig_id)->delete();
            return "success";
            
            
        } catch (\Exception $e) {
            echo $e->getMessage();
            return "error";
            
        }
    }
    public function gigsOrderChange(Request $request)
    {
        if(!auth()->user()->can('update_featured_gigs')){
            abort(403);
        }
        $ids = count($request['ids']);
        $data =[];
        for ($i=0; $i <=$ids ; $i++) { 
            $id = $i+1;
            if (@$request['ids'][$i] !=null) {
               $FeaturedGig = FeaturedGig::where('gig_id',@$request['ids'][$i])->update(['order'=>$id]);
            }
        }
        
       
        return "success";
       // return redirect()->route('admin.auth.homepage-logos.index')->withFlashSuccess("Logo Rearrange successfully!");
    }

    public function allGigsList(Request $request)
    {
        if(!auth()->user()->can('add_featured_gigs')){
            abort(403);
        }
        $featuredGigs = FeaturedGig::getFeaturedGigs();
        $featuredGigIds = [];
        foreach ($featuredGigs as $key => $featuredGig) {
            $featuredGigIds[] = $featuredGig->id;
        }
        $query = CompanyGig::whereNotIn('company_gigs.id',$featuredGigIds)->where('company_gigs.status','published')->select('company_gigs.*','companies.name AS company_name','companies.id AS company_id','companies.logo','users.first_name','users.id AS user_id','users.last_name')
        ->leftJoin('companies', 'companies.id', '=', 'company_gigs.company_id')
        ->leftJoin('users', 'users.id', '=', 'company_gigs.user_id');
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('company_gigs.id', 'LIKE', '%' . $search . '%')
                    ->orWhere('companies.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_gigs.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_gigs.created_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.first_name', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('company_gigs.updated_at', 'desc');
        }
        $orderId =  $request->input('type');
        $type = 'allgigslist';
        $companyGigs = $query->orderBy('company_gigs.updated_at', 'desc')->paginate(10);
        $table = view('backend.auth.homepage_settings.featured_gigs.all-gigs.gigs-table',compact('companyGigs','type','orderId'))->render();
        $pagination = view('backend.auth.homepage_settings.featured_gigs.all-gigs.gigs-pagination',compact('companyGigs','type','orderId'))->withUsers($companyGigs->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }
    public function allgigs(){
         if(!auth()->user()->can('view_gig')){
            abort(403);
        }
        return view('backend.auth.company.gigs.index');
    }

    public function gigsParticularSectionList(Request $request){
       
        if(!auth()->user()->can('view_featured_gigs')){
            abort(403);
        }
        $query = CompanyGig::select('company_gigs.*','companies.name AS company_name','companies.id AS company_id','companies.logo','users.first_name','users.id AS user_id','users.last_name')
        ->leftJoin('companies', 'companies.id', '=', 'company_gigs.company_id')
        ->leftJoin('users', 'users.id', '=', 'company_gigs.user_id');
        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('company_gigs.id', 'LIKE', '%' . $search . '%')
                    ->orWhere('companies.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_gigs.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_gigs.created_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.first_name', 'LIKE', '%' . $search . '%');
            });
        }
        if ($request->from_date && $request->to_date) {
             $from_date =date('Y-m-d 00:00:01', strtotime($request->from_date));
             $to_date = date('Y-m-d 23:59:59', strtotime($request->to_date));
             $query->whereBetween('company_gigs.created_at', [$from_date, $to_date]);
        }
        $status = '';
        if ($request->has('status') && $request->status == 'Activated') {
            $query->where('company_gigs.status','published');
        }
        if ($request->has('status') && $request->status == 'Deactivated') {
            $query->where('company_gigs.status','closed')
                    ->orWhere('company_gigs.status','draft');
        }
        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('company_gigs.updated_at', 'desc');
        }
        $orderId =  $request->input('type');
        $type = 'allgigslist';
        $companyGigs = $query->orderBy('company_gigs.updated_at', 'desc')->paginate(10);
        $table = view('backend.auth.company.gigs.gigs-table',compact('companyGigs','type','orderId'))->render();
        $pagination = view('backend.auth.company.gigs.gigs-table-pagination',compact('companyGigs','type','orderId'))->withUsers($companyGigs->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }
    public function deactivateGig(Request $request)
    {
        try {
            $job = CompanyGig::find($request->get('gig_id'));
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

    public function allReportedGigs(){
         if(!auth()->user()->can('view_featured_gigs')){
            abort(403);
        }
        return view('backend.auth.company.gigs-reported.index');
    }

    public function reportedGigsSectionList(Request $request){
        
        $query = ReportedGig::select('company_gigs.*','companies.name AS company_name','companies.id AS company_id','companies.logo','users.first_name','users.id AS user_id','users.last_name')
            ->join('company_gigs', 'company_gigs.id', '=', 'reported_gigs.gig_id')
            ->leftJoin('companies', 'companies.id', '=', 'company_gigs.company_id')
            ->leftJoin('users', 'users.id', '=', 'company_gigs.user_id');

        if ($request->has('q') && $request->input('q') != '') {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('company_gigs.id', 'LIKE', '%' . $search . '%')
                    ->orWhere('companies.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_gigs.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_gigs.status', 'LIKE', '%' . $search . '%')
                    ->orWhere('company_gigs.created_at', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.first_name', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('company_gigs.updated_at', 'desc');
        }

        if ($request->has('column') && $request->input('column') != '' && $request->has('sort') && $request->input('sort') != ''){
            $query->orderBy($request->input('column'), $request->input('sort'));
        }else{
            $query->orderBy('company_gigs.updated_at', 'desc');
        }
        $orderId =  $request->input('type');
        $type = 'allreportedgigslist';
        $companyGigs = $query->orderBy('company_gigs.updated_at', 'desc')->paginate(10);
        $table = view('backend.auth.company.gigs-reported.reported-gigs-table',compact('companyGigs','type','orderId'))->render();
        $pagination = view('backend.auth.company.gigs-reported.reported-gigs-table-pagination',compact('companyGigs','type','orderId'))->withUsers($companyGigs->appends($request->except('page')))->render();
        return response()->json([
            'status' => true,
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }
    public function deactivateReportedGig(Request $request)
    {
        try {
            $job = CompanyGig::find($request->get('gig_id'));
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

    public function duplicateGig($id)
    {
        try {
            if(CompanyGig::duplicateGig($id))
                return redirect()->route('admin.auth.gigs.allgigs')->withFlashSuccess(__('Gig duplicated successfully.'));
            else
                return redirect()->route('admin.auth.gigs.allgigs')->withFlashError(__('Something went wrong.'));

        } catch (Exception $e) {
            return redirect()->route('admin.auth.gigs.allgigs')->withFlashError(__('Something went wrong.'));
        }
    }
    public function reNewGig($id)
    {
        try {
            $id = $id;
            $user_id    = Auth::guard('web')->user()->id;
            $date = date('Y-m-d H:i:s');
            $gig_renew = CompanyGig::reNewGig($id,$user_id,$date);
            return redirect()->route('admin.auth.gigs.allgigs')->withFlashSuccess(__('Gig Renew successfully.'));
        } catch (Exception $e) {
            return redirect()->route('admin.auth.gigs.allgigs')->withFlashError(__('Something went wrong.'));
        }
    }
    public function exportGigs(Request $request){
        $from_date =date('Y-m-d', strtotime($request->from_date)).' 00:00:01';
        $to_date = date('Y-m-d', strtotime($request->to_date)).' 23:59:59';
        $gigs = CompanyGig::select('company_gigs.title AS gig_title','company_gigs.id AS gig_id','company_gigs.status AS status','company_gigs.created_at AS posted_on','companies.name AS company_name','companies.logo',DB::Raw("CONCAT(users.first_name, ' ', users.last_name) AS posted_by") ,DB::raw('count(candidate_gigs.gig_id) as applicants'))
            ->leftJoin('companies', 'companies.id', '=', 'company_gigs.company_id')
            ->leftJoin('users', 'users.id', '=', 'company_gigs.user_id')
            ->leftJoin('candidate_gigs', 'candidate_gigs.gig_id', '=', 'company_gigs.id')
            ->whereBetween('company_gigs.created_at', [$from_date, $to_date])
            ->groupBy('company_gigs.id')
            ->get();
        $result = array();
        foreach ($gigs as $key => $gig) {
            $result[] = array(
              'Company Name'         => $gig ? $gig->company_name :'',
              'Gig Title'            => $gig ? $gig->gig_title :'',
              'Applicants'           => $gig->applicants > 0 ? $gig->applicants : '0',
              'Posted By'            => $gig ? $gig->posted_by: '',
              'Posted On'            => $gig ? date('d-m-Y', strtotime($gig->posted_on))  : '',
              'status'               => $gig ? $gig->status:''
            );
        }

        $export = new ExportGigs($result);
        return Excel::download($export, 'gigs-'.$from_date.'.xlsx');
    }
    public function createGig($id=''){
        if(!auth()->user()->can('add_gig')){
            abort(403);
        }

        $data_arr = ['location','gig_types','engagement_mode'];

        $data = MasterData::getMasterData($data_arr,'name');
        $data['skills'] = MasterData::select('id', 'type', 'name','value', 'description')->groupBy('name')->where('type','skills')->orderByRaw('CHAR_LENGTH(name)')->get();
        $companies = Companies::all();
        $usersWithRoles = User::with('roles')->get();
        if ($id) {
            $companyGigs = CompanyGig::with('details')->find($id);
        }else{
            $companyGigs = new CompanyGig();
        }
        $required_skills = [];
        $locationsId = [];
        if (isset($companyGigs->details)) {
            foreach ($companyGigs->details as $key => $details) {
                if ($details->type === 'required_skills') {
                    $required_skills[] =  $details->data_id;
                }
               
                if ($details->type === 'locations') {
                    $locationsId[] =  $details->data_id;
                }
            }
        }
        $selectedSkills = MasterData::select('id', 'name')->groupBy('name')->whereIn('id',$required_skills)->get();
        $locations = MasterData::select('id', 'name')->groupBy('name')->whereIn('id',$locationsId)->get();
        $usersWithRoles = User::role(['company admin','company user'])->with('roles')->where('company_id', $companyGigs->company_id)->get();
   
        return view('backend.auth.company.gigs.create-edit', compact('data','companies','usersWithRoles','companyGigs','selectedSkills','locations'));
    }

    public function gigAddEdit(AddEditGigRequest $request)
    {

        try {
            if (isset($request->id) && $request->id != null && $request->id != '') {
                if(!auth()->user()->can('update_gig')){
                    abort(403);
                }
                CompanyGigDetail::deleteData($request->id);
                $message = StringConstants::GIG_UPDATE_SUCCESS_MSG;
            }
            if(!auth()->user()->can('add_gig')){
                abort(403);
            }
            $user_id    = $request->user_id;
            $company_id    = $request->company_id;
            $request['min_budget'] = $request->min_budget ? str_replace(',', '',$request->min_budget) : '';
            $request['max_budget'] = $request->max_budget ? str_replace(',', '',$request->max_budget) : '';
            $gig = CompanyGig::addUpdateGig($request, $user_id, $company_id);
            if ($gig != '') {
                $gigId = $gig->id;
                if ($request->has('required_skills') && count($request->required_skills) > 0) {
                    $required_skills =  MasterData::whereIn('name',$request->required_skills)->groupBy('name')->get();
                    $required_skills_ids =[];
                    foreach ($required_skills as $key => $requiredSkill) {
                        $required_skills_ids[] =$requiredSkill->id;
                    }
                    foreach ( $required_skills_ids as $required_skill) {
                        $temp_array = array();
                        $temp_array['company_gig_id']   = $gigId;
                        $temp_array['data_id']          = $required_skill;
                        $temp_array['type']             = 'required_skills';
                        CompanyGigDetail::add($temp_array);
                    }
                }
                if ($request->has('work_locations') && count($request->work_locations) > 0) {
                    foreach ($request->work_locations as $work_location) {
                        $temp_array = array();
                        $temp_array['company_gig_id']   = $gigId;
                        $temp_array['data_id']          = $work_location;
                        $temp_array['type']             = 'locations';
                        CompanyGigDetail::add($temp_array);
                    }
                }
                CompanyGig::updateSearchableHash($gigId);
                return redirect()->route('admin.auth.gigs.allgigs')->withFlashSuccess(__('alerts.backend.gigs.saved'));
            } else {
                return redirect()->back()->withErrors(['company_id' => 'Something went wrong'])->withInput($request->all());
            }
        } catch (Exception $e) {
            return Communicator::returnResponse(ResponseMessages::EXCEPTIONAL_HANDLING($e));
        }
    }
}
