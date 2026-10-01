<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SecurityExpertHardFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'security_expert')->where('level', 'hard')->delete();

        $scenarios = [
            [
                'title' => 'The SSRF Metadata Attack',
                'description' => 'Exploiting SSRF to access cloud metadata',
                'category' => 'cloud_security',
                'scenario_text' => 'An SSRF in an internal web app allows access to AWS metadata. Attacker retrieves IAM credentials.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Allow all URLs', 'correct' => false],
                    ['text' => 'Block 169.254.169.254 and use IMDSv2', 'correct' => true],
                    ['text' => 'Use HTTP', 'correct' => false],
                    ['text' => 'Disable the app', 'correct' => false],
                ],
                'explanation' => 'Block link-local addresses and enforce IMDSv2.',
            ],
            [
                'title' => 'The Container Signature Bypass',
                'description' => 'Malicious container signed with compromised key',
                'category' => 'cloud_security',
                'scenario_text' => 'Old signing key was not rotated. Attacker signs malicious container image and it passes policy.',
                'question' => 'What allowed this?',
                'options' => [
                    ['text' => 'Container too small', 'correct' => false],
                    ['text' => 'Old signing key not revoked', 'correct' => true],
                    ['text' => 'Weak verification', 'correct' => false],
                    ['text' => 'Registry misconfig', 'correct' => false],
                ],
                'explanation' => 'Rotate and revoke signing keys regularly.',
            ],
            [
                'title' => 'The AI Prompt Injection',
                'description' => 'Indirect prompt injection via malicious document',
                'category' => 'ai_security',
                'scenario_text' => 'An AI assistant reads documents. A malicious doc contains hidden instructions to exfiltrate HR files.',
                'question' => 'What type of attack?',
                'options' => [
                    ['text' => 'SQL Injection', 'correct' => false],
                    ['text' => 'Indirect Prompt Injection', 'correct' => true],
                    ['text' => 'XSS', 'correct' => false],
                    ['text' => 'Buffer overflow', 'correct' => false],
                ],
                'explanation' => 'Isolate AI systems from external actions and filter outputs.',
            ],
            [
                'title' => 'The Zero-Click iMessage Exploit',
                'description' => 'Pegasus-style zero-click attack',
                'category' => 'mobile_security',
                'scenario_text' => 'Executive receives invisible iMessage. Exploit triggers automatically and installs spyware.',
                'question' => 'What makes zero-click dangerous?',
                'options' => [
                    ['text' => 'Requires user action', 'correct' => false],
                    ['text' => 'No user action needed', 'correct' => true],
                    ['text' => 'Only old phones', 'correct' => false],
                    ['text' => 'Easy to detect', 'correct' => false],
                ],
                'explanation' => 'Zero-click exploits need no interaction. Use lockdown mode.',
            ],
            [
                'title' => 'The Supply Chain Compromise',
                'description' => 'SolarWinds-style supply chain attack',
                'category' => 'supply_chain',
                'scenario_text' => 'Vendor build system compromised. Malicious update signed with legitimate certificate distributed to all customers.',
                'question' => 'What is the best defense?',
                'options' => [
                    ['text' => 'Trust all updates', 'correct' => false],
                    ['text' => 'SBOM, code signing, and behavior monitoring', 'correct' => true],
                    ['text' => 'No updates', 'correct' => false],
                    ['text' => 'Open source only', 'correct' => false],
                ],
                'explanation' => 'Use SBOM, code signing, and runtime monitoring.',
            ],
            [
                'title' => 'The Firmware Rootkit',
                'description' => 'UEFI implant persisting across OS reinstall',
                'category' => 'malware',
                'scenario_text' => 'Nation-state implants UEFI rootkit. OS reinstall does not remove it.',
                'question' => 'How to remove?',
                'options' => [
                    ['text' => 'Format C:', 'correct' => false],
                    ['text' => 'Firmware analysis and reflash', 'correct' => true],
                    ['text' => 'Antivirus', 'correct' => false],
                    ['text' => 'Reinstall Windows', 'correct' => false],
                ],
                'explanation' => 'Firmware implants require specialized analysis and reflashing.',
            ],
            [
                'title' => 'The Cryptographic Backdoor',
                'description' => 'Dual_EC_DRBG style backdoor',
                'category' => 'cryptography',
                'scenario_text' => 'A random number generator has a backdoor. Attacker predicts all keys generated.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Use same RNG', 'correct' => false],
                    ['text' => 'Use audited open-source crypto libraries', 'correct' => true],
                    ['text' => 'No crypto', 'correct' => false],
                    ['text' => 'Use MD5', 'correct' => false],
                ],
                'explanation' => 'Use audited, open-source cryptographic libraries.',
            ],
            [
                'title' => 'The Hardware Implant',
                'description' => 'Malicious chip on motherboard',
                'category' => 'hardware_security',
                'scenario_text' => 'A hardware implant on a server motherboard allows remote access below the OS.',
                'question' => 'How to detect?',
                'options' => [
                    ['text' => 'Software scan', 'correct' => false],
                    ['text' => 'Hardware inspection and TPM attestation', 'correct' => true],
                    ['text' => 'Antivirus', 'correct' => false],
                    ['text' => 'Reinstall OS', 'correct' => false],
                ],
                'explanation' => 'Use TPM attestation and physical inspection.',
            ],
            [
                'title' => 'The Electromagnetic Attack',
                'description' => 'Side-channel via EM emissions',
                'category' => 'hardware_security',
                'scenario_text' => 'An attacker uses EM probes to extract cryptographic keys from a device.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Ignore it', 'correct' => false],
                    ['text' => 'Shielding and constant-time crypto', 'correct' => true],
                    ['text' => 'Use HTTP', 'correct' => false],
                    ['text' => 'No encryption', 'correct' => false],
                ],
                'explanation' => 'Use shielding and constant-time cryptographic implementations.',
            ],
            [
                'title' => 'The Quantum Attack',
                'description' => 'Harvest now, decrypt later',
                'category' => 'cryptography',
                'scenario_text' => 'An attacker captures encrypted data today to decrypt with future quantum computers.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'RSA-2048', 'correct' => false],
                    ['text' => 'Post-quantum cryptography', 'correct' => true],
                    ['text' => '3DES', 'correct' => false],
                    ['text' => 'No encryption', 'correct' => false],
                ],
                'explanation' => 'Migrate to post-quantum cryptography.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'security_expert';
            $scenario['level'] = 'hard';
            $scenario['stage'] = 3;
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
        
        echo "\nTotal: " . count($scenarios) . " security expert hard scenarios\n";
    }
}