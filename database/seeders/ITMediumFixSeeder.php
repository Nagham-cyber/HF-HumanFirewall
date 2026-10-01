<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ITMediumFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'it_professional')->where('level', 'medium')->delete();

        $scenarios = [
            [
                'title' => 'The Misconfigured S3 Bucket',
                'description' => 'Cloud storage left publicly accessible',
                'category' => 'cloud_security',
                'scenario_text' => 'A developer uploads customer data to an S3 bucket but forgets to set proper permissions. The bucket is publicly accessible. A security researcher finds it and reports it. However, the data was already indexed by search engines.',
                'question' => 'What should have been done before uploading?',
                'options' => [
                    ['text' => 'Uploaded anyway', 'correct' => false],
                    ['text' => 'Set proper bucket policies and encryption', 'correct' => true],
                    ['text' => 'Used a different cloud provider', 'correct' => false],
                    ['text' => 'Compressed the data', 'correct' => false],
                ],
                'explanation' => 'Always configure S3 bucket policies, enable encryption, and block public access.',
            ],
            [
                'title' => 'The Unpatched Server',
                'description' => 'Critical server left unpatched for months',
                'category' => 'patch_management',
                'scenario_text' => 'An IT admin postpones server updates for 6 months because "it might break something." A known vulnerability in the web server is exploited by attackers, giving them full access.',
                'question' => 'What was the correct approach?',
                'options' => [
                    ['text' => 'Never update', 'correct' => false],
                    ['text' => 'Test updates in staging then deploy', 'correct' => true],
                    ['text' => 'Wait for a breach', 'correct' => false],
                    ['text' => 'Disable the server', 'correct' => false],
                ],
                'explanation' => 'Patch management is critical. Test in staging, then deploy to production.',
            ],
            [
                'title' => 'The Open Database Port',
                'description' => 'Database exposed to the internet',
                'category' => 'network_security',
                'scenario_text' => 'A developer opens port 3306 (MySQL) to the internet for "easy access." Within hours, attackers brute-force the database and steal all customer records.',
                'question' => 'What was the mistake?',
                'options' => [
                    ['text' => 'Using MySQL', 'correct' => false],
                    ['text' => 'Exposing database port to the internet', 'correct' => true],
                    ['text' => 'Not using NoSQL', 'correct' => false],
                    ['text' => 'Having a database', 'correct' => false],
                ],
                'explanation' => 'Databases should never be directly exposed to the internet. Use VPNs or bastion hosts.',
            ],
            [
                'title' => 'The Weak API Key',
                'description' => 'API key hardcoded and weak',
                'category' => 'appsec',
                'scenario_text' => 'A developer hardcodes an API key "12345" in the frontend code. Attackers find it in the JavaScript and use it to access the API without limits.',
                'question' => 'What should have been done?',
                'options' => [
                    ['text' => 'Used a longer key', 'correct' => false],
                    ['text' => 'Used environment variables and proper key management', 'correct' => true],
                    ['text' => 'Removed the API', 'correct' => false],
                    ['text' => 'Obfuscated the code', 'correct' => false],
                ],
                'explanation' => 'Never hardcode API keys. Use environment variables and rotate keys regularly.',
            ],
            [
                'title' => 'The Shared Admin Account',
                'description' => 'Multiple admins using same account',
                'category' => 'access_control',
                'scenario_text' => 'The IT team shares one admin account for convenience. When a breach occurs, no one can tell who did what. The attacker used this account and left no trace.',
                'question' => 'What is the correct practice?',
                'options' => [
                    ['text' => 'Share accounts', 'correct' => false],
                    ['text' => 'Individual accounts with proper auditing', 'correct' => true],
                    ['text' => 'Use one account with a strong password', 'correct' => false],
                    ['text' => 'No accounts needed', 'correct' => false],
                ],
                'explanation' => 'Never share admin accounts. Each admin must have their own account for auditing.',
            ],
            [
                'title' => 'The Unencrypted Backup',
                'description' => 'Backup tapes stolen during transport',
                'category' => 'data_security',
                'scenario_text' => 'Backup tapes are transported to an offsite location without encryption. The transport vehicle is stolen, and all data is exposed.',
                'question' => 'What should have been done?',
                'options' => [
                    ['text' => 'No backup', 'correct' => false],
                    ['text' => 'Encrypt backups before transport', 'correct' => true],
                    ['text' => 'Use only cloud', 'correct' => false],
                    ['text' => 'Hire security guards', 'correct' => false],
                ],
                'explanation' => 'Always encrypt backup data at rest and in transit.',
            ],
            [
                'title' => 'The Log Retention',
                'description' => 'Logs deleted too quickly',
                'category' => 'incident_response',
                'scenario_text' => 'Logs are set to delete after 7 days. An attack that started 10 days ago cannot be traced because the logs are gone.',
                'question' => 'What is the best practice?',
                'options' => [
                    ['text' => 'Delete logs immediately', 'correct' => false],
                    ['text' => 'Retain logs for at least 90 days', 'correct' => true],
                    ['text' => 'No logs needed', 'correct' => false],
                    ['text' => 'Delete after 1 day', 'correct' => false],
                ],
                'explanation' => 'Logs are critical for incident response. Retain them for at least 90 days.',
            ],
            [
                'title' => 'The Phishing Test Failure',
                'description' => 'Employees fail phishing simulation',
                'category' => 'security_awareness',
                'scenario_text' => 'A phishing simulation shows 60% of employees clicked the link. The IT team realizes awareness training is insufficient.',
                'question' => 'What is the best response?',
                'options' => [
                    ['text' => 'Fire employees', 'correct' => false],
                    ['text' => 'Implement regular training and simulations', 'correct' => true],
                    ['text' => 'Ignore it', 'correct' => false],
                    ['text' => 'Block all emails', 'correct' => false],
                ],
                'explanation' => 'Regular training and phishing simulations reduce click rates.',
            ],
            [
                'title' => 'The Third-Party Access',
                'description' => 'Vendor access not revoked after contract',
                'category' => 'access_control',
                'scenario_text' => 'A vendor had access to the internal network for a project that ended 6 months ago. Their credentials were never revoked. They are compromised and used by attackers.',
                'question' => 'What should have been done?',
                'options' => [
                    ['text' => 'Grant permanent access', 'correct' => false],
                    ['text' => 'Revoke access immediately after project ends', 'correct' => true],
                    ['text' => 'Share credentials', 'correct' => false],
                    ['text' => 'Ignore vendor access', 'correct' => false],
                ],
                'explanation' => 'Third-party access should be revoked when no longer needed.',
            ],
            [
                'title' => 'The Default SNMP Community',
                'description' => 'Network devices using default SNMP strings',
                'category' => 'network_security',
                'scenario_text' => 'Network devices use default SNMP community strings like "public" and "private." An attacker scans the network and gains detailed information about all devices.',
                'question' => 'What should be configured?',
                'options' => [
                    ['text' => 'Keep defaults', 'correct' => false],
                    ['text' => 'Change to strong community strings or SNMPv3', 'correct' => true],
                    ['text' => 'Disable SNMP monitoring', 'correct' => false],
                    ['text' => 'Use public only', 'correct' => false],
                ],
                'explanation' => 'Never use default SNMP strings. Use SNMPv3 with authentication.',
            ],
            [
                'title' => 'The Weak WPA2 Password',
                'description' => 'Corporate WiFi with weak password',
                'category' => 'network_security',
                'scenario_text' => 'The corporate WiFi password is "Company2024". An attacker parks outside, cracks the password, and accesses the internal network.',
                'question' => 'What is the best practice?',
                'options' => [
                    ['text' => 'Use a simple password', 'correct' => false],
                    ['text' => 'Use 802.1X with individual credentials', 'correct' => true],
                    ['text' => 'Share the password publicly', 'correct' => false],
                    ['text' => 'No WiFi', 'correct' => false],
                ],
                'explanation' => 'Use enterprise WiFi with 802.1X for individual authentication.',
            ],
            [
                'title' => 'The Unvalidated Redirect',
                'description' => 'Open redirect vulnerability',
                'category' => 'appsec',
                'scenario_text' => 'A login page has an unvalidated redirect parameter. An attacker sends a link to employees that redirects to a fake login page after authentication.',
                'question' => 'What is the vulnerability?',
                'options' => [
                    ['text' => 'XSS', 'correct' => false],
                    ['text' => 'Open Redirect', 'correct' => true],
                    ['text' => 'SQL Injection', 'correct' => false],
                    ['text' => 'CSRF', 'correct' => false],
                ],
                'explanation' => 'Always validate redirect URLs against a whitelist.',
            ],
            [
                'title' => 'The Missing HSTS',
                'description' => 'No HSTS header allows downgrade',
                'category' => 'appsec',
                'scenario_text' => 'A website does not set HSTS headers. An attacker performs a downgrade attack, forcing users to use HTTP and intercepting traffic.',
                'question' => 'What should be added?',
                'options' => [
                    ['text' => 'Nothing', 'correct' => false],
                    ['text' => 'Strict-Transport-Security header', 'correct' => true],
                    ['text' => 'More cookies', 'correct' => false],
                    ['text' => 'Less encryption', 'correct' => false],
                ],
                'explanation' => 'HSTS headers force browsers to use HTTPS.',
            ],
            [
                'title' => 'The Exposed .git Folder',
                'description' => 'Source code exposed via .git',
                'category' => 'appsec',
                'scenario_text' => 'A production server has a publicly accessible .git folder. An attacker downloads the entire source code, including database credentials.',
                'question' => 'What should have been done?',
                'options' => [
                    ['text' => 'Leave it', 'correct' => false],
                    ['text' => 'Block access to .git and sensitive files', 'correct' => true],
                    ['text' => 'Delete the website', 'correct' => false],
                    ['text' => 'Use FTP', 'correct' => false],
                ],
                'explanation' => 'Block access to .git, .env, and other sensitive files.',
            ],
            [
                'title' => 'The Insecure Deserialization',
                'description' => 'Unsafe deserialization leads to RCE',
                'category' => 'appsec',
                'scenario_text' => 'An application deserializes user input without validation. An attacker crafts a malicious payload that executes code on the server.',
                'question' => 'What is the vulnerability?',
                'options' => [
                    ['text' => 'XSS', 'correct' => false],
                    ['text' => 'Insecure Deserialization', 'correct' => true],
                    ['text' => 'SQL Injection', 'correct' => false],
                    ['text' => 'CSRF', 'correct' => false],
                ],
                'explanation' => 'Never deserialize untrusted data. Use safe formats like JSON.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'it_professional';
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
        
        echo "\nTotal: " . count($scenarios) . " IT medium scenarios\n";
    }
}