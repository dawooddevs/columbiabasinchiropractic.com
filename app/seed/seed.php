<?php
/**
 * Seeds the database on a fresh install: services (app/seed/services.php), core pages and
 * redirects. Runs once, from the installer.
 *
 * Providers, locations, testimonials and homepage FAQs start empty. Add them in the
 * dashboard only from information the practice supplies; never invent them.
 */
function seed_database(): void
{
    $now = now();

    // ---------- Services ----------
    $services = require __DIR__ . '/services.php';
    foreach ($services as $i => $s) {
        $faqs = array_map(fn($f) => ['q' => $f[0], 'a' => $f[1]], $s['faqs'] ?? []);
        DB::insert('services', [
            'slug' => $s['slug'],
            'title' => $s['title'],
            'menu_label' => $s['menu_label'] ?? '',
            'category' => $s['category'],
            'excerpt' => $s['excerpt'],
            'hero_text' => $s['hero_text'],
            'image' => '',
            'icon' => $s['icon'] ?? '',
            'what_is' => Html::paragraphs($s['what_is']),
            'conditions' => $s['conditions'],
            'how_it_works' => Html::paragraphs($s['how_it_works']),
            'benefits' => $s['benefits'],
            'what_to_expect' => Html::paragraphs($s['what_to_expect']),
            'faqs' => json_encode($faqs),
            'related' => $s['related'],
            'body_areas' => $s['body_areas'],
            'featured' => (int)($s['featured'] ?? 0),
            'coming_soon' => (int)($s['coming_soon'] ?? 0),
            'show_in_menu' => (int)($s['show_in_menu'] ?? 1),
            'needs_review' => 1,
            'sort_order' => ($i + 1) * 10,
            'status' => $s['status'] ?? 'published',
            'meta_title' => $s['meta_title'] ?? $s['title'],
            'meta_description' => $s['meta_description'] ?? str_limit($s['excerpt'], 155),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    // ---------- Pages ----------
    foreach (seed_pages() as $i => $p) {
        DB::insert('pages', array_merge([
            'eyebrow' => '', 'intro' => '', 'image' => '', 'content' => '', 'template' => 'default', 'show_cta' => 1, 'is_system' => 0,
            'needs_review' => 0, 'status' => 'published', 'meta_title' => '', 'meta_description' => '',
        ], $p, ['sort_order' => ($i + 1) * 10, 'created_at' => $now, 'updated_at' => $now]));
    }

    // ---------- Redirects for common URL variations ----------
    foreach ([
        ['/service/', '/services/'], ['/treatments/', '/services/'],
        ['/appointment/', '/book-appointment/'], ['/make-appointment/', '/book-appointment/'], ['/book/', '/book-appointment/'],
        ['/contact/', '/contact-us/'], ['/home/', '/'],
    ] as [$from, $to]) {
        DB::insert('redirects', ['source' => $from, 'target' => $to, 'code' => 301, 'hits' => 0, 'note' => 'URL variation', 'created_at' => $now, 'updated_at' => $now]);
    }
}

function seed_pages(): array
{
    $name = Settings::get('site_name');
    return [
        [
            'slug' => 'services', 'title' => 'Services', 'eyebrow' => 'Services', 'template' => 'treatments', 'is_system' => 1,
            'intro' => '', 'meta_title' => 'Services',
        ],
        [
            'slug' => 'book-appointment', 'title' => 'Book an Appointment', 'eyebrow' => 'Appointments', 'template' => 'appointment', 'is_system' => 1, 'show_cta' => 0,
            'intro' => '', 'meta_title' => 'Book an Appointment',
        ],
        [
            'slug' => 'contact-us', 'title' => 'Contact Us', 'eyebrow' => 'Contact', 'template' => 'contact', 'is_system' => 1, 'show_cta' => 0,
            'intro' => '', 'meta_title' => 'Contact Us',
        ],
        [
            'slug' => 'privacy-policy', 'title' => 'Privacy Policy', 'eyebrow' => 'Legal', 'template' => 'legal', 'show_cta' => 0, 'needs_review' => 1,
            'intro' => 'How we collect, use and protect information submitted through this website.',
            'content' => '<h2>Information we collect</h2><p>When you submit a form on this website, for example to book an appointment or contact us, we collect the information you provide, such as your name, phone number, email address and message. We also collect basic, non-identifying usage information (such as pages visited) to improve the website.</p><h2>How we use information</h2><p>We use the information you submit to respond to your request, schedule appointments and communicate with you about your care. We do not sell your personal information.</p><h2>Protected health information</h2><p>Please do not submit detailed medical information through general website forms. Protected health information you share with us as a patient is handled in accordance with our Notice of Privacy Practices and applicable law, including HIPAA.</p><h2>Third-party services</h2><p>This website may use third-party services for scheduling, chat, maps and analytics. These providers process information on our behalf according to their own privacy policies.</p><h2>Contact</h2><p>Questions about this policy? Please contact our office.</p>',
            'meta_title' => 'Privacy Policy', 'meta_description' => 'Privacy policy for the ' . $name . ' website.',
        ],
        [
            'slug' => 'terms-and-conditions', 'title' => 'Terms & Conditions', 'eyebrow' => 'Legal', 'template' => 'legal', 'show_cta' => 0, 'needs_review' => 1,
            'intro' => 'The terms that apply to your use of this website.',
            'content' => '<h2>Website information</h2><p>The content on this website is provided for general educational purposes and is not medical advice. It is not a substitute for an evaluation by a qualified healthcare provider. Individual results vary, and eligibility for any treatment is determined after evaluation.</p><h2>Appointments</h2><p>Appointments requested online may need to be confirmed by our office.</p><h2>Emergencies</h2><p>Do not use this website for medical emergencies. If you are experiencing an emergency, call 911.</p><h2>Changes</h2><p>We may update these terms from time to time. Continued use of the website means you accept the current terms.</p>',
            'meta_title' => 'Terms & Conditions', 'meta_description' => 'Terms and conditions for the ' . $name . ' website.',
        ],
        [
            'slug' => 'medical-disclaimer', 'title' => 'Medical Disclaimer', 'eyebrow' => 'Legal', 'template' => 'legal', 'show_cta' => 0, 'needs_review' => 1,
            'intro' => 'Important information about the health content on this website.',
            'content' => '<p>The information on this website is provided for general informational purposes only. It is not intended to replace a one-on-one relationship with a qualified healthcare provider and should not be considered medical advice.</p><p>The content, materials and communications on this website should not be used as a substitute for professional medical advice, diagnosis or treatment. Always seek the advice of your physician or another qualified healthcare provider with any questions you have about a medical condition. Never disregard professional medical advice or delay seeking it because of something you read on this website.</p><p>Individual results vary, and eligibility for any treatment is determined after evaluation. If you have a medical emergency, call 911.</p>',
            'meta_title' => 'Medical Disclaimer', 'meta_description' => 'Medical disclaimer for the ' . $name . ' website.',
        ],
        [
            'slug' => 'sitemap', 'title' => 'Sitemap', 'eyebrow' => 'Sitemap', 'template' => 'sitemap', 'is_system' => 1, 'show_cta' => 0,
            'intro' => 'Every page on this website in one place.',
            'meta_title' => 'Sitemap', 'meta_description' => 'A complete list of pages on the ' . $name . ' website.',
        ],
    ];
}
