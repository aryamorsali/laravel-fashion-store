<?php

namespace App\Http\Controllers\Customer\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileTiketController extends Controller
{
    public function index()
    {
        $tickets = Auth::user()->tickets()->orderBy('created_at', 'desc')->paginate(10);
        return view('customer.profile.ticket.index', compact('tickets'));
    }
}
