<?php

namespace App\Services;

use App\Models\AiConversation;
use App\Models\Scenario;
use Illuminate\Support\Facades\Http;

class AIService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;
    
    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->baseUrl = 'https://api.openai.com/v1';
        $this->model = config('services.openai.model', 'gpt-4-turbo');
    }

    /**
     * توليد سيناريو جديد باستخدام الذكاء الاصطناعي
     */
    public function generateScenario(array $params): array
    {
        $prompt = $this->buildScenarioPrompt($params);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/chat/completions', [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a cybersecurity training expert...'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 2000,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            $content = $response->json('choices.0.message.content');
            return json_decode($content, true);
        }

        return $this->generateFallbackScenario($params);
    }

    /**
     * تحليل بريد إلكتروني مشبوه
     */
    public function analyzeEmail(string $emailContent): array
    {
        $prompt = "Analyze this email for potential security threats:\n\n" . $emailContent;
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/chat/completions', [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a cybersecurity email analyzer...'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'response_format' => ['type' => 'json_object'],
        ]);

        if ($response->successful()) {
            return json_decode($response->json('choices.0.message.content'), true);
        }

        return [
            'risk_level' => 'unknown',
            'indicators' => [],
            'recommendations' => ['Unable to analyze. Please check manually.'],
        ];
    }

    /**
     * إنشاء محادثة تدريبية
     */
    public function createTrainingConversation(int $userId, array $context): AiConversation
    {
        $conversation = AiConversation::create([
            'user_id' => $userId,
            'session_id' => uniqid('ai_', true),
            'messages' => [],
            'context' => $context,
        ]);

        $welcomeMessage = "Hello! I'm your cybersecurity training assistant. How can I help you today?";
        $conversation->addMessage('assistant', $welcomeMessage);

        return $conversation;
    }

    protected function buildScenarioPrompt(array $params): string
    {
        return "Create a cybersecurity training scenario with the following specifications:\n" .
               "- Threat type: {$params['threat_type']}\n" .
               "- Difficulty: {$params['difficulty']}\n" .
               "- Target audience: {$params['audience']}\n" .
               "- Duration: {$params['duration']} minutes\n" .
               "Include: story, interactive elements, questions, and feedback...";
    }

    protected function generateFallbackScenario(array $params): array
    {
        return [
            'title' => 'Basic Security Awareness',
            'description' => 'Learn the fundamentals of cybersecurity',
            'steps' => [
                [
                    'type' => 'intro',
                    'content' => 'Welcome to the security awareness training.',
                ],
            ],
            'questions' => [
                [
                    'type' => 'mcq',
                    'question' => 'What should you do if you receive a suspicious email?',
                    'options' => [
                        ['text' => 'Open it immediately', 'correct' => false],
                        ['text' => 'Report it to IT', 'correct' => true],
                        ['text' => 'Forward to colleagues', 'correct' => false],
                    ],
                ],
            ],
        ];
    }
}