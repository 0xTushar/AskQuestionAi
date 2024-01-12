<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
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

        return $response->json();
    }
}
