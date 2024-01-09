<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send(Request $request)
    {
        $attrs = $request->validate([
            'from' => 'required',
            'subject' => 'required',
            'text' => 'required'
        ]);

        Mail::to('jarjaabua@gmail.com')->send(new SendMail($attrs['from'], $attrs['subject'], $attrs['text']));

        return response()->json('Email sent', 200);
    }
}
