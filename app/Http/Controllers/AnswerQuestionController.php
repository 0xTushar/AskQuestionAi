<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnswerQuestionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(QuestionRequest $request)
    {
        $promt = $request->question . "\n";

        $question = Question::whereQuery($request->question)
            ->whereHashedChoices(hex2bin(md5(json_encode($request->choices))))
            ->first();
        if ($question) {

            return $question->ans;
        }

        foreach ($request->choices as $i => $choice) {
            $promt .= $i . ". " . $choice . "\n";
        }



        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-3.5-turbo",
                "messages" => [

                    [
                        "role" => "system",
                        "content" => "I am sending you a question and a multiple choices . ans the correct one only if multiple ans multiple ans in json array only the ans key like a ,b,c,d. only ans with valid json array"
                    ],
                    [
                        "role" => "user",
                        "content" => $promt
                    ]
                ]
            ]);

        $result = $response->json();

        abort_if(isset($result["error"]), 503);

        $ans = json_decode($result["choices"][0]["message"]["content"], true);

        // check ans is exists or not
        abort_if(!isset($ans["ans"]), 503);


        Question::create([
            'query' => $request->question,
            'choices' => $request->choices,
            'ans' => $ans["ans"],
        ]);

        return $ans["ans"];
    }
}
