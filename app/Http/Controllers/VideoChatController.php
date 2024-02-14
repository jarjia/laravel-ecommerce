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

        $keyExists = Redis::exists("offer:{$attrs['random']}");

        return response()->json(['exists' => $keyExists, 'offer' => json_decode(Redis::get("offer:{$attrs['random']}"))]);
    }

    public function createVideoSession(Request $request)
    {
        $attrs = $request->validate([
            'candidates' => 'required|array',
            'streamID' => 'required'
        ]);

        event(new VideoChatEvent(['offerCandidates' => $attrs['candidates']]));

        Redis::set("offerCandidates:{$attrs['streamID']}", json_encode($attrs['candidates']), 'EX', 86400);

        return response()->json(201);
    }

    public function offer(Request $request)
    {
        $attrs = $request->validate([
            'offer' => 'required',
            'random' => 'required',
            'triggerAnswer' => 'nullable'
        ]);

        event(new VideoChatEvent(['offer' => $attrs['offer']]));

        if(isset($attrs['triggerAnswer'])) {
            $answers = json_decode(Redis::get("answer_ids:{$attrs['random']}"));

            foreach($answers as $answer) {
                event(new VideoChatEvent(['triggerAnswer' => json_decode(Redis::get("answerCandidates:{$attrs['random']}:{$answer}"))]));
            }
        }

        Redis::set("offer:{$attrs['random']}", json_encode($attrs['offer']), 'EX', 86400);

        return response()->json(200);
    }

    public function createAnswer(Request $request)
    {
        $attrs = $request->validate([
            'candidates' => 'required|array',
            'random' => 'required',
            'answer_id' => 'required'
        ]);

        event(new VideoChatEvent(['answerCandidates' => $attrs['candidates']]));

        $keyExists = Redis::exists("answer_ids:{$attrs['random']}");
        $answer_ids = [];

        if($keyExists) {
            $answer_ids = json_decode(Redis::get("answer_ids:{$attrs['random']}"));

            array_push($answer_ids, $attrs['answer_id']);
        } else {
            Redis::set("answer_ids:{$attrs['random']}", json_encode([$attrs['answer_id']]), 'EX', 86400);
        }

        Redis::set("answerCandidates:{$attrs['random']}:{$attrs['answer_id']}", json_encode($attrs['candidates']), 'EX', 86400);

        return response()->json(json_decode(Redis::get("answerCandidates:{$attrs['random']}:{$attrs['answer_id']}")));
    }

    public function answer(Request $request)
    {
        $attrs = $request->validate([
            'answer' => 'required',
            'random' => 'required',
            'answer_id' => 'required'
        ]);

        event(new VideoChatEvent(['answer' => $attrs['answer']]));

        Redis::set("answer:{$attrs['random']}:{$attrs['answer_id']}", json_encode($attrs['answer']), 'EX', 86400);

        return response()->json(json_decode(Redis::get("answer:{$attrs['random']}:{$attrs['answer_id']}")));
    }
}
