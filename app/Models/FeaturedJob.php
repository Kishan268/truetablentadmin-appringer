<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Job;
use App\Models\SystemSettings;

class FeaturedJob extends Model
{
	use SoftDeletes;
	protected $fillable = ['job_id', 'order'];

	public static function getJobs()
	{
		return FeaturedJob::select('featured_jobs.id', 'featured_jobs.order', 'company_jobs.title', 'company_jobs.id AS job_id', 'company_jobs.created_at', 'companies.name AS company_name')
			->join('company_jobs', 'featured_jobs.job_id', '=', 'company_jobs.id')
			->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
			->get();
	}

	public static function getOrderableFeaturedJobs()
	{
		return FeaturedJob::select('featured_jobs.id', 'company_jobs.title', 'company_jobs.id AS job_id')
			->join('company_jobs', 'featured_jobs.job_id', '=', 'company_jobs.id')
			->orderBy('order', 'ASC')
			->get();
	}

	public static function getFeaturedJobs()
	{
		return FeaturedJob::select('featured_jobs.order','featured_jobs.job_id','company_jobs.id', 'company_jobs.title', 'company_jobs.description', 'company_jobs.min_salary', 'company_jobs.max_salary', 'company_jobs.minimum_experience_required', 'company_jobs.maximum_experience_required', \DB::raw("IF(DATE(company_jobs.updated_at) > DATE(NOW() - INTERVAL 90 DAY), status, 'expired') as job_status"), 'job_type_table.name AS job_types', 'company_jobs.created_at', 'company_jobs.renew_date', 'companies.name AS company_name', 'companies.location_id AS company_location', 'companies.logo AS company_logo', \DB::raw('group_concat(distinct job_location_table.name) as job_locations'))
			->join('company_jobs', 'featured_jobs.job_id', '=', 'company_jobs.id')
			->leftJoin('company_job_details AS job_locations', function ($join) {
				$join->on('job_locations.company_job_id', '=', 'company_jobs.id');
				$join->where('job_locations.type', '=', 'locations');
			})
			->leftJoin('master_data AS job_location_table', 'job_locations.data_id', '=', 'job_location_table.id')
			->leftJoin('master_data AS job_type_table', 'job_type_table.id', '=', 'company_jobs.job_type_id')
			->leftJoin('companies', 'company_jobs.company_id', '=', 'companies.id')
			->where('featured_jobs.order','!=',null)
			->groupBy('company_jobs.id')
			->orderBy('featured_jobs.order', 'ASC')
			->get();
	}
}
