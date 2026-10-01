<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GeneralMediumFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'general')->where('level', 'medium')->delete();

        $scenarios = [
            [
                'title' => 'The CEO Email',
                'description' => 'Spear phishing attack targeting finance',
                'category' => 'phishing',
                'scenario_text' => 'James receives urgent email from CEO asking for $50,000 transfer. Email looks real but address is slightly wrong.',
                'question' => 'What should James have done?',
                'options' => [
                    ['text' => 'Transferred immediately', 'correct' => false],
                    ['text' => 'Verified via phone call', 'correct' => true],
                    ['text' => 'Replied to email', 'correct' => false],
                    ['text' => 'Forwarded to colleague', 'correct' => false],
                ],
                'explanation' => 'Always verify unusual financial requests through another channel.',
            ],
            [
                'title' => 'The Same Password Everywhere',
                'description' => 'Reused passwords cause total compromise',
                'category' => 'password',
                'scenario_text' => 'Maria uses same password for work, personal, and banking. One breach compromises all accounts.',
                'question' => 'What was the mistake?',
                'options' => [
                    ['text' => 'Too many accounts', 'correct' => false],
                    ['text' => 'Same weak password everywhere', 'correct' => true],
                    ['text' => 'Used dog name', 'correct' => false],
                    ['text' => 'Registered on forums', 'correct' => false],
                ],
                'explanation' => 'Use unique passwords for each account.',
            ],
            [
                'title' => 'The Lost USB Drive',
                'description' => 'Unencrypted USB with client data',
                'category' => 'physical_security',
                'scenario_text' => 'Ahmed copies 500 client records to unencrypted USB. Loses it at coffee shop.',
                'question' => 'What should Ahmed have done?',
                'options' => [
                    ['text' => 'Used encrypted USB', 'correct' => true],
                    ['text' => 'Emailed files', 'correct' => false],
                    ['text' => 'Printed data', 'correct' => false],
                    ['text' => 'Stored on phone', 'correct' => false],
                ],
                'explanation' => 'Always encrypt sensitive data on portable devices.',
            ],
            [
                'title' => 'The Infected Phone',
                'description' => 'Personal device infected corporate network',
                'category' => 'network_security',
                'scenario_text' => 'Sara connects infected phone to company WiFi. Malware spreads to servers.',
                'question' => 'What was the root cause?',
                'options' => [
                    ['text' => 'Old server', 'correct' => false],
                    ['text' => 'Infected personal device on work network', 'correct' => true],
                    ['text' => 'Weak WiFi password', 'correct' => false],
                    ['text' => 'Advanced ransomware', 'correct' => false],
                ],
                'explanation' => 'Personal devices should not connect to corporate networks.',
            ],
            [
                'title' => 'The Shadow Cloud',
                'description' => 'Company documents on personal cloud',
                'category' => 'data_security',
                'scenario_text' => 'Omar uploads company file to personal Dropbox. Link becomes public.',
                'question' => 'What should Omar have done?',
                'options' => [
                    ['text' => 'Used personal Dropbox', 'correct' => false],
                    ['text' => 'Contacted IT for approved solution', 'correct' => true],
                    ['text' => 'Split the file', 'correct' => false],
                    ['text' => 'Used WhatsApp', 'correct' => false],
                ],
                'explanation' => 'Use approved solutions for company data.',
            ],
            [
                'title' => 'The IT Support Call',
                'description' => 'Vishing attack stealing credentials',
                'category' => 'social_engineering',
                'scenario_text' => 'Fatima receives call from fake IT support asking for password.',
                'question' => 'What should she have done?',
                'options' => [
                    ['text' => 'Provided password', 'correct' => false],
                    ['text' => 'Hung up and contacted IT officially', 'correct' => true],
                    ['text' => 'Asked for employee ID', 'correct' => false],
                    ['text' => 'Shared 2FA code', 'correct' => false],
                ],
                'explanation' => 'IT will never ask for your password.',
            ],
            [
                'title' => 'The Package Text',
                'description' => 'Smishing attack stealing card info',
                'category' => 'phishing',
                'scenario_text' => 'Mohammed gets text about package delivery with payment link.',
                'question' => 'What was the first sign of scam?',
                'options' => [
                    ['text' => 'Low amount', 'correct' => false],
                    ['text' => 'Unknown shortened URL', 'correct' => true],
                    ['text' => 'Professional website', 'correct' => false],
                    ['text' => 'Package message', 'correct' => false],
                ],
                'explanation' => 'Never click shortened links from unknown senders.',
            ],
            [
                'title' => 'The Social Quiz Trap',
                'description' => 'Quizzes collecting security answers',
                'category' => 'social_engineering',
                'scenario_text' => 'Nora answers quiz about first pet and street. Account gets hacked.',
                'question' => 'Why are quizzes dangerous?',
                'options' => [
                    ['text' => 'Waste time', 'correct' => false],
                    ['text' => 'Collect security question answers', 'correct' => true],
                    ['text' => 'Contain viruses', 'correct' => false],
                    ['text' => 'Steal Facebook', 'correct' => false],
                ],
                'explanation' => 'Never share personal details publicly.',
            ],
            [
                'title' => 'The Suspicious Computer',
                'description' => 'Hiding breach allowed spread',
                'category' => 'incident_response',
                'scenario_text' => 'Khalid notices strange computer behavior but hides it. Malware spreads.',
                'question' => 'What should Khalid have done?',
                'options' => [
                    ['text' => 'Ignored it', 'correct' => false],
                    ['text' => 'Reported to IT immediately', 'correct' => true],
                    ['text' => 'Fixed himself', 'correct' => false],
                    ['text' => 'Waited', 'correct' => false],
                ],
                'explanation' => 'Early reporting allows quick containment.',
            ],
            [
                'title' => 'The Stressed CEO Call',
                'description' => 'Emotional manipulation phone scam',
                'category' => 'social_engineering',
                'scenario_text' => 'Laila gets call from stressed CEO demanding urgent transfer.',
                'question' => 'What should Laila have done?',
                'options' => [
                    ['text' => 'Transferred to avoid upsetting CEO', 'correct' => false],
                    ['text' => 'Followed standard approval process', 'correct' => true],
                    ['text' => 'Transferred and documented later', 'correct' => false],
                    ['text' => 'Asked for email', 'correct' => false],
                ],
                'explanation' => 'Always follow verification procedures.',
            ],
            [
                'title' => 'The Antivirus Warning',
                'description' => 'Disabling security to open suspicious file',
                'category' => 'malware',
                'scenario_text' => 'Rami disables antivirus to open invoice file. Ransomware encrypts everything.',
                'question' => 'What should Rami have done?',
                'options' => [
                    ['text' => 'Disabled antivirus', 'correct' => false],
                    ['text' => 'Trusted warning and contacted IT', 'correct' => true],
                    ['text' => 'Clicked Allow Always', 'correct' => false],
                    ['text' => 'Asked colleague', 'correct' => false],
                ],
                'explanation' => 'Never bypass security warnings.',
            ],
            [
                'title' => 'The Free Extension',
                'description' => 'Malicious browser extension',
                'category' => 'malware',
                'scenario_text' => 'Nadia installs free grammar checker. Extension steals session cookies.',
                'question' => 'What should she have checked?',
                'options' => [
                    ['text' => 'Installed immediately', 'correct' => false],
                    ['text' => 'Verified permissions and IT approval', 'correct' => true],
                    ['text' => 'Checked reviews only', 'correct' => false],
                    ['text' => 'Asked colleagues', 'correct' => false],
                ],
                'explanation' => 'Only use IT-approved extensions.',
            ],
            [
                'title' => 'The OAuth Link',
                'description' => 'Work account linked to third-party',
                'category' => 'access_control',
                'scenario_text' => 'Omar uses work Google account to sign into third-party site. Site is malicious.',
                'question' => 'What was the mistake?',
                'options' => [
                    ['text' => 'Using Google', 'correct' => false],
                    ['text' => 'Linking work account to untrusted site', 'correct' => true],
                    ['text' => 'Not using 2FA', 'correct' => false],
                    ['text' => 'Using productivity site', 'correct' => false],
                ],
                'explanation' => 'Avoid linking work accounts to unknown sites.',
            ],
            [
                'title' => 'The Personal Drive',
                'description' => 'Company data on personal cloud',
                'category' => 'data_security',
                'scenario_text' => 'Fatima uploads customer database to personal Google Drive. Account gets hacked.',
                'question' => 'What should she have done?',
                'options' => [
                    ['text' => 'Uploaded to personal drive', 'correct' => false],
                    ['text' => 'Used approved remote access', 'correct' => true],
                    ['text' => 'Emailed herself', 'correct' => false],
                    ['text' => 'Used USB', 'correct' => false],
                ],
                'explanation' => 'Use approved remote access solutions.',
            ],
            [
                'title' => 'The Cafe Conversation',
                'description' => 'Sensitive info overheard in public',
                'category' => 'social_engineering',
                'scenario_text' => 'Rami discusses merger details loudly in cafe. Competitor overhears.',
                'question' => 'What should Rami have done?',
                'options' => [
                    ['text' => 'Discussed normally', 'correct' => false],
                    ['text' => 'Moved to private area', 'correct' => true],
                    ['text' => 'Whispered', 'correct' => false],
                    ['text' => 'Asked caller to speak louder', 'correct' => false],
                ],
                'explanation' => 'Never discuss sensitive info in public.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'general';
            $scenario['level'] = 'medium';
            $scenario['stage'] = 2;
            $scenario['difficulty'] = 'intermediate';
            $scenario['estimated_minutes'] = 5;
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
        
        echo "\nTotal: " . count($scenarios) . " general medium scenarios\n";
    }
}