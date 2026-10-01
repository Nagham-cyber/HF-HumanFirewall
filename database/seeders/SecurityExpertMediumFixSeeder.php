<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SecurityExpertMediumFixSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scenarios')->where('user_type', 'security_expert')->where('level', 'medium')->delete();

        $scenarios = [
            [
                'title' => 'The OAuth Misconfiguration',
                'description' => 'OAuth flow allows token theft',
                'category' => 'appsec',
                'scenario_text' => 'An app uses implicit OAuth flow. An attacker steals the access token via referrer header and accesses user data.',
                'question' => 'What is the fix?',
                'options' => [
                    ['text' => 'Use implicit flow', 'correct' => false],
                    ['text' => 'Use authorization code flow with PKCE', 'correct' => true],
                    ['text' => 'Use HTTP', 'correct' => false],
                    ['text' => 'No OAuth', 'correct' => false],
                ],
                'explanation' => 'Use authorization code flow with PKCE instead of implicit flow.',
            ],
            [
                'title' => 'The JWT Algorithm Confusion',
                'description' => 'JWT accepts "none" algorithm',
                'category' => 'appsec',
                'scenario_text' => 'A JWT library accepts the "none" algorithm. An attacker modifies the token, sets alg to "none", and bypasses authentication.',
                'question' => 'What is the fix?',
                'options' => [
                    ['text' => 'Allow none', 'correct' => false],
                    ['text' => 'Enforce algorithm whitelist', 'correct' => true],
                    ['text' => 'Use sessions only', 'correct' => false],
                    ['text' => 'No JWT', 'correct' => false],
                ],
                'explanation' => 'Always enforce a strict algorithm whitelist.',
            ],
            [
                'title' => 'The CORS Misconfiguration',
                'description' => 'CORS allows any origin',
                'category' => 'appsec',
                'scenario_text' => 'An API sets Access-Control-Allow-Origin: * and allows credentials. A malicious site reads sensitive data from the API.',
                'question' => 'What is the fix?',
                'options' => [
                    ['text' => 'Allow all origins', 'correct' => false],
                    ['text' => 'Strict origin whitelist and no wildcard with credentials', 'correct' => true],
                    ['text' => 'Disable CORS', 'correct' => false],
                    ['text' => 'Use HTTP', 'correct' => false],
                ],
                'explanation' => 'Never use wildcard origins with credentials.',
            ],
            [
                'title' => 'The GraphQL Introspection',
                'description' => 'GraphQL schema exposed',
                'category' => 'appsec',
                'scenario_text' => 'A GraphQL API has introspection enabled in production. An attacker maps the entire schema and finds hidden admin mutations.',
                'question' => 'What should be done?',
                'options' => [
                    ['text' => 'Keep introspection', 'correct' => false],
                    ['text' => 'Disable introspection in production', 'correct' => true],
                    ['text' => 'Use REST', 'correct' => false],
                    ['text' => 'No GraphQL', 'correct' => false],
                ],
                'explanation' => 'Disable introspection in production environments.',
            ],
            [
                'title' => 'The Race Condition',
                'description' => 'TOCTOU vulnerability in payment',
                'category' => 'appsec',
                'scenario_text' => 'A payment system checks balance before deduction but not during. An attacker sends simultaneous requests and withdraws more than the balance.',
                'question' => 'What is the vulnerability?',
                'options' => [
                    ['text' => 'SQL Injection', 'correct' => false],
                    ['text' => 'Race Condition (TOCTOU)', 'correct' => true],
                    ['text' => 'XSS', 'correct' => false],
                    ['text' => 'CSRF', 'correct' => false],
                ],
                'explanation' => 'Use database transactions with proper locking.',
            ],
            [
                'title' => 'The HTTP Request Smuggling',
                'description' => 'CL.TE smuggling attack',
                'category' => 'network_security',
                'scenario_text' => 'A frontend and backend disagree on request boundaries. An attacker smuggles a request to poison the cache and hijack sessions.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Use HTTP/1.0', 'correct' => false],
                    ['text' => 'Consistent parsing and HTTP/2', 'correct' => true],
                    ['text' => 'Disable HTTPS', 'correct' => false],
                    ['text' => 'More proxies', 'correct' => false],
                ],
                'explanation' => 'Use consistent HTTP parsing and HTTP/2 end-to-end.',
            ],
            [
                'title' => 'The Cache Poisoning',
                'description' => 'Web cache poisoning via headers',
                'category' => 'network_security',
                'scenario_text' => 'An attacker sends a request with a malicious Host header. The cache stores the response and serves it to all users.',
                'question' => 'What is the fix?',
                'options' => [
                    ['text' => 'Trust all headers', 'correct' => false],
                    ['text' => 'Validate headers and use cache keys properly', 'correct' => true],
                    ['text' => 'Disable caching', 'correct' => false],
                    ['text' => 'Use HTTP', 'correct' => false],
                ],
                'explanation' => 'Validate headers and include them in cache keys.',
            ],
            [
                'title' => 'The Prototype Pollution',
                'description' => 'JavaScript prototype pollution',
                'category' => 'appsec',
                'scenario_text' => 'An app merges user input into objects without validation. An attacker pollutes Object.prototype and gains RCE.',
                'question' => 'What is the fix?',
                'options' => [
                    ['text' => 'Allow pollution', 'correct' => false],
                    ['text' => 'Validate keys and use Object.create(null)', 'correct' => true],
                    ['text' => 'Use jQuery', 'correct' => false],
                    ['text' => 'No JavaScript', 'correct' => false],
                ],
                'explanation' => 'Validate input keys and use safe object creation.',
            ],
            [
                'title' => 'The Subdomain Takeover',
                'description' => 'Dangling DNS record exploited',
                'category' => 'network_security',
                'scenario_text' => 'A company has a DNS record pointing to a decommissioned S3 bucket. An attacker claims the bucket and serves malicious content on the company subdomain.',
                'question' => 'What should be done?',
                'options' => [
                    ['text' => 'Keep DNS records', 'correct' => false],
                    ['text' => 'Remove dangling DNS records', 'correct' => true],
                    ['text' => 'Use more subdomains', 'correct' => false],
                    ['text' => 'No DNS', 'correct' => false],
                ],
                'explanation' => 'Regularly audit and remove dangling DNS records.',
            ],
            [
                'title' => 'The HTTP/2 Rapid Reset',
                'description' => 'HTTP/2 DDoS attack',
                'category' => 'network_security',
                'scenario_text' => 'An attacker exploits HTTP/2 rapid reset to launch a massive DDoS with minimal resources.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Use HTTP/1.0', 'correct' => false],
                    ['text' => 'Rate limiting and HTTP/2 stream limits', 'correct' => true],
                    ['text' => 'Disable HTTPS', 'correct' => false],
                    ['text' => 'More servers', 'correct' => false],
                ],
                'explanation' => 'Implement rate limiting and limit concurrent streams.',
            ],
            [
                'title' => 'The IDOR Vulnerability',
                'description' => 'Insecure Direct Object Reference',
                'category' => 'appsec',
                'scenario_text' => 'An API endpoint /api/user/123 returns user data without checking ownership. An attacker changes 123 to 124 and accesses other users data.',
                'question' => 'What is the fix?',
                'options' => [
                    ['text' => 'Hide IDs', 'correct' => false],
                    ['text' => 'Proper authorization checks', 'correct' => true],
                    ['text' => 'Use UUIDs only', 'correct' => false],
                    ['text' => 'No API', 'correct' => false],
                ],
                'explanation' => 'Always verify ownership and authorization on every request.',
            ],
            [
                'title' => 'The SSRF via PDF Generator',
                'description' => 'SSRF in HTML to PDF service',
                'category' => 'appsec',
                'scenario_text' => 'A PDF generator service fetches external URLs. An attacker uses it to scan the internal network and access metadata services.',
                'question' => 'What is the defense?',
                'options' => [
                    ['text' => 'Allow all URLs', 'correct' => false],
                    ['text' => 'URL whitelist and network segmentation', 'correct' => true],
                    ['text' => 'Use HTTP', 'correct' => false],
                    ['text' => 'No PDF', 'correct' => false],
                ],
                'explanation' => 'Use URL whitelists and isolate services in segmented networks.',
            ],
            [
                'title' => 'The Deserialization in Java',
                'description' => 'Java deserialization RCE',
                'category' => 'appsec',
                'scenario_text' => 'A Java app accepts serialized objects from users. An attacker uses ysoserial to craft a gadget chain and execute code.',
                'question' => 'What is the fix?',
                'options' => [
                    ['text' => 'Accept all objects', 'correct' => false],
                    ['text' => 'Avoid deserialization or use safe formats', 'correct' => true],
                    ['text' => 'Use XML', 'correct' => false],
                    ['text' => 'No Java', 'correct' => false],
                ],
                'explanation' => 'Avoid deserializing untrusted data. Use JSON with strict schemas.',
            ],
            [
                'title' => 'The Weak Random Number',
                'description' => 'Predictable random values',
                'category' => 'cryptography',
                'scenario_text' => 'A token generator uses Math.random(). An attacker predicts the next token and hijacks sessions.',
                'question' => 'What should be used?',
                'options' => [
                    ['text' => 'Math.random()', 'correct' => false],
                    ['text' => 'Cryptographically secure RNG', 'correct' => true],
                    ['text' => 'Date.now()', 'correct' => false],
                    ['text' => 'No tokens', 'correct' => false],
                ],
                'explanation' => 'Use cryptographically secure random number generators.',
            ],
            [
                'title' => 'The CSRF in API',
                'description' => 'CSRF via JSON API',
                'category' => 'appsec',
                'scenario_text' => 'An API accepts JSON with cookies for auth but no CSRF protection. A malicious site sends a request that changes the user email.',
                'question' => 'What is the fix?',
                'options' => [
                    ['text' => 'No protection needed', 'correct' => false],
                    ['text' => 'CSRF tokens or SameSite cookies', 'correct' => true],
                    ['text' => 'Use GET', 'correct' => false],
                    ['text' => 'Allow all origins', 'correct' => false],
                ],
                'explanation' => 'Use CSRF tokens or SameSite=Strict cookies.',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $scenario['user_type'] = 'security_expert';
            $scenario['level'] = 'medium';
            $scenario['stage'] = 2;
            $scenario['difficulty'] = 'intermediate';
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
        
        echo "\nTotal: " . count($scenarios) . " security expert medium scenarios\n";
    }
}