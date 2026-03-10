<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            ['keyword' => 'shipping', 'answer' => 'We offer standard shipping (3-5 business days) and express shipping (1-2 business days). Orders over $50 qualify for free standard shipping!'],
            ['keyword' => 'delivery', 'answer' => 'Delivery times depend on your chosen method at checkout. We will email you a tracking link as soon as your gear leaves our warehouse.'],
            ['keyword' => 'returns', 'answer' => 'To start a return, simply visit your Orders page, select the item you wish to return, and print the pre-paid shipping label.'],
            ['keyword' => 'order', 'answer' => 'You can view the real-time status of all your purchases by clicking "Previous Orders" in your user profile dropdown menu.'],
            ['keyword' => 'orders', 'answer' => 'To check on multiple orders, please visit your account dashboard and click on "Previous Orders".'],
            ['keyword' => 'status', 'answer' => 'Check your current order status by visiting your Recent Orders page from your account dashboard.'],
            ['keyword' => 'contact', 'answer' => 'You can reach our 24/7 support wizards via the Contact Us form on our homepage, or by emailing support@veltrix.com.'],
            ['keyword' => 'password', 'answer' => 'Forgot your password? No problem. Just head to the Login page and click "Forgot Password" to receive a secure reset link.'],
            ['keyword' => 'hello', 'answer' => 'Hey there, gamer! Welcome to Veltrix. I am your automated AI assistant. How can I help you level up your setup today?'],
            ['keyword' => 'hi', 'answer' => 'Hi! Welcome to Veltrix. How can I assist you with your gaming gear today?'],
            ['keyword' => 'help', 'answer' => 'I am the Veltrix AI Chatbot. I can help you with questions about shipping, returns, tracking your orders, and more! What do you need help with?'],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(['keyword' => $faq['keyword']], $faq);
        }
    }
}
