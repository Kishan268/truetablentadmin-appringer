<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemConfig;
use App\Models\SystemSettings;
use App\Models\MasterData;
use App\Models\FooterContent;
use Auth;
class FooterDynamicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function footerContent(Request $request)
    {
        if ($request->method() == 'GET') {
            $SystemSettings = SystemSettings::first();
            // dd($SystemSettings);
            // $job_by_skills = FooterContent::where('type', 'skills')->pluck('value')->toArray();
            $job_by_skills = FooterContent::where('type', 'skills')->select('value','text')->get();
            // $job_by_location = FooterContent::where('type', 'location')->pluck('value')->toArray();
            $job_by_locations = FooterContent::where('type', 'location')->select('value','text')->get();
            $job_by_industries = FooterContent::where('type', 'industry_domains')->pluck('value')->toArray();
            $navigations = FooterContent::where('type', 'nav')->get();
            $data_arr = ['industry_domains', 'skills', 'location'];
            $data = MasterData::getMasterData($data_arr, 'name');
           
            return view('backend.auth.footer-content.footer-save', compact('job_by_skills','job_by_industries','job_by_locations','navigations','SystemSettings', 'data'));
        }
        // $request->validate([
        //     'company_phone' => 'required',
        //     'company_website' => 'required',
        //     'company_email' => 'required',
        //     'company_address' => 'required',
        //     'nav_name.*' => 'required',
        //     'nav_link.*' => 'required',
        //     'required_skills.*' => 'required',
        //     'work_locations.*' => 'required',
        //     'industry_domain_id.*' => 'required'
        // ]);
        $data = [
            'company_phone'=>$request->company_phone,
            'company_website'=>$request->company_website,
            'company_email'=>$request->company_email,
            'company_address'=>$request->company_address,
            'fb'=>$request->fb,
            'instagram'=>$request->instagram,
            'twitter'=>$request->twitter,
            'linkedin'=>$request->linkedin,
            'contact_text'=>$request->contact_text
        ];
        // unset($data['_token']);
        SystemSettings::updateOrCreate(['id' => 1], $data);
        $authId = Auth::user()->id;
        $nav_name = isset($request->nav_name);
        if ( $nav_name) {
            $allNav = FooterContent::where(['type'=>'nav'])->pluck('type')->count();
            // if (count($request->nav_name) !== $allNav ) {
               FooterContent::where(['type'=>'nav'])->delete();
            // }
            foreach ($request->nav_name as $key => $navName) {
                $nav_name_value['text'] = trim($navName);
                $nav_name_value['value'] = $request->nav_link[$key];
                $nav_name_value['type'] = 'nav';
                $nav_name_value['updated_by'] = $authId;
                $nav_name_value['created_by'] = $authId;
                $jobBySkills = FooterContent::where('type', 'nav')->where('text', $navName)->select('text')->first();
                if (isset($jobBySkills->text) && $jobBySkills->text === $navName) {
                    FooterContent::where('text',$navName)->update(['value'=>$nav_name_value['value'],'text'=>$nav_name_value['text']]);
                }else{
                    FooterContent::create($nav_name_value);
                }
            }
        } else{
           FooterContent::where(['type'=>'nav'])->delete();
        }
        $required_skills = isset($request->required_skills);
        if ( $required_skills) {
            $allSkills = FooterContent::where(['type'=>'skills'])->pluck('type')->count();
            if (count($request->required_skills) !== $allSkills ) {
                FooterContent::where(['type'=>'skills'])->delete();
            }
            foreach ($request->required_skills as $key => $requiredSkill) {
                $skillName = MasterData::where('id', $requiredSkill)->where('type','skills')->select('name')->first();
                $requiredSkills['value'] = trim($requiredSkill);
                $requiredSkills['text'] = trim($skillName->name);
                $requiredSkills['updated_by'] = $authId;
                $requiredSkills['created_by'] = $authId;

                $requiredSkills['type'] = 'skills';
                $jobBySkills = FooterContent::where('type', 'skills')->where('value', $requiredSkill)->select('value')->first();
                if (isset($jobBySkills->value) && $jobBySkills->value === $requiredSkill) {
                    FooterContent::where('value',$requiredSkill)->update(['value'=>$requiredSkills['value'],'text'=>$requiredSkills['text']]);
                }else{
                    FooterContent::create($requiredSkills);
                }
            }
        }else{
            FooterContent::where(['type'=>'skills'])->delete();
        }
        $work_locations = isset($request->work_locations);
        if ( $work_locations) {
            $alllocation = FooterContent::where(['type'=>'location'])->pluck('type')->count();
            if (count($request->work_locations) !== $alllocation ) {
                FooterContent::where(['type'=>'location'])->delete();
            }
            foreach ($request->work_locations as $key => $workLocation) {
                $locationName = MasterData::where('id', $workLocation)->where('type','location')->select('name')->first();
                $workLocations['value'] = $workLocation;
                $workLocations['text'] = $locationName->name;
                $workLocations['type'] = 'location';
                $workLocations['updated_by'] = $authId;
                $workLocations['created_by'] = $authId;

                $jobBySkills = FooterContent::where('type', 'location')->where('value', $workLocation)->select('value')->first();
                if (isset($jobBySkills->value) && $jobBySkills->value === $workLocation) {
                    FooterContent::where('value',$workLocation)->update(['value'=>$workLocations['value'],'text'=>$workLocations['text']]);
                }else{
                    FooterContent::create($workLocations);
                }

            }
        }else{
           FooterContent::where(['type'=>'location'])->delete();
        }
        $industry_domain_id = isset($request->industry_domain_id);
        if ( $industry_domain_id) {
            $allindustry_domains = FooterContent::where(['type'=>'industry_domains'])->pluck('type')->count();
            if (count($request->industry_domain_id) !== $allindustry_domains ) {
                FooterContent::where(['type'=>'industry_domains'])->delete();
            }
            foreach ($request->industry_domain_id as $key => $industryDomains) {
                $domainName = MasterData::where('id', $industryDomains)->where('type','industry_domains')->select('name')->first();
                $industryDomain['value'] = $industryDomains;
                $industryDomain['text'] = $domainName->name;
                $industryDomain['type'] = 'industry_domains';
                $industryDomain['updated_by'] = $authId;
                $industryDomain['created_by'] = $authId;
                $jobBySkills = FooterContent::where('type', 'industry_domains')->where('value', $industryDomains)->select('value')->first();
                if (isset($jobBySkills->value) && $jobBySkills->value === $industryDomains) {
                    FooterContent::where('value',$industryDomains)->update(['value'=>$industryDomain['value'],'text'=>$industryDomain['text']]);
                }else{
                    FooterContent::create($industryDomain);
                }
                // FooterContent::updateOrCreate(['id' => $required_skills], $industryDomain);

            }
        }else{
            FooterContent::where(['type'=>'industry_domains'])->delete();
        }

        return redirect()->route('admin.footer_content')->withFlashSuccess(__('alerts.backend.footer_content_save'));
    }
}
