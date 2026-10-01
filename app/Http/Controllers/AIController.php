<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiAIService;

class AIController extends Controller
{
    protected $gemini;

    public function __construct()
    {
        $this->gemini = new GeminiAIService();
    }

    public function index()
    {
        return view('ai.assistant');
    }

    public function chat(Request $request)
    {
        $message = $request->input('message', '');
        $history = $request->input('history', []);

        if (empty($message)) {
            return response()->json([
                'success' => false,
                'response' => 'Please enter a message.',
            ]);
        }

        $response = $this->gemini->chat($message, $history);

        return response()->json([
            'success' => true,
            'response' => $response,
        ]);
    }

    public function analyzeEmail(Request $request)
    {
        $emailContent = $request->input('email_content', '');
        $analysis = $this->gemini->analyzeEmail($emailContent);
        
        return response()->json([
            'success' => true,
            'analysis' => $analysis,
        ]);
    }

    public function generateScenario(Request $request)
    {
        $params = [
            'threat_type' => $request->input('threat_type', 'phishing'),
            'difficulty' => $request->input('difficulty', 'beginner'),
            'duration' => $request->input('duration', 10),
        ];

        $scenario = $this->gemini->generateScenario($params);
        
        return response()->json([
            'success' => true,
            'scenario' => $scenario,
        ]);
    }
}