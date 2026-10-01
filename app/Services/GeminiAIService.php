<?php

namespace App\Services;

class GeminiAIService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY');
        $this->model = env('OPENROUTER_MODEL', 'meta-llama/llama-3.2-3b-instruct:free');
    }

    /**
     * محادثة مع الذكاء الاصطناعي عبر OpenRouter
     */
    public function chat(string $message, array $history = []): string
    {
        if (empty($this->apiKey)) {
            return $this->getLocalResponse($message);
        }

        try {
            $url = 'https://openrouter.ai/api/v1/chat/completions';

            $messages = [
                [
                    'role' => 'system',
                    'content' => 'You are CyberGuard AI, a cybersecurity training assistant for SentinelPlay platform. Help users learn about security best practices, phishing detection, password security, and incident response. Respond in the same language the user writes in (Arabic or English). Be helpful and educational.'
                ]
            ];

            // إضافة تاريخ المحادثة (آخر 5)
            foreach (array_slice($history, -5) as $msg) {
                $messages[] = [
                    'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                    'content' => $msg['content']
                ];
            }

            // إضافة الرسالة الحالية
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            $data = [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => 500,
                'temperature' => 0.7,
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            if (isset($result['choices'][0]['message']['content'])) {
                return trim($result['choices'][0]['message']['content']);
            }

            if (isset($result['error']['message'])) {
                return 'AI Error: ' . $result['error']['message'];
            }

            return $this->getLocalResponse($message);

        } catch (\Exception $e) {
            return $this->getLocalResponse($message);
        }
    }

    /**
     * تحليل بريد إلكتروني
     */
    public function analyzeEmail(string $emailContent): array
    {
        if (empty($this->apiKey)) {
            return $this->getFallbackAnalysis($emailContent);
        }

        try {
            $prompt = "Analyze this email for security threats:\n\n" . $emailContent . 
                      "\n\nReturn JSON: risk_level (low/medium/high), indicators (array), recommendations (array)";

            $result = $this->sendRequest($prompt);

            if (isset($result['choices'][0]['message']['content'])) {
                $text = $result['choices'][0]['message']['content'];
                preg_match('/\{.*\}/s', $text, $matches);
                if (isset($matches[0])) {
                    return json_decode($matches[0], true) ?: $this->getFallbackAnalysis($emailContent);
                }
            }

            return $this->getFallbackAnalysis($emailContent);

        } catch (\Exception $e) {
            return $this->getFallbackAnalysis($emailContent);
        }
    }

    /**
     * توليد سيناريو
     */
    public function generateScenario(array $params): array
    {
        if (empty($this->apiKey)) {
            return $this->getFallbackScenario($params);
        }

        try {
            $prompt = "Create a cybersecurity training scenario about {$params['threat_type']}. Difficulty: {$params['difficulty']}. Include: title, description, 3 steps, 3 questions";

            $result = $this->sendRequest($prompt);

            if (isset($result['choices'][0]['message']['content'])) {
                return [
                    'title' => 'AI Generated Scenario',
                    'content' => $result['choices'][0]['message']['content'],
                ];
            }

            return $this->getFallbackScenario($params);

        } catch (\Exception $e) {
            return $this->getFallbackScenario($params);
        }
    }

    private function sendRequest($prompt)
    {
        $url = 'https://openrouter.ai/api/v1/chat/completions';
        
        $data = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 500,
            'temperature' => 0.7,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function getLocalResponse(string $message): string
    {
        $message = strtolower($message);
        
        if (str_contains($message, 'hello') || str_contains($message, 'hi') || str_contains($message, 'مرحبا')) {
            return 'Hello! I am your cybersecurity assistant. I can help you with phishing detection, password security, and more.';
        } elseif (str_contains($message, 'phish') || str_contains($message, 'تصيد')) {
            return 'Phishing is when attackers send fake emails to steal your information. Always verify the sender and never click suspicious links.';
        } elseif (str_contains($message, 'password') || str_contains($message, 'كلمة')) {
            return 'Use strong passwords with 12+ characters including uppercase, lowercase, numbers, and symbols.';
        } else {
            return 'I can help with phishing detection, password security, and incident response. What would you like to learn?';
        }
    }

    private function getFallbackAnalysis(string $emailContent): array
    {
        return [
            'risk_level' => 'unknown',
            'indicators' => ['Unable to analyze automatically'],
            'recommendations' => ['Review manually for suspicious links'],
        ];
    }

    private function getFallbackScenario(array $params): array
    {
        return [
            'title' => ucfirst($params['threat_type']) . ' Awareness',
            'content' => "Learn about {$params['threat_type']} security.",
        ];
    }
}