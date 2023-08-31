<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use App\Communicator\Communicator;
use App\Constants\ResponseMessages;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request) {
	    if (!$request->hasValidSignature()) {


	    	return Communicator::returnResponse(ResponseMessages::ERROR("Invalid/Expired url provided", "Invalid/Expired url provided."));
	    }

	    $user = User::withTrashed()->findOrFail($user_id);

	    if ($user->hasVerifiedEmail()) {
	        return Communicator::returnResponse(ResponseMessages::SUCCESS("Email already verified", null));
	    }

	    if (!$user->hasVerifiedEmail()) {
	        $user->markEmailAsVerified();
	    }
	    return Communicator::returnResponse(ResponseMessages::SUCCESS("Email verified successfully", null));
	}

	public function resend() {
	    if (auth()->user()->hasVerifiedEmail()) {
	        return response()->json(["msg" => "Email already verified."], 400);
	    }

	    auth()->user()->notify(new UserNeedsConfirmation(auth()->user()->confirmation_code, auth()->user()->first_name));

	    return response()->json(["msg" => "Email verification link sent on your email id"]);
	}
}
