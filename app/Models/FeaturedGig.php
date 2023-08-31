<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeaturedGig extends Model
{
    use SoftDeletes;
	protected $fillable = ['gig_id', 'order'];

	public static function getFeaturedGigs()
	{
		return CompanyGig::with('type', 'locations', 'skills','company','engagementMode','user')
				->select('company_gigs.*','featured_gigs.order')
                ->join('featured_gigs', 'company_gigs.id', '=', 'featured_gigs.gig_id')
				->whereNull('featured_gigs.deleted_at')
                ->groupBy('company_gigs.id')
				->orderBy('featured_gigs.order', 'ASC')
				->get();
	}
}
