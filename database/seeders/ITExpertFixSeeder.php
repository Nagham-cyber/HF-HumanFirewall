<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ITExpertFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'it_professional')->where('level', 'expert')->delete();

        $scenarios = [
            [
                'title' => 'The APT Intrusion',
                'description' => 'Advanced Persistent Threat in network',
                'category' => 'incident_response',
                'scenario_text' => 'A sophisticated APT has been in the network for 6 months. They use custom malware, encrypted C2 channels, and live off the land. Standard AV finds nothing.',
                'question' => 'What is the correct response?',
                'options' => [
                    ['text' => 'Run antivirus', 'correct' => false],
                    ['text' => 'Engage incident response team and threat hunting', 'correct' => true],
                    ['text' => 'Reinstall Windows', 'correct' => false],
                    ['text' => 'Ignore it', 'correct' => false],
                ],
                'explanation' => 'APTs require specialized incident response and threat hunting.',
            ],
            [
                'title' => 'The Domain Dominance',
                'description' => 'Full AD compromise via DCSync',
                'category' => 'active_directory',
                'scenario_text' => 'An attacker uses DCSync to replicate AD credentials. They extract all password hashes and maintain persistence via Skeleton Keys.',
                'question' => 'What is the response?',
                'options' => [
                    ['text' => 'Change user passwords', 'correct' => false],
                    ['text' => 'Full AD rebuild and reset KRBTGT twice', 'correct' => true],
                    ['text' => 'Restart DCs', 'correct' => false],
                    ['text' => 'Add more DCs', 'correct' => false],
                ],
                'explanation' => 'Full AD compromise requires rebuild from known-good state.',
            ],
            [
                'title' => 'The Firmware Implant',
                'description' => 'UEFI rootkit persistence',
                'category' => 'malware',
                'scenario_text' => 'A nation-state implants a UEFI rootkit. Reinstalling the OS does not remove it. The implant persists across reboots.',
                'question' => 'How to detect and remove?',
                'options' => [
                    ['text' => 'Format C:', 'correct' => false],
                    ['text' => 'Firmware analysis and reflashing', 'correct' => true],
                    ['text' => 'Reinstall Windows', 'correct' => false],
                    ['text' => 'Antivirus scan', 'correct' => false],
                ],
                'explanation' => 'Firmware implants require specialized firmware analysis and reflashing.',
            ],
            [
                'title' => 'The Zero-Day in Firewall',
                'description' => 'Firewall bypass via zero-day',
                'category' => 'network_security',
                'scenario_text' => 'An attacker discovers a zero-day in the enterprise firewall. They bypass all rules and access the internal network.',
                'question' => 'What is the immediate response?',
                'options' => [
                    ['text' => 'Wait for patch', 'correct' => false],
                    ['text' => 'Isolate segments and apply virtual patching', 'correct' => true],
                    ['text' => 'Disable firewall', 'correct' => false],
                    ['text' => 'Ignore it', 'correct' => false],
                ],
                'explanation' => 'Use virtual patching and network segmentation to mitigate.',
            ],
            [
                'title' => 'The Cryptographic Failure',
                'description' => 'Weak crypto allows decryption',
                'category' => 'cryptography',
                'scenario_text' => 'An application uses ECB mode and a weak key. An attacker captures encrypted data and decrypts it using cryptanalysis.',
                'question' => 'What should be used?',
                'options' => [
                    ['text' => 'ECB', 'correct' => false],
                    ['text' => 'AES-GCM with proper key management', 'correct' => true],
                    ['text' => 'DES', 'correct' => false],
                    ['text' => 'MD5', 'correct' => false],
                ],
                'explanation' => 'Use AES-GCM with proper key management and random IVs.',
            ],
            [
                'title' => 'The Insider Threat',
                'description' => 'Malicious insider exfiltrating data',
                'category' => 'insider_threat',
                'scenario_text' => 'A disgruntled employee with legitimate access slowly exfiltrates data over months using encrypted channels.',
                'question' => 'How to detect?',
                'options' => [
                    ['text' => 'Trust all employees', 'correct' => false],
                    ['text' => 'UEBA and DLP solutions', 'correct' => true],
                    ['text' => 'Block all USB', 'correct' => false],
                    ['text' => 'Monitor only external', 'correct' => false],
                ],
                'explanation' => 'User Entity Behavior Analytics (UEBA) and DLP detect insider threats.',
            ],
            [
                'title' => 'The Cloud Account Takeover',
                'description' => 'Full cloud environment compromised',
                'category' => 'cloud_security',
                'scenario_text' => 'An attacker compromises a cloud admin account. They create backdoor IAM users and exfiltrate data from S3 and RDS.',
                'question' => 'What is the response?',
                'options' => [
                    ['text' => 'Change password', 'correct' => false],
                    ['text' => 'Revoke all sessions, rotate keys, audit IAM', 'correct' => true],
                    ['text' => 'Delete the account', 'correct' => false],
                    ['text' => 'Ignore it', 'correct' => false],
                ],
                'explanation' => 'Revoke all sessions, rotate all credentials, and audit IAM.',
            ],
            [
                'title' => 'The AI Model Poisoning',
                'description' => 'Malicious training data',
                'category' => 'ai_security',
                'scenario_text' => 'An attacker poisons the training data of a fraud detection model. The model now approves fraudulent transactions.',
                'question' => 'How to prevent?',
                'options' => [
                    ['text' => 'Trust all data', 'correct' => false],
                    ['text' => 'Data validation and model monitoring', 'correct' => true],
                    ['text' => 'Use less data', 'correct' => false],
                    ['text' => 'Disable AI', 'correct' => false],
                ],
                'explanation' => 'Validate training data and monitor model behavior.',
            ],
            [
                'title' => 'The Quantum Threat',
                'description' => 'Preparing for post-quantum crypto',
                'category' => 'cryptography',
                'scenario_text' => 'An organization realizes their RSA-2048 encryption will be broken by quantum computers. They need to migrate to post-quantum crypto.',
                'question' => 'What is the recommended approach?',
                'options' => [
                    ['text' => 'Keep RSA', 'correct' => false],
                    ['text' => 'Hybrid approach with post-quantum algorithms', 'correct' => true],
                    ['text' => 'Use 3DES', 'correct' => false],
                    ['text' => 'No encryption', 'correct' => false],
                ],
                'explanation' => 'Migrate to hybrid post-quantum cryptography.',
            ],
            [
                'title' => 'The Physical Breach',
                'description' => 'Tailgating into secure facility',
                'category' => 'physical_security',
                'scenario_text' => 'An attacker tailgates an employee into a secure facility. They plug a rogue device into the network and establish a backdoor.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Trust everyone', 'correct' => false],
                    ['text' => 'Mantraps, badge checks, and NAC', 'correct' => true],
                    ['text' => 'No security', 'correct' => false],
                    ['text' => 'Open doors', 'correct' => false],
                ],
                'explanation' => 'Use mantraps, badge verification, and Network Access Control.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'it_professional';
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
        
        echo "\nTotal: " . count($scenarios) . " IT expert scenarios\n";
    }
}