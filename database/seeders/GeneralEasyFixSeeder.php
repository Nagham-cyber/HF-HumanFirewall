<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GeneralEasyFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'general')->where('level', 'easy')->delete();

        $scenarios = [
            [
                'title' => 'The Unlocked Workstation',
                'description' => 'Learn why you must lock your screen',
                'category' => 'physical_security',
                'scenario_text' => 'Sarah leaves her desk without locking her computer. A visitor sees confidential salary data on the screen.',
                'question' => 'What should Sarah have done before leaving her desk?',
                'options' => [
                    ['text' => 'Left the computer open', 'correct' => false],
                    ['text' => 'Locked the screen with Windows + L', 'correct' => true],
                    ['text' => 'Turned off the monitor', 'correct' => false],
                    ['text' => 'Asked a colleague to watch', 'correct' => false],
                ],
                'explanation' => 'Locking your screen prevents unauthorized access.',
            ],
            [
                'title' => 'The Sticky Note Password',
                'description' => 'Never write passwords on sticky notes',
                'category' => 'password',
                'scenario_text' => 'Ahmed writes his admin password on a sticky note. A client sees it and accesses internal systems.',
                'question' => 'What was the security mistake?',
                'options' => [
                    ['text' => 'He worked in IT', 'correct' => false],
                    ['text' => 'He wrote password on visible note', 'correct' => true],
                    ['text' => 'He had a visitor', 'correct' => false],
                    ['text' => 'He used too many systems', 'correct' => false],
                ],
                'explanation' => 'Never write passwords on visible notes.',
            ],
            [
                'title' => 'The Shared Password',
                'description' => 'Sharing credentials destroys accountability',
                'category' => 'access_control',
                'scenario_text' => 'A supervisor gives login to a new intern instead of requesting access from IT.',
                'question' => 'What should the supervisor have done?',
                'options' => [
                    ['text' => 'Shared his password', 'correct' => false],
                    ['text' => 'Requested separate access from IT', 'correct' => true],
                    ['text' => 'Given written credentials', 'correct' => false],
                    ['text' => 'Postponed the work', 'correct' => false],
                ],
                'explanation' => 'Each employee must have their own account.',
            ],
            [
                'title' => 'The Unlocked Phone',
                'description' => 'Phone must have lock screen',
                'category' => 'physical_security',
                'scenario_text' => 'Fatima leaves her phone without lock. Another employee reads confidential messages.',
                'question' => 'What should Fatima have enabled?',
                'options' => [
                    ['text' => 'No lock needed', 'correct' => false],
                    ['text' => 'Fingerprint or PIN lock', 'correct' => true],
                    ['text' => 'Lock during meetings only', 'correct' => false],
                    ['text' => 'Hide the phone', 'correct' => false],
                ],
                'explanation' => 'Work phones must always have a lock screen.',
            ],
            [
                'title' => 'The Public WiFi Trap',
                'description' => 'Free WiFi exposed confidential work',
                'category' => 'network_security',
                'scenario_text' => 'Omar connects to free airport WiFi. A hacker intercepts his data.',
                'question' => 'What should Omar have done?',
                'options' => [
                    ['text' => 'Used free WiFi', 'correct' => false],
                    ['text' => 'Used a VPN', 'correct' => true],
                    ['text' => 'Connected quickly', 'correct' => false],
                    ['text' => 'Used airport computer', 'correct' => false],
                ],
                'explanation' => 'Always use VPN on public networks.',
            ],
            [
                'title' => 'The Ignored Updates',
                'description' => 'Security updates matter',
                'category' => 'updates',
                'scenario_text' => 'Khalid postpones security updates. A known vulnerability is exploited.',
                'question' => 'What should Khalid have done?',
                'options' => [
                    ['text' => 'Delayed updates', 'correct' => false],
                    ['text' => 'Installed immediately', 'correct' => true],
                    ['text' => 'Ignored them', 'correct' => false],
                    ['text' => 'Disabled notifications', 'correct' => false],
                ],
                'explanation' => 'Updates patch vulnerabilities.',
            ],
            [
                'title' => 'The Work Email Shopping',
                'description' => 'Work email for personal use',
                'category' => 'email_security',
                'scenario_text' => 'Nora registers on shopping sites with work email. The sites are breached.',
                'question' => 'Which email should she use?',
                'options' => [
                    ['text' => 'Work email', 'correct' => false],
                    ['text' => 'Personal email', 'correct' => true],
                    ['text' => 'Work email for trusted sites', 'correct' => false],
                    ['text' => 'Any email', 'correct' => false],
                ],
                'explanation' => 'Keep work and personal email separate.',
            ],
            [
                'title' => 'The Suspicious Ads',
                'description' => 'Fake download ads',
                'category' => 'malware',
                'scenario_text' => 'Rami clicks a sponsored ad for PDF converter. Software installs ransomware.',
                'question' => 'What should Rami have done?',
                'options' => [
                    ['text' => 'Clicked first ad', 'correct' => false],
                    ['text' => 'Downloaded from official website', 'correct' => true],
                    ['text' => 'Clicked multiple ads', 'correct' => false],
                    ['text' => 'Used any site', 'correct' => false],
                ],
                'explanation' => 'Download from official websites only.',
            ],
            [
                'title' => 'The Browser Warning',
                'description' => 'Never bypass security alerts',
                'category' => 'web_security',
                'scenario_text' => 'Sara ignores browser warning and proceeds. Her credentials are stolen.',
                'question' => 'What should Sara have done?',
                'options' => [
                    ['text' => 'Proceeded anyway', 'correct' => false],
                    ['text' => 'Closed the page', 'correct' => true],
                    ['text' => 'Continued carefully', 'correct' => false],
                    ['text' => 'Added exception', 'correct' => false],
                ],
                'explanation' => 'Never bypass browser warnings.',
            ],
            [
                'title' => 'The Forgot to Log Out',
                'description' => 'Shared computers risk',
                'category' => 'access_control',
                'scenario_text' => 'Omar forgets to log out from shared computer. Next user accesses his email.',
                'question' => 'What should Omar have done?',
                'options' => [
                    ['text' => 'Closed browser', 'correct' => false],
                    ['text' => 'Logged out and cleared cookies', 'correct' => true],
                    ['text' => 'Walked away', 'correct' => false],
                    ['text' => 'Minimized window', 'correct' => false],
                ],
                'explanation' => 'Always log out on shared computers.',
            ],
            [
                'title' => 'The Notes App Passwords',
                'description' => 'Notes apps not safe for passwords',
                'category' => 'password',
                'scenario_text' => 'Nadia stores passwords in Notes app. Phone stolen, accounts compromised.',
                'question' => 'Where should she store passwords?',
                'options' => [
                    ['text' => 'Notes app', 'correct' => false],
                    ['text' => 'Password manager', 'correct' => true],
                    ['text' => 'Email', 'correct' => false],
                    ['text' => 'Notebook', 'correct' => false],
                ],
                'explanation' => 'Use a password manager.',
            ],
            [
                'title' => 'The Skipped 2FA',
                'description' => 'Two-factor authentication',
                'category' => 'account_security',
                'scenario_text' => 'Rami skips 2FA. Password leaks, account hacked.',
                'question' => 'What should Rami have done?',
                'options' => [
                    ['text' => 'Skipped 2FA', 'correct' => false],
                    ['text' => 'Enabled 2FA', 'correct' => true],
                    ['text' => 'Enabled later', 'correct' => false],
                    ['text' => 'No need', 'correct' => false],
                ],
                'explanation' => '2FA adds critical security layer.',
            ],
            [
                'title' => 'The Printed Documents',
                'description' => 'Forgotten documents in printer',
                'category' => 'physical_security',
                'scenario_text' => 'Fatima prints confidential HR document and forgets it in printer.',
                'question' => 'What should she have done?',
                'options' => [
                    ['text' => 'Picked up later', 'correct' => false],
                    ['text' => 'Collected immediately', 'correct' => true],
                    ['text' => 'Asked someone', 'correct' => false],
                    ['text' => 'Left for tomorrow', 'correct' => false],
                ],
                'explanation' => 'Collect confidential documents immediately.',
            ],
            [
                'title' => 'The Unknown LinkedIn',
                'description' => 'Fake profiles collect intelligence',
                'category' => 'social_engineering',
                'scenario_text' => 'Mohammed accepts stranger on LinkedIn who asks about company structure.',
                'question' => 'What should he have done?',
                'options' => [
                    ['text' => 'Accepted', 'correct' => false],
                    ['text' => 'Verified identity first', 'correct' => true],
                    ['text' => 'Shared info', 'correct' => false],
                    ['text' => 'Accepted if nice profile', 'correct' => false],
                ],
                'explanation' => 'Verify before connecting.',
            ],
            [
                'title' => 'The Wrong Email Address',
                'description' => 'Auto-complete causes data leaks',
                'category' => 'email_security',
                'scenario_text' => 'Nora sends salary data to wrong email due to auto-complete.',
                'question' => 'What should she have done?',
                'options' => [
                    ['text' => 'Clicked send', 'correct' => false],
                    ['text' => 'Double-checked recipient', 'correct' => true],
                    ['text' => 'Trusted auto-complete', 'correct' => false],
                    ['text' => 'Sent to all', 'correct' => false],
                ],
                'explanation' => 'Always verify recipient email.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'general';
            $scenario['level'] = 'easy';
            $scenario['stage'] = 1;
            $scenario['difficulty'] = 'beginner';
            $scenario['estimated_minutes'] = 3;
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
        
        echo "\nTotal: " . count($scenarios) . " general easy scenarios\n";
    }
}