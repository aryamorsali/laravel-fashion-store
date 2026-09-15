<?php

namespace Database\Seeders;

use App\Models\Content\FAQ;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'id'       => 1,
                'question' => 'How can I track my international order?',
                'answer'   => 'Once your order is shipped, you will receive a tracking number via email and SMS to track your package on DHL/FedEx portals.',
                'status'   => 1,
            ],
            [
                'id'       => 2,
                'question' => 'What is your return and refund policy?',
                'answer'   => 'You can return any unworn and unwashed items within 14 days of delivery for a full refund or exchange.',
                'status'   => 1,
            ],
            [
                'id'       => 3,
                'question' => 'What payment methods do you accept?',
                'answer'   => 'We accept all major international credit/debit cards (Visa, MasterCard, Amex), PayPal, and direct online banking.',
                'status'   => 1,
            ],
            [
                'id'       => 4,
                'question' => 'How long does delivery take?',
                'answer'   => 'Standard delivery usually takes 5-7 business days, while Express shipping takes 1-2 business days depending on your destination.',
                'status'   => 1,
            ],
            [
                'id'       => 5,
                'question' => 'Do you ship internationally?',
                'answer'   => 'Yes, we provide worldwide shipping across Europe, North America, Middle East, and Asia.',
                'status'   => 1,
            ],
        ];

        foreach ($faqs as $faq) {
            FAQ::updateOrCreate(
                ['id' => $faq['id']],
                $faq
            );
        }
    }
}
