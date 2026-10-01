<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyChallengesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('challenges')->truncate();

        // ==================== Easy Templates (20) ====================
        $easyTemplates = [
            ['You receive an email with "Invoice.pdf.exe".', 'What should you do?', [['Open it', false], ['Delete and report', true], ['Rename and open', false], ['Forward', false]], 'Double extensions are malware.'],
            ['A caller claims to be from IT and asks for your password.', 'How to respond?', [['Give password', false], ['Hang up and verify', true], ['Give partial', false], ['Email them', false]], 'IT never asks for passwords.'],
            ['You find a USB in the parking lot.', 'What to do?', [['Plug it in', false], ['Give to security', true], ['Keep it', false], ['Share', false]], 'Unknown USBs may contain malware.'],
            ['You won a lottery and need to send bank details.', 'What is this?', [['Real win', false], ['Phishing scam', true], ['Bank promo', false], ['Tax refund', false]], 'Advance-fee fraud.'],
            ['A website asks for a password.', 'Strongest password?', [['password123', false], ['MyDog2024', false], ['P@ssw0rd!2024#Secure', true], ['12345678', false]], 'Mix cases, numbers, symbols.'],
            ['Text: package delayed, click link.', 'What to do?', [['Click link', false], ['Verify with shipping company', true], ['Reply address', false], ['Forward', false]], 'Smishing attack.'],
            ['Pop-up: computer infected, call number.', 'What to do?', [['Call number', false], ['Close browser', true], ['Download software', false], ['Restart', false]], 'Tech support scam.'],
            ['Colleague sends "funny video" link.', 'What to do?', [['Click it', false], ['Ask colleague first', true], ['Share it', false], ['Incognito', false]], 'Verify unexpected links.'],
            ['Email: urgent password reset link.', 'What to do?', [['Click link', false], ['Go to official website', true], ['Reply', false], ['Forward', false]], 'Never click reset links.'],
            ['Stranger asks for WiFi password.', 'What to do?', [['Give it', false], ['Politely decline', true], ['Share it', false], ['Write down', false]], 'Never share credentials.'],
            ['Email asks to verify account or it will be deleted.', 'What to do?', [['Click link', false], ['Login via official site', true], ['Reply', false], ['Delete email', false]], 'Scare tactic phishing.'],
            ['You receive a fake invoice from a "new vendor".', 'What to do?', [['Pay immediately', false], ['Verify vendor', true], ['Reply email', false], ['Forward', false]], 'Verify new vendors.'],
            ['Fake antivirus popup says you have 5 viruses.', 'What to do?', [['Buy it', false], ['Close browser', true], ['Install it', false], ['Click scan', false]], 'Fake AV scam.'],
            ['Facebook friend sends suspicious link.', 'What to do?', [['Click it', false], ['Verify with friend', true], ['Share it', false], ['Like it', false]], 'Account may be hacked.'],
            ['Email says you must update payment info.', 'What to do?', [['Click link', false], ['Login to official site', true], ['Reply', false], ['Ignore', false]], 'Classic phishing.'],
            ['A stranger calls about your "computer problem".', 'What to do?', [['Help them', false], ['Hang up', true], ['Give info', false], ['Follow instructions', false]], 'Cold call scam.'],
            ['You receive a QR code to "verify your account".', 'What to do?', [['Scan it', false], ['Ignore and verify via official site', true], ['Share it', false], ['Print it', false]], 'QR phishing (Quishing).'],
            ['Email with "Urgent: Invoice attached".', 'What to do?', [['Open attachment', false], ['Verify with sender', true], ['Forward', false], ['Delete', false]], 'Verify before opening.'],
            ['Pop-up: "You are the 1,000,000th visitor! Click to claim prize!"', 'What to do?', [['Click it', false], ['Close it', true], ['Claim prize', false], ['Share link', false]], 'Fake prize scam.'],
            ['Email: "Your account has been locked".', 'What to do?', [['Click link', false], ['Login to official site', true], ['Reply', false], ['Call number in email', false]], 'Never click lock notifications.'],
        ];

        // ==================== Medium Templates (20) ====================
        $mediumTemplates = [
            ['CEO email asks for urgent $50,000 wire transfer.', 'What to do first?', [['Process immediately', false], ['Verify via phone', true], ['Reply email', false], ['Forward', false]], 'BEC attack.'],
            ['A website asks to install "security update" via popup.', 'Is this safe?', [['Yes install', false], ['No, use official channels', true], ['Only if official', false], ['Install and scan', false]], 'Never install from popups.'],
            ['Browser warns certificate is invalid.', 'What to do?', [['Proceed', false], ['Leave immediately', true], ['Add exception', false], ['Different browser', false]], 'Invalid cert = MITM.'],
            ['Vendor invoice has updated bank details.', 'What to do?', [['Pay immediately', false], ['Call vendor to confirm', true], ['Reply email', false], ['Forward accounting', false]], 'Verify bank changes.'],
            ['Phone asks to install profile from unknown source.', 'What to do?', [['Install', false], ['Reject', true], ['Ignore', false], ['Ask help', false]], 'Unknown profiles = malware.'],
            ['"Security researcher" asks you to test a tool.', 'What to do?', [['Click link', false], ['Report and don\'t click', true], ['Share', false], ['Test in sandbox', false]], 'Social engineering.'],
            ['SMS with 2FA code you didn\'t request.', 'What to do?', [['Ignore', false], ['Change password', true], ['Share code', false], ['Wait', false]], 'Password compromised.'],
            ['Email: account deletion unless you verify.', 'What to do?', [['Click link', false], ['Login official site', true], ['Reply', false], ['Delete email', false]], 'Scare tactic.'],
            ['Friend sends attachment you didn\'t expect.', 'What to do?', [['Open', false], ['Verify first', true], ['Forward', false], ['Delete', false]], 'Account may be hacked.'],
            ['Email subject: "Urgent action required".', 'How to handle?', [['Act immediately', false], ['Verify authenticity', true], ['Forward boss', false], ['Delete', false]], 'Urgency = red flag.'],
            ['Website URL looks like "paypa1.com" (with number 1).', 'What is this?', [['Legit site', false], ['Typosquatting', true], ['Safe site', false], ['New site', false]], 'Typosquatting attack.'],
            ['Public WiFi asks for credit card to "verify".', 'What to do?', [['Enter card', false], ['Don\'t connect', true], ['Use VPN', false], ['Ignore', false]], 'Fake WiFi.'],
            ['Email offers a job with high pay and no interview.', 'What is this?', [['Real job', false], ['Scam', true], ['Recruiter', false], ['Remote job', false]], 'Job scam.'],
            ['You\'re asked to share a "quick code" you received.', 'What to do?', [['Share code', false], ['Refuse', true], ['Ask why', false], ['Ignore', false]], '2FA code theft.'],
            ['Email from "HR" asks to update bank details via link.', 'What to do?', [['Click link', false], ['Verify with HR directly', true], ['Reply', false], ['Forward', false]], 'HR phishing.'],
            ['Fake "Zoom update" installer downloads via link.', 'What to do?', [['Install', false], ['Download from official Zoom', true], ['Ignore', false], ['Test it', false]], 'Fake updates = malware.'],
            ['Crypto "investment" promises 50% daily returns.', 'What is this?', [['Real investment', false], ['Ponzi scheme', true], ['Safe', false], ['Legit', false]], 'Investment scam.'],
            ['Someone claims to be IRS and demands payment.', 'What to do?', [['Pay', false], ['Hang up', true], ['Share info', false], ['Follow instructions', false]], 'IRS never calls.'],
            ['Message: "Your Netflix account is on hold".', 'What to do?', [['Click link', false], ['Login to Netflix directly', true], ['Reply', false], ['Call number', false]], 'Netflix phishing.'],
            ['You\'re asked to bypass MFA "just this once".', 'What to do?', [['Bypass it', false], ['Refuse', true], ['Ask manager', false], ['Ignore', false]], 'Never bypass MFA.'],
        ];

        // ==================== Hard Templates (20) ====================
        $hardTemplates = [
            ['2FA code request you didn\'t initiate.', 'What does this mean?', [['Glitch', false], ['Someone has your password', true], ['Ignore', false], ['Share code', false]], 'Change password.'],
            ['PowerShell used for lateral movement.', 'What technique?', [['SQL Injection', false], ['Living Off the Land', true], ['Phishing', false], ['DDoS', false]], 'LOTL attack.'],
            ['Vendor update server compromised.', 'What attack?', [['Zero-day', false], ['Supply Chain', true], ['Insider', false], ['Brute force', false]], 'Supply chain attack.'],
            ['DNS queries used for data exfiltration.', 'What is this?', [['Phishing', false], ['DNS Tunneling', true], ['SQL Injection', false], ['MITM', false]], 'DNS tunneling.'],
            ['Attacker moves from workstation to HR, finance, DC.', 'What is this?', [['Initial access', false], ['Lateral Movement', true], ['Privilege escalation', false], ['Recon', false]], 'Lateral movement.'],
            ['Ransomware encrypts AND threatens to leak.', 'What is this?', [['Standard ransomware', false], ['Double Extortion', true], ['Wiper', false], ['Rootkit', false]], 'Double extortion.'],
            ['AI voice mimics CEO to authorize transfer.', 'What attack?', [['Vishing', false], ['Deepfake Voice', true], ['Smishing', false], ['Phishing', false]], 'Deepfake attack.'],
            ['SIM swapping intercepts 2FA codes.', 'Best defense?', [['Use SMS 2FA', false], ['Use authenticator apps', true], ['Change number', false], ['Email 2FA', false]], 'Hardware keys resist SIM swap.'],
            ['Malicious code in legitimate software update.', 'What attack?', [['Zero-day', false], ['Supply Chain', true], ['Phishing', false], ['Insider', false]], 'Trusted vendor compromise.'],
            ['Exploiting vulnerability with no patch.', 'Correct response?', [['Wait', false], ['Isolate and mitigate', true], ['Shut down network', false], ['Ignore', false]], 'Zero-days need isolation.'],
            ['Attacker uses Mimikatz to extract credentials.', 'What is this?', [['Phishing', false], ['Credential Dumping', true], ['SQL Injection', false], ['XSS', false]], 'Mimikatz tool.'],
            ['Attacker uses PsExec for lateral movement.', 'What is this?', [['Phishing', false], ['Lateral Movement Tool', true], ['Brute force', false], ['Rootkit', false]], 'PsExec abuse.'],
            ['Golden Ticket attack on Kerberos.', 'What does it do?', [['Steal data', false], ['Grant unlimited AD access', true], ['Encrypt files', false], ['Send spam', false]], 'KRBTGT compromise.'],
            ['Attacker uses DLL sideloading.', 'What is this?', [['Phishing', false], ['DLL Hijacking', true], ['SQL Injection', false], ['XSS', false]], 'DLL sideload.'],
            ['Attacker uses "living off the land" binaries.', 'Why hard to detect?', [['Encrypted', false], ['Legitimate tools', true], ['New malware', false], ['Zero-day', false]], 'LOLBins.'],
            ['Attacker chains multiple zero-days.', 'What is this?', [['APT', false], ['Exploit Chain', true], ['Phishing', false], ['DDoS', false]], 'Exploit chain.'],
            ['Attacker uses DNS-over-HTTPS for C2.', 'Why hard to detect?', [['Encrypted', false], ['Blends with legit traffic', true], ['New protocol', false], ['Slow', false]], 'DoH C2 evasion.'],
            ['Attacker disables EDR using BYOVD.', 'What is BYOVD?', [['Antivirus', false], ['Bring Your Own Vulnerable Driver', true], ['Firewall', false], ['Tool', false]], 'BYOVD attack.'],
            ['Attacker uses UEFI rootkit.', 'How to remove?', [['Antivirus', false], ['Firmware reflash', true], ['Reinstall OS', false], ['Format disk', false]], 'UEFI rootkit persistence.'],
            ['Attacker uses 0-click iMessage exploit.', 'What is this?', [['Phishing', false], ['Zero-Click Exploit', true], ['Smishing', false], ['Vishing', false]], 'Pegasus-style attack.'],
        ];

        // دالة توليد تحديات متعددة من كل قالب
        $generateVariations = function($templates, $difficulty, $points, $count = 17) {
            $rows = [];
            $totalTemplates = count($templates);
            for ($i = 0; $i < $count; $i++) {
                $t = $templates[$i % $totalTemplates];
                $rows[] = [
                    'difficulty' => $difficulty,
                    'points' => $points,
                    'scenario' => $t[0],
                    'question' => $t[1],
                    'options' => json_encode(array_map(function($o) {
                        return ['text' => $o[0], 'correct' => $o[1]];
                    }, $t[2])),
                    'explanation' => $t[3],
                   
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            return $rows;
        };

        // إنشاء 510 تحدي (170 easy + 170 medium + 170 hard)
        $allChallenges = array_merge(
            $generateVariations($easyTemplates, 'easy', 5, 170),
            $generateVariations($mediumTemplates, 'medium', 10, 170),
            $generateVariations($hardTemplates, 'hard', 15, 170)
        );

        // إدخال على دفعات
        foreach (array_chunk($allChallenges, 50) as $chunk) {
            DB::table('challenges')->insert($chunk);
        }

        echo "Challenges created!\n";
        echo "Easy: 170\n";
        echo "Medium: 170\n";
        echo "Hard: 170\n";
        echo "Total: " . count($allChallenges) . "\n";
    }
}