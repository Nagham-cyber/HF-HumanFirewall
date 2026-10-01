<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GeneralHardFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'general')->where('level', 'hard')->delete();

        $scenarios = [
            [
                'title' => 'The Ransomware Download',
                'description' => 'Malicious ad led to company-wide ransomware',
                'category' => 'malware',
                'scenario_text' => 'Khalid clicks sponsored ad for PDF converter. Downloads ransomware that encrypts 50 computers.',
                'question' => 'What was the initial mistake?',
                'options' => [
                    ['text' => 'Needing PDF converter', 'correct' => false],
                    ['text' => 'Clicking sponsored ad instead of official site', 'correct' => true],
                    ['text' => 'Ransomware too advanced', 'correct' => false],
                    ['text' => 'No backups', 'correct' => false],
                ],
                'explanation' => 'Sponsored ads often lead to fake sites.',
            ],
            [
                'title' => 'The Juice Jacking',
                'description' => 'Public USB charging station attack',
                'category' => 'physical_security',
                'scenario_text' => 'Nora charges phone at airport USB port. Malware transfers to her phone.',
                'question' => 'What attack did Nora experience?',
                'options' => [
                    ['text' => 'Phishing', 'correct' => false],
                    ['text' => 'Juice Jacking via public USB', 'correct' => true],
                    ['text' => 'Malware from website', 'correct' => false],
                    ['text' => 'Social engineering', 'correct' => false],
                ],
                'explanation' => 'Public USB ports can steal data. Use own charger.',
            ],
            [
                'title' => 'The AI Data Leak',
                'description' => 'Company secrets in public AI tool',
                'category' => 'data_security',
                'scenario_text' => 'Omar pastes proprietary code into public AI assistant. Code appears in competitor suggestions.',
                'question' => 'What was the critical error?',
                'options' => [
                    ['text' => 'Using AI tool', 'correct' => false],
                    ['text' => 'Pasting proprietary code into public AI', 'correct' => true],
                    ['text' => 'Being a developer', 'correct' => false],
                    ['text' => 'Improving efficiency', 'correct' => false],
                ],
                'explanation' => 'Never paste proprietary code into public AI.',
            ],
            [
                'title' => 'The Smart Speaker Backdoor',
                'description' => 'IoT device on company network',
                'category' => 'network_security',
                'scenario_text' => 'Fatima connects personal smart speaker to office WiFi. Attacker exploits vulnerability.',
                'question' => 'What was the root cause?',
                'options' => [
                    ['text' => 'Weak WiFi', 'correct' => false],
                    ['text' => 'Unmanaged IoT device on corporate network', 'correct' => true],
                    ['text' => 'Skilled attacker', 'correct' => false],
                    ['text' => 'Defective speaker', 'correct' => false],
                ],
                'explanation' => 'IoT devices create unmanaged backdoors.',
            ],
            [
                'title' => 'The Admin Rights',
                'description' => 'Excessive privileges amplified breach',
                'category' => 'access_control',
                'scenario_text' => 'Rami gets permanent admin rights for one-time install. Malware spreads system-wide.',
                'question' => 'What should have happened?',
                'options' => [
                    ['text' => 'Permanent admin rights', 'correct' => false],
                    ['text' => 'Temporary access revoked after install', 'correct' => true],
                    ['text' => 'Everyone admin', 'correct' => false],
                    ['text' => 'No software install', 'correct' => false],
                ],
                'explanation' => 'Admin rights should be temporary.',
            ],
            [
                'title' => 'The Office Selfie',
                'description' => 'Sensitive data in photo background',
                'category' => 'social_engineering',
                'scenario_text' => 'Nadia posts office selfie. Background shows passwords and network diagrams.',
                'question' => 'What was the issue?',
                'options' => [
                    ['text' => 'Posting on Instagram', 'correct' => false],
                    ['text' => 'Sensitive info visible in background', 'correct' => true],
                    ['text' => 'Taking photo at work', 'correct' => false],
                    ['text' => 'Being with colleagues', 'correct' => false],
                ],
                'explanation' => 'Background details can expose secrets.',
            ],
            [
                'title' => 'The Backup Failure',
                'description' => 'Backups connected to main network',
                'category' => 'data_security',
                'scenario_text' => 'Ransomware encrypts main network AND backups because they share credentials.',
                'question' => 'What should have been done?',
                'options' => [
                    ['text' => 'Same credentials', 'correct' => false],
                    ['text' => 'Isolated backups with unique credentials', 'correct' => true],
                    ['text' => 'More backups', 'correct' => false],
                    ['text' => 'Cloud backup', 'correct' => false],
                ],
                'explanation' => 'Backups must be isolated from main network.',
            ],
            [
                'title' => 'The Disabled Firewall',
                'description' => 'Security disabled for troubleshooting',
                'category' => 'malware',
                'scenario_text' => 'Mohammed disables firewall temporarily. Bot finds vulnerability and installs backdoor.',
                'question' => 'What should he have done?',
                'options' => [
                    ['text' => 'Disabled permanently', 'correct' => false],
                    ['text' => 'Asked IT for proper exception', 'correct' => true],
                    ['text' => 'Uninstalled firewall', 'correct' => false],
                    ['text' => 'Used different computer', 'correct' => false],
                ],
                'explanation' => 'Never disable security. Contact IT.',
            ],
            [
                'title' => 'The Macro Invoice',
                'description' => 'Malicious Excel from fake vendor',
                'category' => 'malware',
                'scenario_text' => 'Laila enables macros on invoice from unknown vendor. RAT installed.',
                'question' => 'What should she have done?',
                'options' => [
                    ['text' => 'Enabled macros', 'correct' => false],
                    ['text' => 'Verified vendor through another channel', 'correct' => true],
                    ['text' => 'Forwarded email', 'correct' => false],
                    ['text' => 'Deleted email', 'correct' => false],
                ],
                'explanation' => 'Verify before enabling macros.',
            ],
            [
                'title' => 'The Lost Access Card',
                'description' => 'Unreported lost badge',
                'category' => 'physical_security',
                'scenario_text' => 'Rami loses access card but does not report. Intruder uses it after hours.',
                'question' => 'What should Rami have done?',
                'options' => [
                    ['text' => 'Waited for it to turn up', 'correct' => false],
                    ['text' => 'Reported to security immediately', 'correct' => true],
                    ['text' => 'Borrowed colleague card', 'correct' => false],
                    ['text' => 'Bought new card', 'correct' => false],
                ],
                'explanation' => 'Lost cards must be reported for deactivation.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'general';
            $scenario['level'] = 'hard';
            $scenario['stage'] = 3;
            $scenario['difficulty'] = 'advanced';
            $scenario['estimated_minutes'] = 7;
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
        
        echo "\nTotal: " . count($scenarios) . " general hard scenarios\n";
    }
}