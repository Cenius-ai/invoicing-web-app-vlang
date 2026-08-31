<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceLineItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ClientSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'Meridian Analytics', 'email' => 'billing@meridiananalytics.com', 'address' => '1420 Data Drive, Suite 300, San Francisco, CA 94105'],
            ['name' => 'Northrop Design Co.', 'email' => 'accounts@northropdesign.co', 'address' => '88 Pacific Ave, Portland, OR 97201'],
            ['name' => 'Crestview Property Group', 'email' => 'ap@crestviewpg.com', 'address' => '2100 Wilshire Blvd, Suite 800, Los Angeles, CA 90010'],
            ['name' => 'Arbor Creek Landscaping', 'email' => 'info@arborcreek.co', 'address' => '7300 Elm Street, Austin, TX 78701'],
            ['name' => 'Solara Energy Partners', 'email' => 'finance@solaraenergy.io', 'address' => '5601 Innovation Way, Denver, CO 80202'],
            ['name' => 'Bentley & Fisk LLP', 'email' => 'office@bentleyfisk.com', 'address' => '400 Madison Ave, 14th Floor, New York, NY 10017'],
            ['name' => 'Harborview Catering', 'email' => 'orders@harborviewcatering.com', 'address' => '221B Waterfront Road, Seattle, WA 98101'],
            ['name' => 'Talon Media Group', 'email' => 'ar@talonmedia.com', 'address' => '330 N Wabash Ave, Suite 2200, Chicago, IL 60611'],
        ];

        foreach ($clients as $client) {
            Client::firstOrCreate(['email' => $client['email']], $client);
        }
    }
}

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();

        $invoices = [
            [
                'client_email' => 'billing@meridiananalytics.com',
                'invoice_number' => 'INV-0001',
                'status' => 'paid',
                'issue_date' => '2025-10-01',
                'due_date' => '2025-10-31',
                'line_items' => [
                    ['description' => 'Q3 Data Pipeline Migration — Phase I', 'quantity' => 1, 'unit_price_cents' => 2500000],
                    ['description' => 'Custom Dashboard Module (Analytics Suite)', 'quantity' => 1, 'unit_price_cents' => 1800000],
                    ['description' => 'On-site Training & Knowledge Transfer', 'quantity' => 3, 'unit_price_cents' => 120000],
                ],
            ],
            [
                'client_email' => 'accounts@northropdesign.co',
                'invoice_number' => 'INV-0002',
                'status' => 'paid',
                'issue_date' => '2025-10-15',
                'due_date' => '2025-11-14',
                'line_items' => [
                    ['description' => 'Brand Identity Refresh — Logo & Style Guide', 'quantity' => 1, 'unit_price_cents' => 950000],
                    ['description' => 'Website Redesign (5-page responsive)', 'quantity' => 1, 'unit_price_cents' => 4200000],
                ],
            ],
            [
                'client_email' => 'ap@crestviewpg.com',
                'invoice_number' => 'INV-0003',
                'status' => 'sent',
                'issue_date' => '2025-11-01',
                'due_date' => '2025-12-01',
                'line_items' => [
                    ['description' => 'Portfolio Valuation Report — 12 Properties', 'quantity' => 1, 'unit_price_cents' => 3200000],
                    ['description' => 'Market Trend Analysis (Q4 2025)', 'quantity' => 1, 'unit_price_cents' => 750000],
                    ['description' => 'Tenant Risk Assessment Package', 'quantity' => 2, 'unit_price_cents' => 400000],
                ],
            ],
            [
                'client_email' => 'info@arborcreek.co',
                'invoice_number' => 'INV-0004',
                'status' => 'draft',
                'issue_date' => '2025-11-15',
                'due_date' => '2025-12-15',
                'line_items' => [
                    ['description' => 'Irrigation System Design & Planning', 'quantity' => 1, 'unit_price_cents' => 1100000],
                    ['description' => 'Native Plant Procurement — 240 units', 'quantity' => 1, 'unit_price_cents' => 680000],
                    ['description' => 'Site Preparation & Soil Amendment', 'quantity' => 5, 'unit_price_cents' => 95000],
                ],
            ],
            [
                'client_email' => 'finance@solaraenergy.io',
                'invoice_number' => 'INV-0005',
                'status' => 'overdue',
                'issue_date' => '2025-09-01',
                'due_date' => '2025-09-30',
                'line_items' => [
                    ['description' => 'Residential Solar Array Installation — 8kW', 'quantity' => 1, 'unit_price_cents' => 4850000],
                    ['description' => 'Battery Backup System — 13.5 kWh', 'quantity' => 2, 'unit_price_cents' => 950000],
                    ['description' => 'Electrical Panel Upgrade (200A)', 'quantity' => 1, 'unit_price_cents' => 320000],
                ],
            ],
            [
                'client_email' => 'office@bentleyfisk.com',
                'invoice_number' => 'INV-0006',
                'status' => 'paid',
                'issue_date' => '2025-10-20',
                'due_date' => '2025-11-19',
                'line_items' => [
                    ['description' => 'Contract Review — Client Matter #4421', 'quantity' => 12, 'unit_price_cents' => 37500],
                    ['description' => 'Deposition Preparation & Transcripts', 'quantity' => 1, 'unit_price_cents' => 620000],
                    ['description' => 'Discovery Document Processing', 'quantity' => 1, 'unit_price_cents' => 410000],
                ],
            ],
            [
                'client_email' => 'orders@harborviewcatering.com',
                'invoice_number' => 'INV-0007',
                'status' => 'sent',
                'issue_date' => '2025-11-20',
                'due_date' => '2025-12-20',
                'line_items' => [
                    ['description' => 'Corporate Gala — 180 Guests (Full Service)', 'quantity' => 1, 'unit_price_cents' => 8750000],
                    ['description' => 'Specialty Dessert Station Add-on', 'quantity' => 1, 'unit_price_cents' => 340000],
                ],
            ],
            [
                'client_email' => 'ar@talonmedia.com',
                'invoice_number' => 'INV-0008',
                'status' => 'draft',
                'issue_date' => '2025-11-25',
                'due_date' => '2025-12-25',
                'line_items' => [
                    ['description' => 'Social Media Campaign — Instagram & LinkedIn', 'quantity' => 1, 'unit_price_cents' => 2150000],
                    ['description' => 'Content Production — 15 Short-form Videos', 'quantity' => 1, 'unit_price_cents' => 3800000],
                    ['description' => 'Monthly Analytics Report & Strategy Call', 'quantity' => 3, 'unit_price_cents' => 180000],
                ],
            ],
            [
                'client_email' => 'billing@meridiananalytics.com',
                'invoice_number' => 'INV-0009',
                'status' => 'sent',
                'issue_date' => '2025-12-01',
                'due_date' => '2025-12-31',
                'line_items' => [
                    ['description' => 'Q4 Data Pipeline Maintenance & Monitoring', 'quantity' => 1, 'unit_price_cents' => 1800000],
                    ['description' => 'Predictive Forecasting Model — Retail Sector', 'quantity' => 1, 'unit_price_cents' => 3200000],
                ],
            ],
            [
                'client_email' => 'accounts@northropdesign.co',
                'invoice_number' => 'INV-0010',
                'status' => 'overdue',
                'issue_date' => '2025-08-15',
                'due_date' => '2025-09-14',
                'line_items' => [
                    ['description' => 'Packaging Design Suite — 6 SKUs', 'quantity' => 1, 'unit_price_cents' => 1650000],
                    ['description' => 'Print Production Management', 'quantity' => 1, 'unit_price_cents' => 420000],
                ],
            ],
        ];

        foreach ($invoices as $invData) {
            $client = $clients->firstWhere('email', $invData['client_email']);
            if (!$client) continue;

            $totalCents = 0;
            foreach ($invData['line_items'] as $li) {
                $liTotal = $li['quantity'] * $li['unit_price_cents'];
                $totalCents += $liTotal;
            }

            $invoice = Invoice::firstOrCreate(
                ['invoice_number' => $invData['invoice_number']],
                [
                    'client_id' => $client->id,
                    'amount' => $totalCents,
                    'status' => $invData['status'],
                    'issue_date' => $invData['issue_date'],
                    'due_date' => $invData['due_date'],
                ]
            );

            if ($invoice->wasRecentlyCreated) {
                foreach ($invData['line_items'] as $li) {
                    InvoiceLineItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => $li['description'],
                        'quantity' => $li['quantity'],
                        'unit_price' => $li['unit_price_cents'],
                        'total' => $li['quantity'] * $li['unit_price_cents'],
                    ]);
                }
            }
        }
    }
}
