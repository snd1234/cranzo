<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cms_pages')) {
            Schema::create('cms_pages', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug', 191)->unique();
                $table->string('excerpt')->nullable();
                $table->longText('content');
                $table->string('image')->nullable();
                $table->string('meta_title')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('canonical_url')->nullable();
                $table->unsignedTinyInteger('status')->default(1)->index();
                $table->unsignedTinyInteger('show_in_footer')->default(1)->index();
                $table->string('footer_group', 20)->default('information')->index();
                $table->unsignedInteger('footer_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->string('question');
                $table->text('answer');
                $table->unsignedInteger('sort_order')->default(0)->index();
                $table->unsignedTinyInteger('status')->default(1)->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('newsletter_subscribers')) {
            Schema::create('newsletter_subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->unsignedTinyInteger('status')->default(1)->index();
                $table->unsignedTinyInteger('consent')->default(0);
                $table->string('token', 64)->unique();
                $table->timestamp('subscribed_at')->nullable();
                $table->timestamp('unsubscribed_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('newsletter_campaigns')) {
            Schema::create('newsletter_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('subject');
                $table->longText('content');
                $table->string('status', 20)->default('draft')->index();
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->unsignedInteger('recipient_count')->default(0);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('contact_us') && ! Schema::hasColumn('contact_us', 'subject')) {
            Schema::table('contact_us', function (Blueprint $table) {
                $table->string('subject')->nullable()->after('phone');
            });
        }

        $now = now();
        foreach ($this->pages() as $page) {
            $exists = DB::table('cms_pages')->where('slug', $page['slug'])->exists();
            if (! $exists) {
                DB::table('cms_pages')->insert($page + ['created_at' => $now, 'updated_at' => $now]);
            }
        }

        if (DB::table('faqs')->count() === 0) {
            foreach ($this->faqs() as $index => $faq) {
                DB::table('faqs')->insert($faq + [
                    'sort_order' => $index + 1,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaigns');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('cms_pages');
        if (Schema::hasTable('contact_us') && Schema::hasColumn('contact_us', 'subject')) {
            Schema::table('contact_us', function (Blueprint $table) {
                $table->dropColumn('subject');
            });
        }
    }

    private function note(): string
    {
        return '<p><strong>Owner confirmation needed.</strong> Replace any bracketed note with Cranzo’s confirmed business detail. This starter text does not state a refund window, warranty, registered address, tax number, or legal jurisdiction.</p>';
    }

    private function pages(): array
    {
        $note = $this->note();

        return [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'excerpt' => 'Cranzo sells car and bike accessories.',
                'content' => '<p>Cranzo is an online shop for car and bike accessories. The catalogue includes covers, mats, seat covers, and everyday vehicle-care products.</p><p>Product titles, prices, and stock shown on the site come from the Cranzo catalogue. Cash on delivery is offered where the checkout says it is available.</p><p>Customer support can be reached through the contact form. [Owner to confirm the company introduction, years in business, and service area.]</p>'.$note,
                'meta_title' => 'About Cranzo',
                'meta_description' => 'About the Cranzo car and bike accessories shop.',
                'status' => 1,
                'show_in_footer' => 1,
                'footer_group' => 'information',
                'footer_order' => 1,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'excerpt' => 'Send Cranzo a product or order question.',
                'content' => '<p>Use the form to ask about a product, an order, or a fitment. [Owner to confirm the phone number, email address, and business hours that should appear here.]</p>'.$note,
                'meta_title' => 'Contact Cranzo',
                'meta_description' => 'Contact Cranzo about products and orders.',
                'status' => 1,
                'show_in_footer' => 1,
                'footer_group' => 'support',
                'footer_order' => 1,
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'excerpt' => 'Common questions about shopping at Cranzo.',
                'content' => '<p>These answers are managed in the admin FAQ list. They do not replace the product page or the checkout.</p>'.$note,
                'meta_title' => 'Cranzo FAQ',
                'meta_description' => 'Questions about Cranzo orders, delivery, and returns.',
                'status' => 1,
                'show_in_footer' => 1,
                'footer_group' => 'support',
                'footer_order' => 2,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'excerpt' => 'How this shop handles customer information.',
                'content' => '<p>The shop stores the details you submit, including your name, email, phone, delivery address, and order contents, so an order or message can be handled.</p><p>Payment card details are not collected on this site while cash on delivery is the checkout method. [Owner to confirm any future payment provider.]</p><p>The site uses a session cookie to keep the cart. [Owner to confirm analytics or marketing cookies.]</p><p>Information is used to fulfil orders, reply to messages, and manage newsletter requests. It is not sold as a mailing list from this starter text.</p><p>[Owner to confirm how long records are kept, who can access them, and the contact for a privacy request.]</p>'.$note,
                'meta_title' => 'Privacy Policy | Cranzo',
                'meta_description' => 'How Cranzo handles customer and order information.',
                'status' => 1,
                'show_in_footer' => 1,
                'footer_group' => 'information',
                'footer_order' => 2,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'excerpt' => 'Conditions for browsing and ordering.',
                'content' => '<p>Prices and product details on the site can change. The price shown at checkout is the price charged for that order.</p><p>Placing an order asks Cranzo to supply the listed products. [Owner to confirm when an order is accepted, how cancellation works, and which law applies.]</p><p>Delivery timing is not promised by this starter text. [Owner to confirm shipping areas and charges.]</p><p>Catalogue text and images belong to their owners. Do not copy them for another shop.</p>'.$note,
                'meta_title' => 'Terms and Conditions | Cranzo',
                'meta_description' => 'Conditions for using the Cranzo shop and placing an order.',
                'status' => 1,
                'show_in_footer' => 1,
                'footer_group' => 'information',
                'footer_order' => 3,
            ],
            [
                'title' => 'Returns & Exchange',
                'slug' => 'returns-exchange',
                'excerpt' => 'How to ask about a return or exchange.',
                'content' => '<p>Contact Cranzo before sending an item back. This page does not set a return window, a restocking fee, or a refund timeline.</p><p>[Owner to confirm which products can be returned, the condition they must be in, what happens with a damaged or wrong item, and how a refund is paid.]</p><p>Until those rules are confirmed, use the contact form and include the order number.</p>'.$note,
                'meta_title' => 'Returns and Exchange | Cranzo',
                'meta_description' => 'How to contact Cranzo about a return or exchange.',
                'status' => 1,
                'show_in_footer' => 1,
                'footer_group' => 'support',
                'footer_order' => 3,
            ],
            [
                'title' => 'Terms of Use',
                'slug' => 'terms-of-use',
                'excerpt' => 'Acceptable use of the Cranzo website.',
                'content' => '<p>Use the website to browse products, place an order, and send a genuine enquiry. Do not attempt to disrupt the site, scrape it in a way that harms the service, or submit another person’s details without permission.</p><p>You are responsible for keeping access to your own email and for the accuracy of the address you enter at checkout.</p><p>Links to other sites are outside this shop. [Owner to confirm any additional acceptable-use rules.]</p>'.$note,
                'meta_title' => 'Terms of Use | Cranzo',
                'meta_description' => 'Acceptable use of the Cranzo website.',
                'status' => 1,
                'show_in_footer' => 1,
                'footer_group' => 'information',
                'footer_order' => 4,
            ],
        ];
    }

    private function faqs(): array
    {
        return [
            ['question' => 'How do I place an order?', 'answer' => 'Add a product to the cart and complete checkout. The current checkout offers cash on delivery.'],
            ['question' => 'When will my order arrive?', 'answer' => 'Delivery timing is not fixed on this page. [Owner to confirm the delivery areas and typical timing.]'],
            ['question' => 'How do I know a product fits my vehicle?', 'answer' => 'Use the product title and description. Do not assume a fit unless the product page states it.'],
            ['question' => 'Can I return or exchange an item?', 'answer' => 'Read the Returns & Exchange page and contact Cranzo with the order number. A return period has not been set in this starter content.'],
            ['question' => 'How are refunds handled?', 'answer' => '[Owner to confirm how a refund is paid and how long it takes.] Contact the shop before expecting a refund.'],
            ['question' => 'Is there a warranty?', 'answer' => '[Owner to confirm any product warranty.] The catalogue does not add a warranty by itself.'],
            ['question' => 'What if a product is out of stock?', 'answer' => 'The product page shows the stock held in the catalogue. An out-of-stock item cannot be added to the cart.'],
            ['question' => 'Do I need an account?', 'answer' => 'Checkout currently collects a delivery name, mobile number, and address. A customer login is not required for that form.'],
        ];
    }
};
