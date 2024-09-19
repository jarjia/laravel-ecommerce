<?php

namespace App\Http\Controllers;

use App\Events\StreamEvent;
use App\Events\VideoChatEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\JsonResponse;

class VideoChatController extends Controller
{
    private int $expire = 42300;

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
        // event(new StreamEvent(['stream_id' => $attrs['random']]));

        Redis::set("offerCandidates:{$attrs['random']}", json_encode($attrs['candidates']), 'EX', $this->expire);

        return response()->json(json_decode(Redis::get("offerCandidates:{$attrs['random']}")));
    }

    public function offer(Request $request)
    {
        $attrs = $request->validate([
            'offer' => 'required',
            'random' => 'required'
        ]);

        event(new VideoChatEvent(['offer' => $attrs['offer']]));

        Redis::set("offer:{$attrs['random']}", json_encode($attrs['offer']), 'EX', $this->expire);

        return response()->json(json_decode(Redis::get("offer:{$attrs['random']}")));
    }

    public function createAnswer(Request $request)
    {
        $attrs = $request->validate([
            'candidates' => 'required|array',
            'random' => 'required'
        ]);
        event(new VideoChatEvent(['answerCandidates' => $attrs['candidates']]));

        Redis::set("answerCandidates:{$attrs['random']}", json_encode($attrs['candidates']), 'EX', $this->expire);

        return response()->json(json_decode(Redis::get("answerCandidates:{$attrs['random']}")));
    }

    public function answer(Request $request)
    {
        $attrs = $request->validate([
            'answer' => 'required',
            'random' => 'required'
        ]);

        event(new VideoChatEvent(['answer' => $attrs['answer']]));

        Redis::set("answer:{$attrs['random']}", json_encode($attrs['answer']), 'EX', $this->expire);

        return response()->json(json_decode(Redis::get("answer:{$attrs['random']}")));
    }

    public function streams(): JsonResponse
    {
        $keys = [];
        $iterator = null;

        do {
            [$iterator, $matchingKeys] = Redis::scan($iterator ?? 0, 'MATCH', '*video_chat_offer:*');

            $keys = array_merge($keys, $matchingKeys);
        } while ($iterator > 0);

        return response()->json($keys);
    }
}
