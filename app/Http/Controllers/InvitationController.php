<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvitationController extends Controller
{
    /**
     * トークン招待を受け取る窓口
     * @param $token
     */
    public function store($token)
    {
        $invitation = Invitation::where('token', $token)->first();
        if ($invitation) {
            return view('invitation.create');
        }
    }
}
