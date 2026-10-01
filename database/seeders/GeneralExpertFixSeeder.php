<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GeneralExpertFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'general')->where('level', 'expert')->delete();

        $scenarios = [
            [
                'title' => 'The Watering Hole Attack',
                'description' => 'Compromised website targeting specific users',
                'category' => 'phishing',
                'scenario_text' => 'Analysts visit a trusted industry website that has been compromised. Malware infects all visitors.',
                'question' => 'What type of attack is this?',
                'options' => [
                    ['text' => 'Phishing', 'correct' => false],
                    ['text' => 'Watering Hole Attack', 'correct' => true],
                    ['text' => 'DDoS', 'correct' => false],
                    ['text' => 'Man-in-the-Middle', 'correct' => false],
                ],
                'explanation' => 'Watering hole attacks compromise trusted websites.',
            ],
            [
                'title' => 'The Deepfake Voice',
                'description' => 'AI-generated voice impersonation',
                'category' => 'social_engineering',
                'scenario_text' => 'Manager receives call sounding exactly like CFO requesting $250,000 transfer.',
                'question' => 'What saved the company?',
                'options' => [
                    ['text' => 'Recognized fake voice', 'correct' => false],
                    ['text' => 'Verification through secondary channel', 'correct' => true],
                    ['text' => 'CFO answered phone', 'correct' => false],
                    ['text' => 'Transfer limit too low', 'correct' => false],
                ],
                'explanation' => 'Deepfakes can clone voices. Always verify.',
            ],
            [
                'title' => 'The Supply Chain Attack',
                'description' => 'Malware in trusted software update',
                'category' => 'malware',
                'scenario_text' => 'Vendor update server compromised. Malicious code in update affects thousands.',
                'question' => 'What type of attack?',
                'options' => [
                    ['text' => 'Zero-day', 'correct' => false],
                    ['text' => 'Supply Chain Attack', 'correct' => true],
                    ['text' => 'Insider threat', 'correct' => false],
                    ['text' => 'Brute force', 'correct' => false],
                ],
                'explanation' => 'Supply chain attacks compromise trusted software.',
            ],
            [
                'title' => 'The Zero-Day Exploit',
                'description' => 'Unknown vulnerability exploited',
                'category' => 'incident_response',
                'scenario_text' => 'Server compromised through vulnerability with no patch available.',
                'question' => 'What is the correct response?',
                'options' => [
                    ['text' => 'Wait for patch', 'correct' => false],
                    ['text' => 'Isolate and apply mitigations', 'correct' => true],
                    ['text' => 'Shut down network', 'correct' => false],
                    ['text' => 'Ignore it', 'correct' => false],
                ],
                'explanation' => 'Zero-days need immediate isolation.',
            ],
            [
                'title' => 'The DNS Tunneling',
                'description' => 'Data exfiltration through DNS queries',
                'category' => 'network_security',
                'scenario_text' => 'Workstation makes thousands of DNS queries to random subdomains. Data is being exfiltrated.',
                'question' => 'What technique is used?',
                'options' => [
                    ['text' => 'SQL Injection', 'correct' => false],
                    ['text' => 'DNS Tunneling', 'correct' => true],
                    ['text' => 'MITM', 'correct' => false],
                    ['text' => 'Phishing', 'correct' => false],
                ],
                'explanation' => 'DNS tunneling hides data in DNS queries.',
            ],
            [
                'title' => 'The Lateral Movement',
                'description' => 'Attacker spreading through network',
                'category' => 'network_security',
                'scenario_text' => 'Attacker compromises receptionist computer, then moves to HR, finance, and domain controller.',
                'question' => 'What is this called?',
                'options' => [
                    ['text' => 'Initial access', 'correct' => false],
                    ['text' => 'Lateral Movement', 'correct' => true],
                    ['text' => 'Privilege escalation', 'correct' => false],
                    ['text' => 'Reconnaissance', 'correct' => false],
                ],
                'explanation' => 'Lateral movement spreads from one system to others.',
            ],
            [
                'title' => 'The Double Extortion',
                'description' => 'Encryption plus data theft',
                'category' => 'malware',
                'scenario_text' => 'Hospital ransomware encrypts AND threatens to publish patient data.',
                'question' => 'What makes double extortion dangerous?',
                'options' => [
                    ['text' => 'Faster encryption', 'correct' => false],
                    ['text' => 'Encryption plus public exposure threat', 'correct' => true],
                    ['text' => 'Targets hospitals', 'correct' => false],
                    ['text' => 'Cannot decrypt', 'correct' => false],
                ],
                'explanation' => 'Double extortion makes refusal more damaging.',
            ],
            [
                'title' => 'The Living Off the Land',
                'description' => 'Attackers using legitimate tools',
                'category' => 'malware',
                'scenario_text' => 'Attackers use PowerShell and WMI only. No malware files found.',
                'question' => 'Why hard to detect?',
                'options' => [
                    ['text' => 'Strong encryption', 'correct' => false],
                    ['text' => 'Legitimate tools look normal', 'correct' => true],
                    ['text' => 'Targets old systems', 'correct' => false],
                    ['text' => 'Physical access needed', 'correct' => false],
                ],
                'explanation' => 'LOTL attacks use built-in tools.',
            ],
            [
                'title' => 'The SIM Swapping',
                'description' => 'Phone hijacked to bypass 2FA',
                'category' => 'account_security',
                'scenario_text' => 'Executive phone loses service. Attacker receives SMS 2FA codes and transfers $500,000.',
                'question' => 'What made attack possible?',
                'options' => [
                    ['text' => 'Weak 2FA', 'correct' => false],
                    ['text' => 'SIM swapping receives SMS codes', 'correct' => true],
                    ['text' => 'Shared password', 'correct' => false],
                    ['text' => 'No bank security', 'correct' => false],
                ],
                'explanation' => 'SIM swapping hijacks your number.',
            ],
            [
                'title' => 'The BEC Attack',
                'description' => 'Vendor email hacked for payment redirect',
                'category' => 'phishing',
                'scenario_text' => 'Vendor email compromised. Attackers send new bank details. $200,000 lost.',
                'question' => 'What should finance team have done?',
                'options' => [
                    ['text' => 'Trusted the email', 'correct' => false],
                    ['text' => 'Called vendor using known number', 'correct' => true],
                    ['text' => 'Replied to email', 'correct' => false],
                    ['text' => 'Paid quickly', 'correct' => false],
                ],
                'explanation' => 'Always verify payment changes.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'general';
            $scenario['level'] = 'expert';
            $scenario['stage'] = 4;
            $scenario['difficulty'] = 'advanced';
            $scenario['estimated_minutes'] = 10;
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
        
        echo "\nTotal: " . count($scenarios) . " general expert scenarios\n";
    }
}