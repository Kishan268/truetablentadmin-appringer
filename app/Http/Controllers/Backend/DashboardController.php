<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Companies;
use App\Models\Job;
use App\Models\CompanyGig;
use App\Models\Auth\User;
use App\Models\SystemSettings;
use App\Models\PaymentTransactions;
use App\Models\FeaturedJob;
use App\Models\FeaturedGig;
use App\AppRinger\Logger;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {       
        
        $start_date = date('Y-01-01');
        $end_date = date('Y-12-t');

        
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;


        return view('backend.dashboard2', compact('data'));
    }

    public function system_settings(Request $request){
        if($request->method() == 'GET'){
            $SystemSettings = SystemSettings::first();
            return view('backend.settings', compact('SystemSettings'));
        }
        $data = $request->all();
        unset($data['_token']);
        SystemSettings::updateOrCreate(['id' => 1], $data);

        return redirect()->route('admin.system_settings')->withFlashSuccess(__('alerts.backend.settings_saved'));
    }

    public function companiesData(Request $request){
        $previous_range = [];        
        

        $start_date = date('Y-01-01');
        $end_date = date('Y-12-t');

        if($request->has('start') && $request->start != '' && $request->has('end') && $request->end){
            $start_date = strtotime($request->start);
            $start_date = date('Y-m-01',$start_date);

            $end_date = strtotime($request->end);
            $end_date = date('Y-m-t',$end_date);

        }

        $ts1 = strtotime($start_date);
        $ts2 = strtotime($end_date);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

        $start    = (new \DateTime($start_date))->modify('first day of this month');
        $end      = (new \DateTime($end_date))->modify('last day of this month');

        $diif_months = $diff + 1;
        $previous_range['first_date'] = date('Y-m-d 00:00:01', strtotime($start_date. ' - '.$diif_months.' months'));
        $previous_range['last_date'] = date('Y-m-d 23:59:59', strtotime($end_date. ' - '.$diif_months.' months'));

        $data = [
            'payments' => PaymentTransactions::pluck('amount'),
            'candidates' => User::role('candidate')->count(),
            'companies_by_industries' => Companies::select(\DB::raw('count(companies.id) as company_count'),'master_data.name','master_data.value')->leftJoin('master_data', 'master_data.id', '=', 'companies.industry_domain_id')->whereBetween('companies.created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->orderBy('company_count','desc')->groupBy('companies.industry_domain_id')->skip(0)->take(10)->get(),
            'total_companies' => Companies::withTrashed()->where('created_at','<=',$end_date." 23:59:59")->count(),
            'previous_range_new_companies' => Companies::whereBetween('created_at',[$previous_range['first_date'],$previous_range['last_date']])->count(),
            'new_companies' => Companies::whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),
            'active_companies' => Companies::where('deleted_at',null)->where('created_at','<=',$end_date." 23:59:59")->count(),
            'previous_range_active_companies' => Companies::where('deleted_at',null)->where('created_at','<=',$previous_range['last_date'])->count(),
        ];
        $total = 0;
        $data['payments']->map(function($amt) use(&$total){
            $total += (double) str_replace('$', '', $amt);
        });
        $data['payments'] = $total;
        $companies_by_industries_graph = [];
        $companies_by_industries_graph['labels'] = [];
        $companies_by_industries_graph['data'] = [];
        foreach ($data['companies_by_industries'] as $value) {
            $companies_by_industries_graph['labels'][] = $value->name;
            $companies_by_industries_graph['data'][] = $value->company_count;
        }

        $data['companies_by_industries_graph']['labels'] = $companies_by_industries_graph['labels'];
        $data['companies_by_industries_graph']['data'] = $companies_by_industries_graph['data'];


        $data_companies = [];
        $data_new_companies = [];
        $graph2_data = [];
        $labels = [];

        $dates = [];
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $dates[] = $dt->format("Y-m");
        }

        for ($i = 0; $i <= $diff; $i++) {
            $d = strtotime($dates[$i]);
            $m = date('m', $d);
            $y = date('Y', $d);
            $month = date("M", mktime(0, 0, 0, $m, 1));
            $labels[] = $month;
            $month_first_date = date($y."-m-01 00:00:01", mktime(0, 0, 0, $m, 1));
            $month_last_date = date($y."-m-t 23:59:59", mktime(0, 0, 0, $m, 1));


            $data_companies[] = Companies::where('created_at','<=',$month_last_date)->count();
            $data_new_companies[] = Companies::whereBetween('created_at',[$month_first_date,$month_last_date])->count();
            
        }

        $datasets_new = [
            'label' => 'Newly Added',
            'data' => $data_new_companies,    
            'backgroundColor' => '#5542F6',     
        ];

        $datasets_all = [
            'label' => 'All Companies',
            'data' => $data_companies,    
            'backgroundColor' => '#C8C2FC',     
        ];

        $graph1_data['labels'] = $labels;
        $graph1_data['datasets'][] = $datasets_new;
        $graph1_data['datasets'][] = $datasets_all;

        $data['graph1_data'] = $graph1_data;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;


        return view('backend.dashboard-tabs.companies', compact('data'));
    }

    public function candicatesData(Request $request){
        $previous_range = [];        
        $start_date = date('Y-01-01');
        $end_date = date('Y-12-t');

        if($request->has('start') && $request->start != '' && $request->has('end') && $request->end){
            $start_date = strtotime($request->start);
            $start_date = date('Y-m-01',$start_date);

            $end_date = strtotime($request->end);
            $end_date = date('Y-m-t',$end_date);

        }

        $ts1 = strtotime($start_date);
        $ts2 = strtotime($end_date);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

        $start    = (new \DateTime($start_date))->modify('first day of this month');
        $end      = (new \DateTime($end_date))->modify('last day of this month');

        $diif_months = $diff + 1;
        $previous_range['first_date'] = date('Y-m-d 00:00:01', strtotime($start_date. ' - '.$diif_months.' months'));
        $previous_range['last_date'] = date('Y-m-d 23:59:59', strtotime($end_date. ' - '.$diif_months.' months'));


        $data = [
            'all_candidates' => User::role('candidate')->whereBetween('users.created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->skip(0)->take(10)->select('gender')->selectRaw('gender, sum(gender) as sum')->groupBy('gender')->get(),
            'candidates_by_industries' => User::role('candidate')->select(\DB::raw('count(users.id) as company_count'))->whereBetween('users.created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->orderBy('company_count','desc')->skip(0)->take(10)->get(),
            'total_candidates' =>User::role('candidate')->withTrashed()->where('created_at','<=',$end_date." 23:59:59")->count(),
            'previous_range_new_candidates' => User::role('candidate')->whereBetween('created_at',[$previous_range['first_date'],$previous_range['last_date']])->count(),
            'new_candidates' => User::role('candidate')->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),
            'active_candidates' => User::role('candidate')->where('deleted_at',null)->where('created_at','<=',$end_date." 23:59:59")->count(),
            'previous_range_active_candidates' => Companies::where('deleted_at',null)->where('created_at','<=',$previous_range['last_date'])->count(),

            'previous_range_new_candidates_age' => User::role('candidate')->whereBetween('created_at',[$previous_range['first_date'],$previous_range['last_date']])->select('gender')->selectRaw('gender, sum(gender) as sum')->groupBy('gender')->get(),

            'new_candidates_age' => User::role('candidate')->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->select('gender')->selectRaw('gender, sum(gender) as sum')->groupBy('gender')->get(),

            'male_candidates' => User::role('candidate')->where('gender','Male')->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),
            'female_candidates' => User::role('candidate')->where('gender','Female')->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),

            'previous_male_candidates' => User::role('candidate')->where('gender','Male')->whereBetween('created_at',[$previous_range['first_date'],$previous_range['last_date']])->count(),
            'previous_female_candidates' => User::role('candidate')->where('gender','Female')->whereBetween('created_at',[$previous_range['first_date'],$previous_range['last_date']])->count(),

        ];

        $keys = [ 'users.first_name', 'users.last_name', 'users.email', 'users.avatar_location', 'user_work_profiles.contact_number', 'user_work_profiles.location_name', 'user_work_profiles.joining_preference_id', 'user_work_profiles.summary', 'user_work_profiles.cv_link' , 'user_work_profiles.total_experience' ];

        Logger::logDebug("[GET INCOMPLETE PROFILES]");
        \DB::enableQueryLog();
        $incompleteProfileQuery =   User::role('candidate')->whereBetween('users.created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->leftJoin('user_work_profiles', 'users.id', '=', 'user_work_profiles.user_id');
                // ->leftJoin('user_work_profile_details', 'users.id', '=', 'user_work_profile_details.user_id');
                // ->leftJoin('user_work_profile_details AS skill_table', function($join)
                // {
                //     $join->on('skill_table.user_id', '=', 'users.id');
                //     $join->where('skill_table.type','=', 'skill');
                // })
                // ->where('skill_table.id',null);

        $incompleteProfileQuery->where(function ($query) use ($keys) {
            foreach ($keys as $key) {
                $query->orWhere($key,'=', NULL);
                $query->orWhere($key,'=' ,'');
            }
        });
        

        $incompleteProfileCount = $incompleteProfileQuery->count();

        Logger::logDebug("Query build: " . json_encode(\DB::getQueryLog()));

        $previousIncompleteProfileQuery =   User::role('candidate')->whereBetween('users.created_at',[$previous_range['first_date'],$previous_range['last_date']])->leftJoin('user_work_profiles', 'users.id', '=', 'user_work_profiles.user_id');
                // ->leftJoin('user_work_profile_details', 'users.id', '=', 'user_work_profile_details.user_id')
                //  ->leftJoin('user_work_profile_details AS skill_table', function($join)
                // {
                //     $join->on('skill_table.user_id', '=', 'users.id');
                //     $join->where('skill_table.type','=', 'skill');
                // })
                // ->where('skill_table.id',null);

        $previousIncompleteProfileQuery->where(function ($query) use ($keys) {
            foreach ($keys as $key) {
                $query->orWhere($key,'=', NULL);
                $query->orWhere($key,'=' ,'');
            }
        });

        $previousIncompleteProfileCount =   $previousIncompleteProfileQuery->count();

        // $incompleteProfile =   User::role('candidate')->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->get();
        // $incompleteProfileCount = 0;
        // $previousIncompleteProfileCount = 0;
        // foreach ($incompleteProfile as $key => $user) {
        //     if(User::getUserProfileProgress($user) < 100){
        //         $incompleteProfileCount += 1;
        //     }
        // }

        // foreach ($incompleteProfilePrevious as $key => $user) {
        //     if(User::getUserProfileProgress($user) < 100){
        //         $previousIncompleteProfileCount += 1;
        //     }
        // }

        $candidates_by_industries_graph = [];
        $candidates_by_industries_graph['labels'] = [];
        $candidates_by_industries_graph['data'] = [];


        foreach ($data['all_candidates'] as $value) {
            if ($value->sum !==null) {
              $candidates_by_industries_graph['labels'][] = $value->gender;
              $candidates_by_industries_graph['data'][] = $value->sum;
            }
            
        }

        $data['candidates_by_industries_graph']['labels'] = $candidates_by_industries_graph['labels'];
        $data['candidates_by_industries_graph']['data'] = $candidates_by_industries_graph['data'];

        $data_candidates = [];
        $data_new_candidates = [];
        $graph2_data = [];
        $labels = [];

        $dates = [];
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $dates[] = $dt->format("Y-m");
        }

        for ($i = 0; $i <= $diff; $i++) {
            $d = strtotime($dates[$i]);
            $m = date('m', $d);
            $y = date('Y', $d);
            $month = date("M", mktime(0, 0, 0, $m, 1));
            $labels[] = $month;
            $month_first_date = date($y."-m-01 00:00:01", mktime(0, 0, 0, $m, 1));
            $month_last_date = date($y."-m-t 23:59:59", mktime(0, 0, 0, $m, 1));


            $data_candidates[] = User::role('candidate')->where('created_at','<=',$month_last_date)->count();
            $data_new_candidates[] = User::role('candidate')->whereBetween('created_at',[$month_first_date,$month_last_date])->count();
            
            $ranges = [ // the start of each age-range.
                '20-30 years' => 20,
                '30-40 years' => 30,
                '40-50 years' => 40,
                '50-60 years' => 50
            ];
            $data['dataset'] = User::role('candidate')
             ->whereBetween('created_at',[$month_first_date,$month_last_date])
            ->get()
            ->map(function ($user) use ($ranges) {
                $age = \Carbon\Carbon::parse($user->date_of_birth)->age;
                foreach($ranges as $key => $breakpoint)
                {
                    if ($breakpoint >= $age)
                    {
                        $user->range = $key;
                        break;
                    }
                }

                return $user;
            })
            ->mapToGroups(function ($user, $key) {
                // return [$user->range];
                return [$user->range => $user];
            })
            ->map(function ($group) {
                return count($group);
            })
            ->sortKeys();
            $ageDatas = [];
            $ageDatasValue = [];
            foreach(  $data['dataset'] as $key =>  $agedata) {
                $ageDatas [] = $key;
                $ageDatasValue [] = $agedata;
            } 
            
        }
            $rangesData=[];
            $keyData = [];
            $count = 0;
            foreach ($ranges as $key => $range) {
                $rangesData[] = $key;
                if (in_array($key, $ageDatas)) {
                    $keyData[] = $ageDatasValue[$count++];
                }else{
                   $keyData[]= 0;
                }
            }

        $datasets_age_new = [
            'label' => '',
            'data' => $keyData,    
            'backgroundColor' => '#5542F6',     
        ];
        $graph2_data['labels'] = $rangesData;
        // dd( $graph2_data['labels'])
        $graph2_data['datasets'][] = $datasets_age_new;

        $data['graph2_data'] = $graph2_data;

            // dd( $data['graph2_data'] );

        $datasets_new = [
            'label' => 'Newly Added',
            'data' => $data_new_candidates,    
            'backgroundColor' => '#5542F6',     
        ];

        $datasets_all = [
            'label' => 'All Candidates',
            'data' => $data_candidates,    
            'backgroundColor' => '#C8C2FC',     
        ];

        $graph1_data['labels'] = $labels;
        // dd( $graph1_data['labels'])
        $graph1_data['datasets'][] = $datasets_new;
        $graph1_data['datasets'][] = $datasets_all;

        $data['graph1_data'] = $graph1_data;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;


       @$data['previous_range_new_candidates_age'][1];
       @$data['previous_range_new_candidates_age'][2];
       $data['previous_range_new_candidates_age_sum'] =(int)@$data['previous_range_new_candidates_age'][1]['sum']+(int)@$data['previous_range_new_candidates_age'][2]['sum']; 
       $data['incompleteProfileCount'] = $incompleteProfileCount;
       $data['previousIncompleteProfileCount'] = $previousIncompleteProfileCount;

       // dd($data['candidates_by_industries_graph']['data']);

        return view('backend.dashboard-tabs.candidates', compact('data'));
    }

    public function JobsAndGigsData(Request $request)
    {

        $previous_range = [];        
        

        $start_date = date('Y-01-01');
        $end_date = date('Y-12-t');

        if($request->has('start') && $request->start != '' && $request->has('end') && $request->end){
            $start_date = strtotime($request->start);
            $start_date = date('Y-m-01',$start_date);

            $end_date = strtotime($request->end);
            $end_date = date('Y-m-t',$end_date);

        }

        $ts1 = strtotime($start_date);
        $ts2 = strtotime($end_date);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

        $start    = (new \DateTime($start_date))->modify('first day of this month');
        $end      = (new \DateTime($end_date))->modify('last day of this month');

        $diif_months = $diff + 1;
        $previous_range['first_date'] = date('Y-m-d 00:00:01', strtotime($start_date. ' - '.$diif_months.' months'));
        $previous_range['last_date'] = date('Y-m-d 23:59:59', strtotime($end_date. ' - '.$diif_months.' months'));


        $data = [

            'jobs_count' => Job::count(),

            'active_jobs_count' => Job::where('status','published')->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),
            'previous_active_jobs_count' => Job::where('status','published')->whereBetween('created_at',[$previous_range['first_date']." 00:00:01",$previous_range['last_date']." 23:59:59"])->count(),
            
            'closed_jobs_count' => Job::where('status','closed')->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),
            'previous_closed_jobs_count' => Job::where('status','closed')->whereBetween('created_at',[$previous_range['first_date']." 00:00:01",$previous_range['last_date']." 23:59:59"])->count(),

            'active_gigs_count' => CompanyGig::where('status','published')->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),
            'previous_active_gigs_count' => CompanyGig::where('status','published')->whereBetween('created_at',[$previous_range['first_date']." 00:00:01",$previous_range['last_date']." 23:59:59"])->count(),
            
            'closed_gigs_count' => CompanyGig::where('status','closed')->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),
            'previous_closed_gigs_count' => CompanyGig::where('status','closed')->whereBetween('created_at',[$previous_range['first_date']." 00:00:01",$previous_range['last_date']." 23:59:59"])->count(),
            

            'jobs_by_industries' => Job::select(\DB::raw('count(company_jobs.id) as job_count'),'master_data.id','master_data.name','master_data.value')->leftJoin('master_data', 'master_data.id', '=', 'company_jobs.industry_domain_id')->whereBetween('company_jobs.created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->where('status','published')->orderBy('job_count','desc')->groupBy('company_jobs.industry_domain_id')->skip(0)->take(10)->get(),


            'featured_jobs_count' => FeaturedJob::withTrashed()->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),
            'previous_featured_jobs_counts' => FeaturedJob::withTrashed()->whereBetween('created_at',[$previous_range['first_date']." 00:00:01",$previous_range['last_date']." 23:59:59"])->count(),

            'featured_gigs_count' => FeaturedGig::withTrashed()->whereBetween('created_at',[$start_date." 00:00:01",$end_date." 23:59:59"])->count(),
            'previous_featured_gigs_counts' => FeaturedGig::withTrashed()->whereBetween('created_at',[$previous_range['first_date']." 00:00:01",$previous_range['last_date']." 23:59:59"])->count(),
        ];

        $jobs_by_industries_graph = [];
        $jobs_by_industries_graph['labels'] = [];
        $jobs_by_industries_graph['data'] = [];
        foreach ($data['jobs_by_industries'] as $value) {
            $jobs_by_industries_graph['labels'][] = $value->name;
            $jobs_by_industries_graph['data'][] = $value->job_count;
        }

        $data['jobs_by_industries_graph']['labels'] = $jobs_by_industries_graph['labels'];
        $data['jobs_by_industries_graph']['data'] = $jobs_by_industries_graph['data'];

        return view('backend.dashboard-tabs.jobs-gigs', compact('data'));
    }
}
