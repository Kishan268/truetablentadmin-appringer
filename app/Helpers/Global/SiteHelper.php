<?php

namespace App\Helpers;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Exception;
use App\Models\SystemConfig;
use App\Models\MasterData;
use App\Models\Job;
use Illuminate\Support\Facades\Crypt;

class SiteHelper
{
    
    public static function removeNullFromArray($array)
    {
        $array_no_null = array_filter($array, function($v){ 
            return !is_null($v); 
        });

        return $array_no_null;
    }

    public static function checkValidRecord($data)
    {
        if ($data && $data != '' && $data != 'N/A' && $data != 'n/a' && $data != 'na' && $data != 'NA') {
        	return true;
        }else{
        	return false;
        }
    }

    public static function getObjectUrl($key)
    {
        if (str_contains($key, 'http') || str_contains($key, 'https')) { 
		    return $key;
		}else{
			$s3 = new S3Client([
	            'region'  => env('AWS_DEFAULT_REGION'),
	            'version' => 'latest',
	            'credentials' => [
	                'key'    => env('AWS_ACCESS_KEY_ID'),
	                'secret' => env('AWS_SECRET_ACCESS_KEY'),
	            ]
	        ]);

	        $cmd = $s3->getCommand('GetObject', [
			    'Bucket' => env('AWS_BUCKET'),
			    'Key' => $key
			]);

			$request = $s3->createPresignedRequest($cmd, '+480 minutes');
			$presignedUrl = (string)$request->getUri();
		   	return $presignedUrl;

		}
    }

    public static function getDataEntryObjectUrl($key)
    {
        if (str_contains($key, 'http') || str_contains($key, 'https')) { 
            return $key;
        }else{
            $s3 = new S3Client([
                'region'  => env('DATA_ENTRY_AWS_DEFAULT_REGION'),
                'version' => 'latest',
                'credentials' => [
                    'key'    => env('DATA_ENTRY_AWS_ACCESS_KEY_ID'),
                    'secret' => env('DATA_ENTRY_AWS_SECRET_ACCESS_KEY'),
                ]
            ]);

            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => env('DATA_ENTRY_AWS_BUCKET'),
                'Key' => $key
            ]);

            $request = $s3->createPresignedRequest($cmd, '+60 minutes');
            $presignedUrl = (string)$request->getUri();
            return $presignedUrl;

        }
    }

    public static function yearStartDate($year)
    {
        return date('Y-m-d 00:00:00', strtotime('01/01/'.$year));
    }

    public static function yearEndDate($year)
    {
        return date('Y-m-d 23:59:59', strtotime('12/31/'.$year));
    }

    public static function isRealDate($date) {
        try {
            if (false === strtotime($date)) {
                return false;
            }
            list($year, $month, $day) = explode('-', date("Y-m-d", strtotime($date)));
            return checkdate($month, $day, $year);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getRealDate($date) {
        try {
            if (false === strtotime($date)) {
                return null;
            }
            return date("d/m/Y", strtotime($date));
        } catch (Exception $e) {
            return null;
        }
    }

    public static function trimContactNumber($contact_number)
    {
        return str_replace( array( "+91", "(", ")", "-", "_", " ","+", ":" ), '', $contact_number);
    }

    public static function removeAllWhitespaces($string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    public static function getDomain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }

    public static function getDomainFromEmail($email)
    {
        if( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            $domain_arr = explode('@', $email);
            $domain = array_pop($domain_arr);
            
            return $domain;
        }
        return false;
    }

    

    public function getSystemConfig($key)
    {
        return $key;
    }

    public static function isNotEmpty($str)
    {
        return $str != null && $str != '';
    }

    public static function createSlug($string)
    {
        return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $string));
    }

    public static function getMasterDataName($id)
    {
        $data = MasterData::getMasterDataName($id);
        return $data ? $data->name : '';
    }

    public static function getCandidateExperienceText($experience) {

        $experience = (int)$experience;
        if ($experience <= 0) {
            return 'Fresher';
        } else {
            $text = number_format($experience / 12) . 'Y ';
            $text .= number_format($experience % 12) > 0 ? number_format($experience % 12) . 'M' : '';
            return $text;
        }
    }

    public static function getSkillPercentage($totalExperience, $skillExperience) {
        if ($totalExperience <= 0 || $skillExperience <= 0) {
            return 0;
        } else if ($skillExperience >= $totalExperience) {
            return 100;
        } else {
            return number_format((($skillExperience / $totalExperience) * 100),2);
        }
    }

    public static function encrypt($string)
    {
        return base64_encode($string);
    }

    public static function decrypt($string)
    {
        return base64_decode($string);
    }

    public static function getIndustryJobsCount($industry_id){
        return Job::where('industry_domain_id',$industry_id)->where('status','published')->count();
    }

    public static function calculateRatio($num_1, $num_2){
  
        for($n=$num_2; $n>1; $n--) {
            if(($num_1%$n) == 0 && ($num_2%$n) == 0) {
                $num_1=$num_1/$n;
                $num_2=$num_2/$n;
            }
        }
        return $num_1.":".$num_2;
    }

    public static function getSearchHashSeperator()
    {
        return __('strings.backend.general.search_seperator');
    }

    public static function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    public static function endsWith($string, $endString)
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }
}

