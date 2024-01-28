<?php

namespace App\Http\Controllers;

use App\Events\VideoChatEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VideoChatController extends Controller
{
    public string $mainKey = '1';

    public function getSession(Request $request)
    {
        $attrs = $request->validate([
            'random' => 'required'
        ]);

        return response()->json(json_decode(Redis::get("{$this->mainKey}:offer:{$attrs['random']}")));
    }

    public function create(Request $request)
    {
        $attrs = $request->validate([
            'candidates' => 'required|array',
            'random' => 'required'
        ]);

        event(new VideoChatEvent(['offerCandidates' => $attrs['candidates']]));

        Redis::set("{$this->mainKey}:offerCandidates:{$attrs['random']}", json_encode($attrs['candidates']));

        return response()->json(json_decode(Redis::get("{$this->mainKey}:offerCandidates:{$attrs['random']}")));
    }

    public function createAnswer(Request $request)
    {
        $attrs = $request->validate([
            'candidates' => 'required|array',
            'random' => 'required'
        ]);

        event(new VideoChatEvent(['answerCandidates' => $attrs['candidates']]));

        Redis::set("{$this->mainKey}:answerCandidates:{$attrs['random']}", json_encode($attrs['candidates']));

        return response()->json(json_decode(Redis::get("{$this->mainKey}:answerCandidates:{$attrs['random']}")));
    }

    public function offer(Request $request)
    {
        $attrs = $request->validate([
            'offer' => 'required',
            'random' => 'required'
        ]);

        event(new VideoChatEvent(['offer' => $attrs['offer']]));

        Redis::set("{$this->mainKey}:offer:{$attrs['random']}", json_encode($attrs['offer']));

        return response()->json(json_decode(Redis::get("{$this->mainKey}:offer:{$attrs['random']}")));
    }

    public function answer(Request $request)
    {
        $attrs = $request->validate([
            'answer' => 'required',
            'random' => 'required'
        ]);

        event(new VideoChatEvent(['answer' => $attrs['answer']]));

        Redis::set("{$this->mainKey}:answer:{$attrs['random']}", json_encode($attrs['answer']));

        return response()->json(json_decode(Redis::get("{$this->mainKey}:answer:{$attrs['random']}")));
    }
}
