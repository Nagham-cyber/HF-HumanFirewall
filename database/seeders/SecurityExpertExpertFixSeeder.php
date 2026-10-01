<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SecurityExpertExpertFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'security_expert')->where('level', 'expert')->delete();

        $scenarios = [
            [
                'title' => 'The APT Attribution',
                'description' => 'Attributing a nation-state attack',
                'category' => 'threat_intelligence',
                'scenario_text' => 'A sophisticated attack with custom malware, zero-days, and specific targeting. Attribution requires deep analysis.',
                'question' => 'What is the correct approach?',
                'options' => [
                    ['text' => 'Guess based on tools', 'correct' => false],
                    ['text' => 'Multi-source intelligence and TTP analysis', 'correct' => true],
                    ['text' => 'Public IP lookup', 'correct' => false],
                    ['text' => 'Ask the attacker', 'correct' => false],
                ],
                'explanation' => 'Use multiple intelligence sources and analyze TTPs.',
            ],
            [
                'title' => 'The Anti-Forensics',
                'description' => 'Attacker covering tracks',
                'category' => 'incident_response',
                'scenario_text' => 'An attacker uses timestomping, log deletion, and encrypted containers to hide their activities.',
                'question' => 'How to investigate?',
                'options' => [
                    ['text' => 'Trust logs', 'correct' => false],
                    ['text' => 'Memory forensics and timeline analysis', 'correct' => true],
                    ['text' => 'Reinstall OS', 'correct' => false],
                    ['text' => 'Give up', 'correct' => false],
                ],
                'explanation' => 'Use memory forensics and thorough timeline analysis.',
            ],
            [
                'title' => 'The Hardware Backdoor',
                'description' => 'NSA-style hardware implant',
                'category' => 'hardware_security',
                'scenario_text' => 'A hardware implant on a network device allows remote access below the OS.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Software scan', 'correct' => false],
                    ['text' => 'Supply chain verification and TPM attestation', 'correct' => true],
                    ['text' => 'Antivirus', 'correct' => false],
                    ['text' => 'Reinstall OS', 'correct' => false],
                ],
                'explanation' => 'Verify supply chain and use hardware attestation.',
            ],
            [
                'title' => 'The Cryptographic Catastrophe',
                'description' => 'Total crypto failure',
                'category' => 'cryptography',
                'scenario_text' => 'A flaw in an HSM allows extraction of all private keys.',
                'question' => 'What is the response?',
                'options' => [
                    ['text' => 'Continue using', 'correct' => false],
                    ['text' => 'Revoke all certificates and reissue', 'correct' => true],
                    ['text' => 'Use software keys', 'correct' => false],
                    ['text' => 'No encryption', 'correct' => false],
                ],
                'explanation' => 'Revoke and reissue all certificates with new keys.',
            ],
            [
                'title' => 'The Nation-State Attack',
                'description' => 'Full-scale nation-state intrusion',
                'category' => 'incident_response',
                'scenario_text' => 'A nation-state has full access to the network with multiple persistence mechanisms.',
                'question' => 'What is the response?',
                'options' => [
                    ['text' => 'Simple cleanup', 'correct' => false],
                    ['text' => 'Complete rebuild and threat hunting', 'correct' => true],
                    ['text' => 'Change passwords', 'correct' => false],
                    ['text' => 'Ignore', 'correct' => false],
                ],
                'explanation' => 'Complete rebuild from known-good state with ongoing hunting.',
            ],
            [
                'title' => 'The Zero-Day Chain',
                'description' => 'Chain of multiple zero-days',
                'category' => 'appsec',
                'scenario_text' => 'An attacker uses a chain of zero-days to compromise a fully patched system.',
                'question' => 'What is the best defense?',
                'options' => [
                    ['text' => 'Patch everything', 'correct' => false],
                    ['text' => 'Defense in depth and isolation', 'correct' => true],
                    ['text' => 'Disable internet', 'correct' => false],
                    ['text' => 'Use one vendor', 'correct' => false],
                ],
                'explanation' => 'Implement defense in depth and network isolation.',
            ],
            [
                'title' => 'The Quantum Computing Threat',
                'description' => 'Preparing for quantum attacks',
                'category' => 'cryptography',
                'scenario_text' => 'An organization realizes their RSA encryption will be broken by quantum computers.',
                'question' => 'What is the best approach?',
                'options' => [
                    ['text' => 'Keep RSA', 'correct' => false],
                    ['text' => 'Hybrid post-quantum cryptography', 'correct' => true],
                    ['text' => 'Use 3DES', 'correct' => false],
                    ['text' => 'No encryption', 'correct' => false],
                ],
                'explanation' => 'Migrate to hybrid post-quantum cryptography.',
            ],
            [
                'title' => 'The AI Adversarial Attack',
                'description' => 'Evading AI-based security',
                'category' => 'ai_security',
                'scenario_text' => 'An attacker crafts inputs that evade AI-based malware detection.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Rely only on AI', 'correct' => false],
                    ['text' => 'Ensemble models and human analysis', 'correct' => true],
                    ['text' => 'No AI', 'correct' => false],
                    ['text' => 'Ignore it', 'correct' => false],
                ],
                'explanation' => 'Use ensemble models and human expert analysis.',
            ],
            [
                'title' => 'The Physical Nation-State Attack',
                'description' => 'Physical intrusion with insider help',
                'category' => 'physical_security',
                'scenario_text' => 'Nation-state actors with insider help physically access a secure facility.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Trust insiders', 'correct' => false],
                    ['text' => 'Multi-person controls and continuous monitoring', 'correct' => true],
                    ['text' => 'No security', 'correct' => false],
                    ['text' => 'Open doors', 'correct' => false],
                ],
                'explanation' => 'Use multi-person controls and continuous monitoring.',
            ],
            [
                'title' => 'The Ultimate Breach',
                'description' => 'Full-scale compromise of everything',
                'category' => 'incident_response',
                'scenario_text' => 'Every system is compromised. All credentials are stolen. All data is exfiltrated.',
                'question' => 'What is the response?',
                'options' => [
                    ['text' => 'Simple cleanup', 'correct' => false],
                    ['text' => 'Complete rebuild from scratch with new infrastructure', 'correct' => true],
                    ['text' => 'Change passwords', 'correct' => false],
                    ['text' => 'Give up', 'correct' => false],
                ],
                'explanation' => 'Complete rebuild with new infrastructure and credentials.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'security_expert';
            $scenario['level'] = 'expert';
            $scenario['stage'] = 4;
            $scenario['difficulty'] = 'advanced';
            $scenario['estimated_minutes'] = 15;
            $scenario['uuid'] = (string) Str::uuid();
            $scenario['is_active'] = true;
            $scenario['version'] = 1;
            $scenario['content'] = json_encode([
                'scenario' => $scenario['scenario_text'],
                'question' => $scenario['question'],
                'options' => $scenario['options'],
                'explanation' => $scenario['explanation'],
            ]);
            
            unset($scenario['scenario_text'], $scenario['options'], $scenario['question'], $scenario['explanation']);
            
            DB::table('scenarios')->insert($scenario);
            echo "Added: " . $scenario['title'] . "\n";
        }
        
        echo "\nTotal: " . count($scenarios) . " security expert expert scenarios\n";
    }
}