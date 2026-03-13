<?php

namespace Database\Seeders;

use App\Models\SendingSource;
use Illuminate\Database\Seeder;

class SendingSourceSeeder extends Seeder
{
    /**
     * Seed the sending_sources table with known email sending services and their IP ranges.
     */
    public function run(): void
    {
        $sources = [
            [
                'name' => 'Google Workspace',
                'organization' => 'Google Workspace',
                'ip_ranges' => [
                    '209.85.128.0/17',
                    '172.217.0.0/16',
                    '142.250.0.0/15',
                    '74.125.0.0/16',
                    '35.190.247.0/24',
                    '64.233.160.0/19',
                ],
                'description' => 'Google Workspace (Gmail) email sending infrastructure.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'Microsoft 365',
                'organization' => 'Microsoft 365',
                'ip_ranges' => [
                    '40.92.0.0/15',
                    '40.107.0.0/16',
                    '52.100.0.0/14',
                    '104.47.0.0/17',
                    '23.103.128.0/17',
                ],
                'description' => 'Microsoft 365 (Exchange Online) email sending infrastructure.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'Amazon SES',
                'organization' => 'Amazon SES',
                'ip_ranges' => [
                    '199.255.192.0/22',
                    '199.127.232.0/22',
                    '54.240.0.0/18',
                ],
                'description' => 'Amazon Simple Email Service (SES) sending infrastructure.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'SendGrid',
                'organization' => 'SendGrid',
                'ip_ranges' => [
                    '167.89.0.0/17',
                    '198.37.144.0/20',
                    '159.183.0.0/16',
                    '169.45.113.0/24',
                ],
                'description' => 'Twilio SendGrid email delivery platform.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'Mailchimp/Mandrill',
                'organization' => 'Mailchimp/Mandrill',
                'ip_ranges' => [
                    '198.2.128.0/18',
                    '205.201.128.0/20',
                    '198.2.185.0/24',
                ],
                'description' => 'Mailchimp and Mandrill transactional email sending infrastructure.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'Mailgun',
                'organization' => 'Mailgun',
                'ip_ranges' => [
                    '209.61.151.0/24',
                    '166.78.68.0/22',
                    '198.61.254.0/23',
                    '50.56.21.0/24',
                ],
                'description' => 'Mailgun email delivery service by Sinch.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'Postmark',
                'organization' => 'Postmark',
                'ip_ranges' => [
                    '50.31.156.0/24',
                    '50.31.164.0/24',
                ],
                'description' => 'Postmark transactional email service by ActiveCampaign.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'SparkPost',
                'organization' => 'SparkPost',
                'ip_ranges' => [
                    '147.253.208.0/20',
                    '192.174.80.0/20',
                    '18.232.0.0/14',
                ],
                'description' => 'SparkPost email delivery service by MessageBird.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'Zoho',
                'organization' => 'Zoho',
                'ip_ranges' => [
                    '136.143.184.0/21',
                ],
                'description' => 'Zoho Mail email sending infrastructure.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'Yahoo',
                'organization' => 'Yahoo',
                'ip_ranges' => [
                    '66.163.0.0/16',
                    '68.180.0.0/16',
                    '98.136.0.0/14',
                    '67.195.0.0/16',
                ],
                'description' => 'Yahoo Mail email sending infrastructure.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'Proofpoint',
                'organization' => 'Proofpoint',
                'ip_ranges' => [
                    '67.231.144.0/20',
                    '148.163.128.0/17',
                ],
                'description' => 'Proofpoint email security and delivery platform.',
                'icon' => null,
                'is_system' => true,
            ],
            [
                'name' => 'Mimecast',
                'organization' => 'Mimecast',
                'ip_ranges' => [
                    '207.211.30.0/24',
                    '205.139.110.0/23',
                    '91.220.42.0/24',
                ],
                'description' => 'Mimecast email security and delivery platform.',
                'icon' => null,
                'is_system' => true,
            ],
        ];

        foreach ($sources as $source) {
            SendingSource::updateOrCreate(
                ['name' => $source['name']],
                [
                    'organization' => $source['organization'],
                    'ip_ranges' => $source['ip_ranges'],
                    'description' => $source['description'],
                    'icon' => $source['icon'],
                    'is_system' => $source['is_system'],
                ]
            );
        }
    }
}
