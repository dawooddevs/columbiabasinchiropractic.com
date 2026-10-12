<?php
/**
 * Seeds the database on a fresh install: services and conditions (app/seed/services.php), pages,
 * team, the Richland office, Google reviews, FAQs, settings and redirects from the old WordPress URLs.
 * Runs once, from the installer. All content comes from the practice's existing website.
 */
function seed_database(): void
{
    $now = now();

    // ---------- Services & conditions ----------
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
            'faqs' => json_encode($faqs, JSON_UNESCAPED_UNICODE),
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

    // ---------- Office ----------
    DB::insert('locations', [
        'slug' => 'richland', 'name' => 'Richland Office', 'address' => '1305 Fowler St., Suite 1C', 'city' => 'Richland', 'state' => 'WA', 'zip' => '99352',
        'phone' => '(509) 582-3549', 'fax' => '',
        'hours' => json_encode([
            ['days' => 'Monday', 'time' => '9:00 AM – 6:00 PM', 'schema' => ['Mo 09:00-18:00']],
            ['days' => 'Tuesday', 'time' => '10:00 AM – 6:00 PM', 'schema' => ['Tu 10:00-18:00']],
            ['days' => 'Wednesday – Friday', 'time' => '9:00 AM – 6:00 PM', 'schema' => ['We-Fr 09:00-18:00']],
            ['days' => 'Saturday – Sunday', 'time' => 'Closed', 'schema' => []],
        ], JSON_UNESCAPED_UNICODE),
        'map_embed' => '', 'directions_url' => 'https://maps.app.goo.gl/41dXwqLXBHHEnWHLA', 'image' => '',
        'description' => 'Our Richland clinic is designed to feel comfortable, personal, and focused on helping you move forward with confidence.',
        'latitude' => '', 'longitude' => '', 'sort_order' => 10, 'status' => 'published', 'created_at' => $now, 'updated_at' => $now,
    ]);

    // ---------- Team ----------
    foreach (seed_providers() as $i => $p) {
        DB::insert('providers', array_merge([
            'credentials' => '', 'photo' => '', 'photo_position' => '50% 20%', 'locations' => 'richland', 'short_bio' => '', 'bio' => '',
            'education' => '', 'experience' => '', 'philosophy' => '', 'focus' => '', 'services' => '', 'needs_review' => 1,
            'status' => 'published', 'meta_title' => '', 'meta_description' => '',
        ], $p, ['sort_order' => ($i + 1) * 10, 'created_at' => $now, 'updated_at' => $now]));
    }

    // ---------- Google reviews (published on the old site via Trustindex; original wording) ----------
    $reviews = [
        ['April K.', 'Really liked that the care felt tailored to me instead of a one-size-fits-all thing. They also broke down the costs and insurance in a way that was simple to follow, which took a lot of stress out of starting treatment.'],
        ['James C.', 'The people are very attentive to my needs and the chiropractor is awesome and listened and helped me work through the pain I was having'],
        ['Bryan N.', 'What really sold me here was how experienced the doctors are and how much time they spent actually listening to what was going on with my pain instead of rushing through it. They also walked me through my insurance and costs in detail and helped me get the most out of my benefits, which took a lot of stress out of starting treatment.'],
        ['Berkey Family', 'Very kind staff and appointment went smoothly. Eric was very attentive and respectful.'],
        ['Alison N.', "I've been a patient of Columbia Basin Chiropractic for years. Others with the same condition I have remark that my mobility is impressive. I tell them about the team here. The office personnel are so kind, they ask about things that matter to me and show sincere compassion for my wellness, or lapses in such. The process is always easy, and approachable!"],
        ['Kelsey C.', "Got in for a same day appointment even though I hadn't been there since 2023, which was really helpful. The provider actually listened to what was going on and helped me out, and I felt so much better walking out than when I came in."],
        ['Daniel C.', 'Very flexible at getting appointment last min! Very helpful when something spikes the back pain above the normal everyday pain. Thanks Dr Kurtz'],
        ['Sharon H.', 'All the staff is amazing! Love going here! Dr. Folkman is the best!'],
        ['Cade S.', 'Really like how friendly everyone is here, from the front desk to the doctor. The chiropractor was professional and clearly knew what they were doing, which made me feel comfortable about the treatment.'],
    ];
    foreach ($reviews as $i => [$name, $text]) {
        DB::insert('testimonials', ['name' => $name, 'label' => 'Google review', 'content' => $text, 'rating' => 5, 'featured' => 1, 'sort_order' => ($i + 1) * 10, 'status' => 'published', 'created_at' => $now, 'updated_at' => $now]);
    }

    // ---------- FAQs ----------
    $order = 0;
    foreach (seed_faqs() as $grp => $items) {
        foreach ($items as [$q, $a]) {
            DB::insert('faqs', ['question' => $q, 'answer' => $a, 'grp' => $grp, 'sort_order' => ($order += 10), 'status' => 'published', 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    // ---------- Settings ----------
    foreach (seed_settings() as $k => $v) {
        Settings::set($k, $v);
    }

    // ---------- Redirects: URL variations and the old WordPress URLs ----------
    $redirects = [
        '/service/' => '/services/', '/treatments/' => '/services/', '/our-services/' => '/services/',
        '/appointment/' => '/book-appointment/', '/make-appointment/' => '/book-appointment/', '/book/' => '/book-appointment/',
        '/schedule-an-appointment/' => '/book-appointment/',
        '/contact/' => '/contact-us/', '/location/' => '/contact-us/', '/work-with-us/' => '/contact-us/', '/home/' => '/',
        '/our-doctor/' => '/about-us/', '/doctor/' => '/about-us/',
        '/patient-paperwork/' => '/new-patient/', '/billing-and-insurance/' => '/faqs/',
        '/blog/' => '/', '/blog-standard/' => '/', '/blog-full-width/' => '/', '/landing-page/' => '/', '/coming-soon/' => '/',
        '/sports-medicine/' => '/service/sports-medicine/',
        '/accidents-injuries/' => '/service/accident-injury-care/', '/nutrition/' => '/service/nutrition-lifestyle-support/',
        '/massage-therapy/' => '/services/', '/service/medical-weight-loss/' => '/services/',
        '/service/spinal-decompression-therapy/' => '/service/spinal-decompression/',
        '/services/spinal-decompression-therapy/' => '/service/spinal-decompression/',
        '/stress-tension/' => '/stress/', '/slipped-discs/' => '/herniated-slipped-discs/',
        '/sms-policy/' => '/1-on-1-sms-privacy-policy/',
    ];
    foreach ($services as $s) {
        if ($s['category'] === Content::CONDITION_CATEGORY) {
            $redirects['/conditions/' . $s['slug'] . '/'] = '/' . $s['slug'] . '/';
        } else {
            $redirects['/services/' . $s['slug'] . '/'] = '/service/' . $s['slug'] . '/';
        }
    }
    foreach ($redirects as $from => $to) {
        DB::insert('redirects', ['source' => $from, 'target' => $to, 'code' => 301, 'hits' => 0, 'note' => 'Old site URL', 'created_at' => $now, 'updated_at' => $now]);
    }
}

function seed_settings(): array
{
    return [
        'site_name' => 'Columbia Basin Chiropractic Center',
        'site_short_name' => 'Columbia Basin Chiropractic',
        'tagline' => 'Chiropractic Care in Richland, WA',
        'brand_description' => 'Personalized chiropractic care focused on pain relief, better movement, recovery, and lasting function.',
        'phone' => '(509) 582-3549',
        'sms_phone' => '(509) 582-3549',
        'email' => 'info@columbiabasinchiropractic.com',
        'booking_url' => 'https://cbc.janeapp.com/',
        'portal_url' => 'https://main17.silkone-emr.com/PatientPortal.aspx?ClinicID=1628',
        'financing_url' => 'https://shop.columbiabasinmedical.instapaytient.com/shop/spinal-decompression',
        'communities' => 'Richland, Kennewick, Tri-Cities',
        'color_primary' => '#0b3b2a',
        'color_accent' => '#00804a',
        'color_highlight' => '#f6c453',
        'hero_eyebrow' => 'Personalized chiropractic care · Richland, WA',
        'hero_line1' => 'Better movement. Better function.',
        'hero_line2' => 'Better life.',
        'hero_copy' => 'Personalized chiropractic care, spinal decompression, and injury treatment from a local team committed to helping you move forward with confidence.',
        'hero_badges' => "Fast, friendly & affordable\nMultiple chiropractors on staff\nMost insurance accepted, including Medicare",
        'about_heading' => 'Care built around the way you live.',
        'about_copy' => 'Pain can affect how you work, sleep, move, and enjoy time with the people who matter. Our team takes the time to understand what is limiting you, then builds a thoughtful care plan around your condition and goals.',
        'purpose_heading' => 'Love God. Love people. Serve with excellence.',
        'purpose_copy' => 'Our mission is to serve patients and families with compassion, integrity, and focused chiropractic care that supports better health and function.',
        'featured_services' => 'chiropractic-care,spinal-decompression,accident-injury-care,sports-medicine,pregnancy-care,nutrition-lifestyle-support',
        'cta_headline' => 'Move toward better comfort and function.',
        'cta_copy' => 'Our team is ready to listen, answer your questions, and help you understand your next step.',
        'cta_small_print' => 'Call or text (509) 582-3549 · Mon, Wed–Fri 9 AM–6 PM · Tue 10 AM–6 PM',
        'youtube_videos' => implode("\n", array_map(fn($id) => 'https://www.youtube.com/watch?v=' . $id, ['4uxQG1-D-j4', 'kF0rSp21W3Y', 'sn8NGnmG4kw', 'enW0oJqtvTY', 'fK8rigYP0OE', 'dAusd8ZQfrA', '6wHhFq1C9Xw', '_3V1T-us5No', 'stSuHD3HBPs', 'ApeqS2kRHDc', '_9kWJFQ-DGA'])),
        'social_facebook' => 'https://www.facebook.com/KennewickChiropractor',
        'seo_title_suffix' => ' | Columbia Basin Chiropractic',
        'seo_default_description' => 'Columbia Basin Chiropractic offers personalized chiropractic care, spinal decompression, injury recovery and wellness care in Richland, WA.',
    ];
}

function seed_providers(): array
{
    $p = fn(string ...$x): string => implode('', array_map(fn($t) => '<p>' . e($t) . '</p>', $x));
    $staff = fn(string $name, string $slug, string $title) => ['slug' => $slug, 'name' => $name, 'title' => $title, 'type' => 'staff', 'photo_position' => '50% 25%'];
    return [
        [
            'slug' => 'dr-eric-kurtz', 'name' => 'Dr. Eric Kurtz', 'credentials' => 'D.C., CCSP', 'title' => 'Chiropractor & Certified Chiropractic Sports Practitioner', 'type' => 'chiropractor',
            'short_bio' => 'Coming from generations of mechanics, Dr. Kurtz has always enjoyed using his hands to fix things—but his love for mending is for the human body.',
            'bio' => $p('Coming from generations of mechanics, Dr. Eric Kurtz has always enjoyed using his hands to fix things. However, his love for mending is not for machines, but for the human body.')
                . '<blockquote>I became very passionate about becoming a chiropractor after seeing how chiropractic care helped my mother live a happier, healthier life. From a young age I was inspired to help people maintain their health through a drugless, gentle method of healing. Just like my mom’s chiropractor helped her.</blockquote>',
            'education' => $p('Graduated from Life Chiropractic College West in California. He also has an advanced degree in Sports Medicine, and is recognized as a Certified Chiropractic Sports Practitioner (CCSP), accredited by the American Chiropractic Association for sports injuries and physical fitness.'),
            'philosophy' => $p('Dr. Kurtz is committed to help you heal thoroughly so you can get back to doing the things you love. Through chiropractic care, he works to gently realign your body so that you can function just as you were designed to. From here, you’ve got nowhere to go but up.')
                . '<blockquote>My passion is to help athletes heal when they need it, and perform at the best level possible. You’d be surprised at how much chiropractic care can do for your sports performance.</blockquote>',
            'focus' => "Sports injuries and performance\nGentle, drug-free chiropractic care\nHelping patients heal thoroughly",
            'services' => 'chiropractic-care,sports-medicine,spinal-decompression,accident-injury-care',
            'meta_title' => 'Meet Dr. Eric Kurtz | Chiropractor in Richland, WA',
            'meta_description' => 'Dr. Eric Kurtz is a chiropractor and Certified Chiropractic Sports Practitioner at Columbia Basin Chiropractic in Richland, WA.',
        ],
        [
            'slug' => 'dr-rick-folkman', 'name' => 'Dr. Rick Folkman', 'credentials' => 'D.C.', 'title' => 'Chiropractor', 'type' => 'chiropractor',
            'short_bio' => '“I really wanted a career that I was passionate about, where I could truly help people live a better life.”',
            'bio' => '<h3>Discovering the perfect career</h3>' . $p('Initially, Dr. Rick Folkman had an ambition to become an electrical engineer or a dentist. “I really wanted a career that I was passionate about, where I could truly help people live a better life.” So, after narrowing it down to dentistry, he began school. Then a few years in, he went out into his community to shadow other local dentists, and just didn’t feel that’s where his heart was.', '“I went back to a childhood chiropractor to see if he loved his job, and he did (along with the other chiropractors in town).” So Dr. Folkman started seeing his chiropractor regularly and noticed that he just “felt better and functioned better than he thought possible.” “It was then that I was inspired to become a chiropractor, helping and healing people when no other methods could.”'),
            'education' => $p('Dr. Folkman graduated from Parker College of Chiropractic in Dallas, Texas. He loved the chiropractic philosophy and loved his experience undertaking the program.') . '<blockquote>With all that I learned in Chiropractic College I was so excited to get back to Richland to help our community thrive!</blockquote>',
            'philosophy' => '<h3>Doing what I love, loving what I do</h3>' . $p('“I’d love to meet with you and set up a time to see how I can help. Shall we get started?”'),
            'services' => 'chiropractic-care,spinal-decompression,accident-injury-care',
            'meta_title' => 'Meet Dr. Rick Folkman | Chiropractor in Richland, WA',
            'meta_description' => 'Discover Dr. Rick Folkman, a dedicated chiropractor specializing in pain, injuries, and musculoskeletal conditions in Richland, WA.',
        ],
        [
            'slug' => 'dr-liz-faletti', 'name' => 'Dr. Liz Faletti', 'credentials' => 'D.C.', 'title' => 'Chiropractor', 'type' => 'chiropractor',
            'short_bio' => 'The only chiropractor within 150 miles certified Advanced Proficient in Activator Methods®—ideal for patients who prefer not to have manual adjustments.',
            'bio' => $p('Dr. Faletti is the only chiropractor within 150 miles certified Advanced Proficient in Activator Methods®. This instrument-assisted adjusting technique offers outstanding results and is ideal for patients who do not like manual adjustments.', 'Dr. Faletti tries as many things as she can to get the most out of life. She’s been an artist, musician and teacher, and loves to raise, train and ride horses, and rides as often as possible. When she’s not at work, Dr. Faletti spends time with her husband, riding horses together, hanging out with friends, watching movies, solving puzzles, and going to the mountains.'),
            'experience' => '<h3>Her path to chiropractic</h3>' . $p('Dr. Faletti’s horse received chiropractic care before she did! A horse lover and trainer, she had a mare that was subtly not moving correctly. The veterinarian suggested chiropractic, since the mare’s problem seemed to originate in her back. Dr. Randy Baze helped Dr. Faletti’s horse, as well as her husband’s. Then Dr. Faletti and her husband became patients of Dr. Baze to help with their own back problems. While under active care, Dr. Faletti was seriously injured in a riding accident. Dr. Baze and chiropractic care got her back to riding within three months.', 'During that time, Dr. Faletti looked into the veterinary and chiropractic fields. She was a Spanish teacher, but originally wanted to become a veterinarian and was considering a career change. She decided on chiropractic so she could help people and animals.'),
            'focus' => "Activator Methods® (Advanced Proficient)\nInstrument-assisted adjusting\nGentle care for patients who prefer no manual adjustments",
            'services' => 'chiropractic-care,pregnancy-care,accident-injury-care',
            'meta_title' => 'Meet Dr. Liz Faletti | Chiropractor in Richland, WA',
            'meta_description' => 'Dr. Liz Faletti is certified Advanced Proficient in Activator Methods®, offering gentle instrument-assisted chiropractic care in Richland, WA.',
        ],
        $staff('Griselda Jensen', 'griselda-jensen', 'Chiropractic Office Manager'),
        $staff('Makayla Hunt', 'makayla-hunt', 'Chiropractic Assistant'),
        $staff('Evie Jensen', 'evie-jensen', 'Chiropractic Assistant'),
        $staff('Kristi Forsberg', 'kristi-forsberg', 'Chiropractic Assistant'),
        $staff('Sofia Jensen', 'sofia-jensen', 'Chiropractic Assistant'),
        $staff('Alexis Amador', 'alexis-amador', 'Social Media & Marketing Coordinator'),
    ];
}

function seed_faqs(): array
{
    return [
        'home' => [
            ['Do you take insurance?', 'Yes, we do. We take most healthcare insurance including Medicare. We also have cash and family plans if you’re interested.'],
            ['Does chiropractic care hurt?', 'No, we pride ourselves on being very gentle using multiple, comfortable ways to adjust your spine. We assure you that you’ll be comfortable, yet effective in relieving your condition.'],
            ['How do I know which service to schedule?', 'You can begin with an appointment based on your primary concern. The provider can evaluate your condition and determine whether one service or a combination of services may be appropriate.'],
            ['Can I schedule online?', 'Yes. Use our online scheduling system or call or text the Richland office at (509) 582-3549 for assistance.'],
        ],
        'general' => [
            ['What is the cost of chiropractic care?', 'Our chiropractic care fees vary depending on your needs, conditions, frequency of visit and insurance coverage. We’d love to speak with you about these things on the phone to give you a better idea, so give us a call.'],
            ['Do you take insurance?', 'Yes, we do. We take most healthcare insurance including Medicare. We also have cash and family plans if you’re interested.'],
            ['How often do I have to come back?', 'If your injury is serious you may require more continuous care, otherwise for smaller pains or discomfort you may not need to visit as often. Your health is up to you. We’ll make our suggestions, but in the end it’s your decision.'],
            ['Does chiropractic care hurt?', 'No, we pride ourselves on being very gentle using multiple, comfortable ways to adjust your spine. We assure you that you’ll be comfortable, yet effective in relieving your condition.'],
            ['Can multiple services be combined?', 'Yes. Depending on your findings and goals, chiropractic care may be combined with decompression, rehabilitation, sports care, pregnancy-specific modifications, or nutrition and lifestyle support.'],
            ['Will I receive treatment during my first visit?', 'That depends on your condition, examination findings, and whether treatment is appropriate that day. The provider will explain the recommendation before care begins.'],
            ['Are the services non-invasive?', 'Our services focus on non-invasive care, rehabilitation, movement, and lifestyle support. Your provider will explain the specific approach recommended for you.'],
        ],
        'myths' => [
            ['Is chiropractic scientific?', 'If you define “scientific” as the systematic pursuit of knowledge involving the recognition of a problem, the collection of data through observation and experiment and then testing the resulting hypotheses, then today’s chiropractic is quite scientific. Don’t be misled by the “low-tech” nature of chiropractic adjustments!'],
            ['Are chiropractors well educated?', 'The educational requirements for today’s chiropractor are among the most stringent of any of the health care professions. Several decades ago chiropractic education focused on anatomy, the philosophy of natural healing, the wisdom of the body and adjusting techniques. Today’s chiropractor receives a much broader education.'],
            ['Will I have to keep coming forever?', 'You don’t have to do anything you don’t want to. Many folks choose to continue their chiropractic care on some type of periodic basis for the rest of their lives. Others choose to see us from time to time for episodes of neck or back pain. It’s your choice.'],
            ['Do I need a referral?', 'You don’t need a referral or permission from anyone to see a chiropractor. However, if you expect financial assistance from certain insurance plans, some policies dictate that certain approval steps be taken before commencing care.'],
        ],
        'new-patients' => [
            ['What should I wear?', 'Please wear comfortable clothes that you can bend and move in.'],
            ['When should I arrive?', 'Feel free to arrive 10–15 minutes earlier than your appointment so there’s time to fill out paperwork about your medical history, insurance coverage and what brought you in.'],
            ['Will I be adjusted on my first visit?', 'Usually, we use a one-visit process where our doctors may adjust you on your first visit. However, in severe cases, we may need to gather additional information such as outside MRIs to determine the best care plan for you.'],
            ['How long are routine visits?', 'Regular visits last around 30 to 45 minutes. Once you sign in, you’ll be taken back to the private adjusting room and relax for a few minutes before your doctor comes in for a review.'],
        ],
    ];
}

function seed_pages(): array
{
    $name = Settings::get('site_name');
    $p = fn(string ...$x): string => implode('', array_map(fn($t) => '<p>' . e($t) . '</p>', $x));
    return [
        [
            'slug' => 'services', 'title' => 'Care built around how you want to live.', 'eyebrow' => 'Services', 'template' => 'treatments', 'is_system' => 1,
            'intro' => 'Personalized, non-invasive services focused on pain relief, movement, recovery, performance, pregnancy comfort, and long-term health.',
            'content' => $p('Pain, injury, limited movement, pregnancy, athletic performance, and lifestyle concerns rarely affect only one part of daily life. Columbia Basin Chiropractic Center offers connected services designed to help patients understand what is happening, explore appropriate options, and move forward with a personalized care plan.'),
            'meta_title' => 'Chiropractic Services | Pain Relief & Wellness Care',
            'meta_description' => 'Explore chiropractic services at Columbia Basin Chiropractic, including back pain relief, spinal decompression, sports medicine, and wellness care.',
        ],
        [
            'slug' => 'conditions', 'title' => 'Find care for what is holding you back.', 'eyebrow' => 'Conditions', 'template' => 'conditions', 'is_system' => 1,
            'intro' => 'Select a concern to learn more about symptoms, possible causes, and available chiropractic care options.',
            'meta_title' => 'Chiropractic Treatment for Common Conditions | Richland, WA',
            'meta_description' => 'Explore common conditions treated at Columbia Basin Chiropractic, including neck pain, headaches, sciatica, and more, with personalized care.',
        ],
        [
            'slug' => 'about-us', 'title' => 'Going beyond chiropractic care for your best wellness.', 'eyebrow' => 'About the clinic', 'template' => 'about',
            'intro' => 'We believe that chiropractic care is a foundation for your best health. By combining chiropractic care with massage therapy and nutritional counseling, you can take your health even farther.',
            'content' => '<h2>We truly want to see you thrive</h2>' . $p('Are you having a hard time finding relief? Have you tried everything but nothing’s working? That’s why we’re here. We believe that chiropractic care can help take your health to a level you’ve only dreamed of. We want to help you find relief first. Then, your body can heal thoroughly and soon you can feel your best again.', 'At Columbia Basin Chiropractic, we use an adjusting style commonly referred to as “Diversified.” First, an analysis of your spine is performed. This can initially involve a case history and X-ray pictures of your spine.', 'Are you an athlete? We even offer Sports Medicine to help you function and perform at your best level.'),
            'meta_title' => 'About Columbia Basin Chiropractic | Trusted Chiropractors',
            'meta_description' => 'Learn about Columbia Basin Chiropractic’s dedicated team, providing personalized care to improve your health and quality of life in Richland, WA.',
        ],
        [
            'slug' => 'new-patient', 'title' => 'Helping you move + feel better.', 'eyebrow' => 'New patients', 'template' => 'default',
            'intro' => 'To create a safe and effective treatment plan that addresses the root of the problem instead of simply treating symptoms, we provide each patient with a thorough examination and accurate diagnostic assessment.',
            'content' => '<h2>How to prepare</h2>' . $p('Please wear comfortable clothes that you can bend and move in. Feel free to arrive 10–15 minutes earlier than your appointment so there’s time to fill out paperwork about your medical history, insurance coverage and what brought you in.', 'Usually, we use a one-visit process where our doctors may adjust you on your first visit. However, in severe cases, we may need to gather additional information such as outside MRIs to determine the best care plan for you.')
                . '<h3>New to chiropractic care?</h3>' . $p('No problem. We have interactive TVs with presentations to explain how chiropractic care works. We also send out periodic emails and newsletters to educate and share information with you.')
                . '<h2>Your first visit</h2><ol class="steps"><li><strong>Paperwork &amp; vitals</strong> After doing paperwork, we’ll carry out a general assessment, taking your vital information such as blood pressure, weight and height. This gives us a better idea of where your health stands today and is a great reference point to compare to later on.</li><li><strong>Meet your chiropractor</strong> They’ll sit down with you and review your case through a brief consultation of about 15 minutes. If you decide to do an examination, we’ll carry out a spinal exam focusing on the area of your discomfort. (Your consultation is complimentary if you decide not to proceed with care.)</li><li><strong>Examination</strong> You’ll be treated or examined in a private room to ensure your comfort and privacy. If X-rays are needed we’ll discuss why and where we would take them, then discuss what we found and what we can do to help.</li><li><strong>Your first adjustment</strong> If you think chiropractic care is right for you, it’s time for an adjustment.</li></ol>'
                . '<h2>Routine visits</h2>' . $p('Regular visits last around 30 to 45 minutes. Once you sign in, you’ll be taken back to the private adjusting room and relax for a few minutes before your doctor comes in for a review. Then they’ll give you an adjustment and discuss your progress to make sure you’re on track.', 'Doing rehab with us? If you need therapy, you’ll be taken to the rehabilitation therapy room, usually before seeing your doctor.')
                . '<h2>Insurance &amp; payment</h2>' . $p('Insurance coverage is something we work on together with you, because we want to make sure your treatment is covered. We take most healthcare insurance including Medicare, and strive to make the process as easy as possible. For those without insurance, we’ll still help you reach your wellness goals: we offer reasonable financial arrangements, and cash and family plans.'),
            'needs_review' => 1,
            'meta_title' => 'New Patient Info | Columbia Basin Chiropractic',
            'meta_description' => 'Start your journey to better health at Columbia Basin Chiropractic. Get all the details on what to expect during your first visit.',
        ],
        [
            'slug' => 'faqs', 'title' => 'Questions, answered.', 'eyebrow' => 'FAQs', 'template' => 'faq',
            'intro' => 'Choosing the proper healthcare provider is important. We know what it’s like to be new somewhere, and how nice it is to know what to expect ahead of time. Enjoy some of our more commonly asked questions, and give us a call so we can set up a time to meet you.',
            'meta_title' => 'Chiropractic FAQs | Answers to Common Questions',
            'meta_description' => 'Get answers to common questions about chiropractic care, treatments, and conditions on our FAQs page at Columbia Basin Chiropractic Center.',
        ],
        [
            'slug' => 'testimonials', 'title' => 'Trusted by patients throughout the Tri-Cities.', 'eyebrow' => 'Patient reviews', 'template' => 'testimonials',
            'intro' => 'Read real experiences from people who have chosen Columbia Basin Chiropractic for their care.',
            'meta_title' => 'Patient Reviews | Columbia Basin Chiropractic',
            'meta_description' => 'Read real Google reviews from patients of Columbia Basin Chiropractic in Richland, WA.',
        ],
        [
            'slug' => 'our-videos', 'title' => 'Understand your care. Feel confident in your next step.', 'eyebrow' => 'Patient education', 'template' => 'videos',
            'intro' => 'Explore helpful videos from the Columbia Basin Chiropractic team about chiropractic care, common conditions, and better movement.',
            'meta_title' => 'Columbia Basin Chiropractic Videos | Health Tips & Insights',
            'meta_description' => 'Watch informative videos from Columbia Basin Chiropractic on pain relief, chiropractic care, and wellness tips from our expert team.',
        ],
        [
            'slug' => 'patient-portal', 'title' => 'Patient Portal', 'eyebrow' => 'Patients', 'template' => 'default', 'show_cta' => 0, 'needs_review' => 1,
            'intro' => 'View your treatment plans, appointment history, and more through our secure patient portal.',
            'content' => '<h2>How to access our patient portal</h2>' . $p('If you do not already have a PIN, please call or text our office for your dedicated PIN: (509) 582-3549.', 'The default PIN number for everyone is 1111 – if you are a new patient and have seen us since January of 2023.') . '<p><a href="https://main17.silkone-emr.com/PatientPortal.aspx?ClinicID=1628" target="_blank" rel="noopener">Access the Patient Portal →</a></p>',
            'meta_title' => 'Access Your Patient Portal | Columbia Basin Chiropractic',
            'meta_description' => 'View your treatment plans, appointment history, and more by accessing your patient portal at Columbia Basin Chiropractic.',
        ],
        [
            'slug' => 'book-appointment', 'title' => 'Book your visit.', 'eyebrow' => 'Appointments', 'template' => 'appointment', 'is_system' => 1, 'show_cta' => 0,
            'intro' => 'Book online in a couple of minutes, or send us a request and our team will reach out. If you can’t find a time that meets your needs, call or text us at (509) 582-3549.',
            'meta_title' => 'Schedule Your Appointment | Columbia Basin Chiropractic',
            'meta_description' => 'Schedule your chiropractic appointment today at Columbia Basin Chiropractic. Start your journey to pain relief and improved wellness.',
        ],
        [
            'slug' => 'contact-us', 'title' => 'We’re here to help.', 'eyebrow' => 'Contact', 'template' => 'contact', 'is_system' => 1, 'show_cta' => 0,
            'intro' => 'Questions about your first visit, appointment availability, or the care that may be right for you? Call, text, or send us a message.',
            'meta_title' => 'Contact Columbia Basin Chiropractic | Richland, WA',
            'meta_description' => 'Contact Columbia Basin Chiropractic at 1305 Fowler St., Suite 1C, Richland, WA 99352. Call or text (509) 582-3549.',
        ],
        [
            'slug' => 'privacy-policy', 'title' => 'Privacy Policy & SMS/Text Messaging Terms', 'eyebrow' => 'Legal', 'template' => 'legal', 'show_cta' => 0, 'needs_review' => 1,
            'intro' => 'Last updated: 09/25/2025',
            'content' => '<p>Columbia Basin Chiropractic is committed to protecting the privacy and security of our patients and website visitors. This Privacy Policy explains how we collect, use, and safeguard your information when you interact with our practice, including through our website, patient portal, and text messaging services.</p><p>By visiting our website, providing us with your information, or opting in to receive text messages, you agree to the practices described below.</p>'
                . '<h2>1. Information we collect</h2><p>We may collect the following types of information:</p><ul><li><strong>Personal Information</strong> – such as your name, date of birth, phone number, email address, mailing address, and insurance details.</li><li><strong>Health Information (PHI)</strong> – such as medical history, treatment information, or test results provided in our clinic or via our secure patient portal, in accordance with HIPAA.</li><li><strong>Website Usage Data</strong> – such as IP addresses, browser type, device information, and site interaction patterns using cookies or analytics tools.</li><li><strong>SMS/Text Messaging Information</strong> – including your mobile number and message history if you opt-in to receive text messages.</li></ul>'
                . '<h2>2. How we use your information</h2><p>We use your information for purposes including but not limited to:</p><ul><li>Scheduling and confirming appointments.</li><li>Providing healthcare services and communicating with you about your care.</li><li>Sending appointment reminders, health updates, or important announcements.</li><li>Offering promotions or special programs (only if you have opted in).</li><li>Fulfilling legal and regulatory obligations.</li><li>Improving our website and services.</li></ul>'
                . '<h2>3. SMS/Text messaging terms</h2><p>By providing your mobile phone number and explicitly opting in, you consent to receive SMS or text messages from <strong>Columbia Basin Chiropractic</strong>. These may include:</p><ul><li>Appointment confirmations and reminders.</li><li>Health and wellness notifications.</li><li>Promotions and special offers (only if you have opted in to receive marketing messages).</li><li>Billing or account-related alerts.</li></ul><h3>Opt-in</h3><ul><li>You must provide express consent (for example, by checking a box or signing a form).</li><li>You must be the owner or authorized user of the mobile number provided.</li></ul><h3>Message frequency</h3><ul><li>You may receive up to <strong>4–6 messages per month</strong>, though frequency may vary depending on your appointments or services.</li></ul><h3>Opt-out</h3><ul><li>You can stop receiving messages at any time by replying <strong>STOP</strong> to any message.</li><li>You may also contact us at <strong>(509) 582-3549</strong> or <strong>info@columbiabasinchiropractic.com</strong> to request removal from SMS communications.</li></ul><h3>Help/assistance</h3><ul><li>Reply <strong>HELP</strong> to any message for more information or call us at <strong>(509) 582-3549</strong>.</li></ul><h3>Fees</h3><ul><li>Standard message and data rates may apply depending on your mobile carrier.</li><li>Columbia Basin Chiropractic is not responsible for these charges.</li></ul><h3>Privacy and data security</h3><ul><li>We do <strong>not</strong> sell, rent, or share your phone number with third parties for marketing purposes.</li><li>Your mobile number is securely stored and only used for communications you have consented to receive.</li><li>All communications containing Protected Health Information (PHI) are handled in compliance with HIPAA guidelines.</li></ul>'
                . '<h2>4. Data protection and HIPAA compliance</h2><p>We follow all HIPAA standards for protecting your medical and personal information.</p><ul><li>Access to your health records is limited to authorized staff only.</li><li>Electronic records are stored securely and encrypted when transmitted.</li><li>We train our staff regularly on HIPAA compliance and patient confidentiality.</li></ul>'
                . '<h2>5. Third-party services</h2><p>Our website may use third-party services such as analytics or appointment scheduling tools. These services may collect non-identifiable data to help us improve your experience.</p><ul><li>If you use our patient portal or scheduling system, those platforms are also bound by HIPAA compliance rules.</li></ul>'
                . '<h2>6. Your rights</h2><p>You have the right to:</p><ul><li>Request a copy of your health records.</li><li>Request corrections to your records.</li><li>Withdraw consent for marketing communications at any time.</li><li>File a complaint if you believe your privacy rights have been violated.</li></ul>'
                . '<h2>7. Cookies and website tracking</h2><p>We may use cookies or similar technologies to improve site performance and user experience. You can disable cookies in your browser settings, though some features may not work correctly.</p>'
                . '<h2>8. Changes to this policy</h2><p>We may update this Privacy Policy periodically. Changes will be posted here with a revised “Last Updated” date. If significant changes are made, we will notify you via email, SMS, or website notice.</p>'
                . '<h2>9. Contact information</h2><p>If you have questions about this Privacy Policy or your information, please contact us:</p><p><strong>Columbia Basin Chiropractic</strong><br>1305 Fowler St., Suite 1C<br>Richland, WA 99352<br>Phone: <strong>(509) 582-3549</strong><br>Email: <strong>info@columbiabasinchiropractic.com</strong></p>',
            'meta_title' => 'Privacy Policy', 'meta_description' => 'Privacy policy and SMS/text messaging terms for ' . $name . '.',
        ],
        [
            'slug' => '1-on-1-sms-privacy-policy', 'title' => '1-on-1 SMS Privacy Policy', 'eyebrow' => 'Legal', 'template' => 'legal', 'show_cta' => 0,
            'intro' => 'How we handle SMS consent, frequency, opt-in and opt-out.',
            'content' => '<h2>SMS message consent</h2><p>When you opt in to receive SMS communications from <strong>Columbia Basin Chiropractic</strong>, we collect your phone number and any other necessary information to communicate with you via text messages. This information is not shared with third parties for marketing purposes. We respect your privacy and are committed to keeping your data secure.</p><h2>SMS message frequency &amp; fees</h2><p>The frequency of SMS messages may vary depending on the type of communication you are receiving. Standard message and data rates may apply depending on your mobile carrier’s pricing plan. These fees may vary if messages are sent domestically or internationally.</p><h2>SMS opt-in</h2><p>By providing your phone number and opting in, you agree to receive text messages from <strong>Columbia Basin Chiropractic</strong>. Message and data rates may apply. Message frequency may vary depending on service activity.</p><h2>SMS opt-out</h2><p>If you are receiving text messages from us and wish to stop receiving messages, you may opt out at any time by replying <strong>STOP</strong> to the number from which you received the message. Once we receive your request, you will no longer receive SMS communications from us.</p><h2>SMS privacy policy</h2><p><strong>Columbia Basin Chiropractic</strong> maintains strict privacy policies to protect the personal information of our patients and users. Personal information will not be shared, sold, rented, released, or traded with third parties for marketing or promotional purposes without prior consent or legal obligation.</p><p>All the above categories exclude text messaging originator opt-in data and consent. This information will <strong>not be shared with any third parties</strong>. No mobile opt-in data will be shared with third parties.</p>',
            'meta_title' => '1-on-1 SMS Privacy Policy', 'meta_description' => 'SMS privacy policy for ' . $name . '.',
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
