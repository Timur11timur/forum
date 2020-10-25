<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;

class RegisterConformationController extends Controller
{
    public function index()
    {
        try {
            $user = User::where('confirmation_token', request('token'))
                ->firstOrFail()->confirm();
        } catch (\Exception $e) {
            return redirect(route('threads'))
                ->with('flash', 'Unknown token.');
        }

        return redirect(route('threads'))
            ->with('flash', 'Your account is now confirmed! You may post to the forum.');
    }
}
