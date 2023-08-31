<?php

namespace App\Http\Controllers\Backend\Auth\HomePageSettings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\BlockedDomain;

class BlockedDomainController extends Controller{
    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {
        $domains = BlockedDomain::all();
        return view('backend.auth.homepage_settings.blocked_domains.index', compact('domains'));
    }
}
