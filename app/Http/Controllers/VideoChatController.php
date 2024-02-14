<?php

namespace App\Http\Controllers;

use App\Events\VideoChatEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VideoChatController extends Controller
{
    public function getSession(Request $request)
    {
        $attrs = $request->validate([
            'random' => 'required'
        ]);

        return response()->json(json_decode(Redis::get("offer:{$attrs['random']}")));
    }

    public function createVideoSession(Request $request)
    {
        $attrs = $request->validate([
            'candidates' => 'required|array',
            'random' => 'required'
        ]);

        event(new VideoChatEvent(['offerCandidates' => $attrs['candidates']]));

        Redis::set("offerCandidates:{$attrs['random']}", json_encode($attrs['candidates']), 'EX', 86400);

        return response()->json(json_decode(Redis::get("offerCandidates:{$attrs['random']}")));
    }

    public function offer(Request $request)
    {
        $attrs = $request->validate([
            'offer' => 'required',
            'random' => 'required'
        ]);

        event(new VideoChatEvent(['offer' => $attrs['offer']]));

        Redis::set("offer:{$attrs['random']}", json_encode($attrs['offer']), 'EX', 86400);

        return response()->json(json_decode(Redis::get("offer:{$attrs['random']}")));
    }

    public function createAnswer(Request $request)
    {
        $attrs = $request->validate([
            'candidates' => 'required|array',
            'random' => 'required'
        ]);
        event(new VideoChatEvent(['answerCandidates' => $attrs['candidates']]));

        Redis::set("answerCandidates:{$attrs['random']}", json_encode($attrs['candidates']), 'EX', 86400);

        return response()->json(json_decode(Redis::get("answerCandidates:{$attrs['random']}")));
    }

    public function answer(Request $request)
    {
        $attrs = $request->validate([
            'answer' => 'required',
            'random' => 'required'
        ]);

        event(new VideoChatEvent(['answer' => $attrs['answer']]));

        Redis::set("answer:{$attrs['random']}", json_encode($attrs['answer']), 'EX', 86400);

        return response()->json(json_decode(Redis::get("answer:{$attrs['random']}")));
    }
}
