<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ITHardFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'it_professional')->where('level', 'hard')->delete();

        $scenarios = [
            [
                'title' => 'The Kerberoasting Attack',
                'description' => 'Service account hashes extracted from AD',
                'category' => 'active_directory',
                'scenario_text' => 'An attacker with a regular domain account requests Kerberos service tickets for all SPNs. They extract the tickets and crack them offline to get service account passwords.',
                'question' => 'What is this attack called?',
                'options' => [
                    ['text' => 'Golden Ticket', 'correct' => false],
                    ['text' => 'Kerberoasting', 'correct' => true],
                    ['text' => 'Pass-the-Hash', 'correct' => false],
                    ['text' => 'DCSync', 'correct' => false],
                ],
                'explanation' => 'Kerberoasting targets service accounts with weak passwords. Use strong passwords and gMSA.',
            ],
            [
                'title' => 'The Golden Ticket',
                'description' => 'KRBTGT hash compromised',
                'category' => 'active_directory',
                'scenario_text' => 'An attacker gains domain admin access and extracts the KRBTGT hash. They create Golden Tickets that grant access to any resource for 10 years.',
                'question' => 'What is the remediation?',
                'options' => [
                    ['text' => 'Restart servers', 'correct' => false],
                    ['text' => 'Reset KRBTGT password twice', 'correct' => true],
                    ['text' => 'Disable AD', 'correct' => false],
                    ['text' => 'Change user passwords', 'correct' => false],
                ],
                'explanation' => 'Reset KRBTGT password twice to invalidate all Golden Tickets.',
            ],
            [
                'title' => 'The Pass-the-Hash',
                'description' => 'NTLM hash reused for lateral movement',
                'category' => 'active_directory',
                'scenario_text' => 'An attacker extracts NTLM hashes from a compromised workstation and uses them to authenticate to other systems without knowing the passwords.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Longer passwords', 'correct' => false],
                    ['text' => 'Credential Guard and disable NTLM', 'correct' => true],
                    ['text' => 'Use HTTP', 'correct' => false],
                    ['text' => 'Disable firewall', 'correct' => false],
                ],
                'explanation' => 'Enable Credential Guard and move to Kerberos-only authentication.',
            ],
            [
                'title' => 'The SQL Injection to RCE',
                'description' => 'SQL injection escalating to command execution',
                'category' => 'appsec',
                'scenario_text' => 'A web app has SQL injection in a search field. The attacker uses xp_cmdshell to execute commands on the database server.',
                'question' => 'What is the correct fix?',
                'options' => [
                    ['text' => 'Hide error messages', 'correct' => false],
                    ['text' => 'Parameterized queries and disable xp_cmdshell', 'correct' => true],
                    ['text' => 'Use NoSQL', 'correct' => false],
                    ['text' => 'Add a WAF only', 'correct' => false],
                ],
                'explanation' => 'Always use parameterized queries and disable dangerous procedures.',
            ],
            [
                'title' => 'The SSRF to Cloud Metadata',
                'description' => 'SSRF accessing AWS metadata',
                'category' => 'cloud_security',
                'scenario_text' => 'A web app fetches URLs provided by users. An attacker requests http://169.254.169.254/latest/meta-data/iam/security-credentials/ and gets AWS credentials.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Allow all URLs', 'correct' => false],
                    ['text' => 'Block metadata IP and use IMDSv2', 'correct' => true],
                    ['text' => 'Use HTTP', 'correct' => false],
                    ['text' => 'Disable the app', 'correct' => false],
                ],
                'explanation' => 'Block access to link-local addresses and enforce IMDSv2.',
            ],
            [
                'title' => 'The Container Escape',
                'description' => 'Breaking out of Docker container',
                'category' => 'cloud_security',
                'scenario_text' => 'A container runs with --privileged flag. An attacker compromises the app and escapes the container to access the host.',
                'question' => 'What should be done?',
                'options' => [
                    ['text' => 'Use privileged mode', 'correct' => false],
                    ['text' => 'Drop capabilities and avoid privileged mode', 'correct' => true],
                    ['text' => 'Run as root', 'correct' => false],
                    ['text' => 'Mount docker socket', 'correct' => false],
                ],
                'explanation' => 'Never run containers in privileged mode. Drop unnecessary capabilities.',
            ],
            [
                'title' => 'The Kubernetes Secrets Exposure',
                'description' => 'Secrets stored in plain text',
                'category' => 'cloud_security',
                'scenario_text' => 'Kubernetes secrets are stored as base64-encoded values. An attacker with read access decodes them and gets database passwords.',
                'question' => 'What is the best practice?',
                'options' => [
                    ['text' => 'Use base64', 'correct' => false],
                    ['text' => 'Enable encryption at rest and use external secrets manager', 'correct' => true],
                    ['text' => 'Store in Git', 'correct' => false],
                    ['text' => 'Use ConfigMaps', 'correct' => false],
                ],
                'explanation' => 'Enable encryption at rest and use external secret managers.',
            ],
            [
                'title' => 'The Log4Shell Exploit',
                'description' => 'JNDI injection in Log4j',
                'category' => 'appsec',
                'scenario_text' => 'An application uses a vulnerable Log4j version. An attacker sends a crafted string that triggers JNDI lookup and remote code execution.',
                'question' => 'What is the fix?',
                'options' => [
                    ['text' => 'Ignore it', 'correct' => false],
                    ['text' => 'Update Log4j and disable JNDI lookups', 'correct' => true],
                    ['text' => 'Block all HTTP', 'correct' => false],
                    ['text' => 'Use Java 8', 'correct' => false],
                ],
                'explanation' => 'Update Log4j immediately and disable JNDI lookups.',
            ],
            [
                'title' => 'The Ransomware via RDP',
                'description' => 'RDP brute force leading to ransomware',
                'category' => 'network_security',
                'scenario_text' => 'An RDP server is exposed to the internet with weak passwords. Attackers brute-force in and deploy ransomware across the network.',
                'question' => 'What is the best defense?',
                'options' => [
                    ['text' => 'Change password only', 'correct' => false],
                    ['text' => 'Disable RDP or use VPN with MFA', 'correct' => true],
                    ['text' => 'Use port 3389', 'correct' => false],
                    ['text' => 'Allow all IPs', 'correct' => false],
                ],
                'explanation' => 'Never expose RDP to the internet. Use VPN with MFA.',
            ],
            [
                'title' => 'The Supply Chain Compromise',
                'description' => 'Malicious update from vendor',
                'category' => 'supply_chain',
                'scenario_text' => 'A software vendor is compromised. A malicious update is pushed to all customers and installs a backdoor.',
                'question' => 'What should be done?',
                'options' => [
                    ['text' => 'Trust all updates', 'correct' => false],
                    ['text' => 'Verify signatures and have rollback plans', 'correct' => true],
                    ['text' => 'Never update', 'correct' => false],
                    ['text' => 'Use only open source', 'correct' => false],
                ],
                'explanation' => 'Verify update signatures and maintain rollback capability.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'it_professional';
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
        
        echo "\nTotal: " . count($scenarios) . " IT hard scenarios\n";
    }
}