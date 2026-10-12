<?php
/**
 * Services and conditions loaded on a fresh install. Content comes from the practice's existing
 * WordPress site (columbiabasinchiropractic.com). After install, edit in the dashboard or add a
 * migration in app/migrations/ (the seed only runs once, at install).
 *
 * Conditions use the category 'conditions' (Content::CONDITION_CATEGORY) and are served at /<slug>/,
 * the same URLs as the old site. For conditions the fields are shown as:
 *   what_is = overview, conditions = common causes, benefits = symptoms,
 *   how_it_works = treatment options (a leading <div class="callout callout--warn"> is shown as warning signs),
 *   what_to_expect = your first visit.
 *
 * HTML is allowed in what_is, how_it_works and what_to_expect; conditions and benefits take one item
 * per line; faqs is a list of [question, answer].
 */

/** Card grid: [[title, text], …] → rich-text HTML the templates style as cards. */
$cards = function (array $items): string {
    $h = '<div class="cards">';
    foreach ($items as [$t, $p]) {
        $h .= '<div class="card"><h3>' . e($t) . '</h3><p>' . e($p) . '</p></div>';
    }
    return $h . '</div>';
};
/** Numbered steps: [[title, text], …]. */
$steps = function (array $items): string {
    $h = '<ol class="steps">';
    foreach ($items as [$t, $p]) {
        $h .= '<li><strong>' . e($t) . '</strong> ' . e($p) . '</li>';
    }
    return $h . '</ol>';
};
/** Warning-signs callout shown under "Symptoms" on condition pages. */
$warn = function (string $title, array $items): string {
    $h = '<div class="callout callout--warn"><h3>' . e($title) . '</h3><ul>';
    foreach ($items as [$t, $p]) {
        $h .= '<li><strong>' . e($t) . '.</strong> ' . e($p) . '</li>';
    }
    return $h . '</ul></div>';
};
$p = fn(string ...$paras): string => implode('', array_map(fn($x) => '<p>' . e($x) . '</p>', $paras));

return [
    // ================================================================= CORE CARE
    [
        'slug' => 'chiropractic-care', 'title' => 'Chiropractic Care', 'category' => 'chiropractic', 'icon' => 'spine', 'featured' => 1,
        'excerpt' => 'Personalized adjustments and care plans focused on improving movement, comfort, and everyday function.',
        'hero_text' => 'Personalized chiropractic treatment focused on reducing pain, restoring healthy movement, and helping you return to the activities that matter most.',
        'what_is' => $p(
            'Chiropractic care is a non-invasive form of treatment focused on the relationship between the spine, joints, muscles, nerves, and the way the body moves.',
            'When a joint is restricted or is not moving correctly, nearby muscles and tissues may compensate. Over time, this can contribute to discomfort, stiffness, reduced mobility, altered posture, or difficulty completing normal activities.',
            'Chiropractic adjustments use precise and controlled movements to improve joint function. Depending on the patient, treatment may include manual adjustments, instrument-assisted techniques, mobility work, rehabilitation exercises, or other supportive therapies.'
        ) . '<blockquote>We look beyond the location of the pain to understand how the body is moving as a whole.</blockquote>',
        'conditions' => "Lower back pain\nNeck pain and stiffness\nSciatica and radiating discomfort\nHeadaches related to neck tension\nShoulder and upper-back discomfort\nJoint restriction and reduced mobility\nWork-related strain\nSports and activity-related discomfort",
        'how_it_works' => $p(
            'Your care plan is based on what your provider finds during your consultation and examination. Treatment techniques can be adjusted to accommodate your age, health history, comfort level, symptoms, and goals.',
            'Chiropractic treatment may be used independently or as one component of a broader plan involving decompression, therapeutic exercise, movement education, or additional services when appropriate.'
        ) . $cards([
            ['Joint Mobility', 'Help restricted joints move more normally and comfortably.'],
            ['Reduced Tension', 'Address compensation and tension affecting muscles and surrounding tissues.'],
            ['Daily Function', 'Support more comfortable movement during work, exercise, and everyday activity.'],
        ]) . '<h3>Treatment may include</h3><ul><li>Manual chiropractic adjustments</li><li>Instrument-assisted adjustments</li><li>Mobility and stretching guidance</li><li>Corrective or rehabilitative exercises</li><li>Posture and movement recommendations</li><li>Complementary supportive therapies</li></ul>',
        'benefits' => "Individualized evaluation\nGentle treatment options\nNon-invasive, drug-free care\nCare plans built around your goals\nCan be coordinated with decompression, rehab and lifestyle support",
        'what_to_expect' => $p('Your first appointment is designed to help the provider understand your condition and determine whether chiropractic care is appropriate for you.') . $steps([
            ['Consultation', 'We discuss your symptoms, health history, previous injuries, daily activities, and the results you hope to achieve.'],
            ['Examination', 'Your provider evaluates posture, joint movement, mobility, areas of tenderness, and other relevant clinical findings.'],
            ['Explanation', 'We explain what may be contributing to your symptoms and answer questions about the available treatment options.'],
            ['Care Plan', 'When appropriate, your provider recommends a treatment plan based on your findings, needs, comfort, and goals.'],
        ]),
        'faqs' => [
            ['Does chiropractic treatment hurt?', 'Chiropractic care is generally intended to be comfortable. Your provider can select or modify techniques based on your condition, sensitivity, age, and comfort level.'],
            ['How many appointments will I need?', 'The number and frequency of visits depend on your condition, health history, goals, and response to treatment. Recommendations are discussed after your evaluation.'],
            ['Is chiropractic care only for back pain?', 'No. Patients may also seek chiropractic care for neck pain, headaches associated with neck tension, joint restriction, sciatica, muscle tension, and other movement-related concerns.'],
            ['Can chiropractic care be combined with other treatments?', 'Yes. Depending on your findings, chiropractic treatment may be combined with decompression, rehabilitation exercises, movement guidance, or other appropriate services.'],
            ['What should I bring to my first visit?', 'Bring any requested forms, identification, insurance information when applicable, and relevant records or imaging that may help your provider better understand your condition.'],
        ],
        'related' => 'spinal-decompression,accident-injury-care,sports-medicine',
        'body_areas' => 'neck,shoulder,upper-back,lower-back,hip-leg,whole-body',
        'meta_title' => 'Chiropractic Care in Richland, WA | Pain Relief & Mobility',
        'meta_description' => 'Experience chiropractic care at Columbia Basin Chiropractic to relieve pain, improve mobility, and promote overall health and wellness.',
    ],
    [
        'slug' => 'spinal-decompression', 'title' => 'Spinal Decompression Therapy', 'menu_label' => 'Spinal Decompression', 'category' => 'chiropractic', 'icon' => 'move', 'featured' => 1,
        'excerpt' => 'Non-surgical care for disc-related back pain, neck pain, sciatica, and nerve irritation.',
        'hero_text' => 'A gentle, non-surgical treatment designed to reduce pressure on spinal discs and nerves while supporting mobility, comfort, and long-term recovery.',
        'what_is' => $p(
            'Non-surgical spinal decompression is a specialized form of motorized traction. During treatment, the spine is gently stretched and relaxed through carefully controlled cycles.',
            'These controlled changes may reduce pressure inside the spinal discs and create a more favorable environment for movement, circulation, and the body’s natural healing process.',
            'The treatment settings are customized based on the area being treated, the patient’s condition, body position, comfort, and response to therapy.'
        ) . '<blockquote>Disc pressure can affect far more than the spine. It can interfere with walking, sitting, sleeping, working, exercising, and enjoying everyday life.</blockquote>',
        'conditions' => "Herniated or bulging discs\nDegenerative disc disease\nSciatica and radiating leg pain\nChronic lower-back pain\nNeck pain and stiffness\nPinched or irritated nerves\nFacet joint discomfort\nReduced spinal mobility",
        'how_it_works' => $p(
            'Spinal discs act as cushions between the vertebrae. When a disc becomes injured, compressed, bulging, or irritated, nearby nerves and tissues may also become affected.',
            'Decompression therapy applies gentle traction to the spine. This may help reduce pressure on affected discs and support improved movement of fluid, oxygen, and nutrients through the surrounding tissues. The goal is not to force the spine: the system uses controlled phases of pull and relaxation based on your treatment plan.'
        ) . $cards([
            ['Reduced Pressure', 'Help reduce mechanical pressure affecting discs and nearby structures.'],
            ['Improved Space', 'Encourage separation and movement between targeted spinal segments.'],
            ['Better Circulation', 'Support movement of oxygen, nutrients, and fluid through surrounding tissues.'],
            ['Improved Function', 'Support more comfortable movement and normal daily activity.'],
        ]) . '<h3>What a session feels like</h3>' . $p('During treatment, you remain fully clothed and lie comfortably on the decompression table. A support system is positioned around the body to help control the treatment area. The computerized system then applies gentle and carefully measured traction. Most patients describe the process as comfortable and relaxing.', 'Decompression is usually provided as a series of sessions of approximately 20–30 minutes. The number and frequency of visits depend on your diagnosis, symptom history, severity, response to treatment, and overall care plan.')
            . '<div class="callout"><h3>Decompression may not be appropriate when</h3><ul><li>Certain fractures or spinal instability are present</li><li>Recent spinal surgery requires restrictions</li><li>Severe osteoporosis affects the spine</li><li>Specific tumors or infections are involved</li><li>Other medical contraindications are identified</li></ul></div>',
        'benefits' => "Non-surgical, drug-free treatment\nCustomized settings for your condition\nComfortable, relaxing sessions\nCan be combined with chiropractic care\nFinancing available through Affirm",
        'what_to_expect' => $p('The best way to determine whether decompression is appropriate is through a consultation, examination, and review of your health history and any available imaging.') . $steps([
            ['Consultation', 'We review your symptoms, pain pattern, previous treatment, injuries, and health history.'],
            ['Examination', 'Your provider evaluates movement, posture, spinal function, neurological findings, and areas of discomfort.'],
            ['Imaging Review', 'When relevant, existing X-rays, MRI findings, or other records may be reviewed.'],
            ['Recommendation', 'We explain whether decompression is appropriate and how it may fit into your broader care plan.'],
        ]),
        'faqs' => [
            ['Does spinal decompression hurt?', 'Treatment is generally designed to be comfortable. The settings are adjusted to your condition and tolerance, and you should communicate any discomfort during the session.'],
            ['How long is each treatment session?', 'A typical decompression session may take approximately 20 to 30 minutes, although appointment length can vary depending on the treatment plan.'],
            ['How many sessions will I need?', 'The number of visits varies by condition, symptom severity, health history, and response to treatment. Your provider will explain the recommended schedule after your evaluation.'],
            ['Can decompression help a herniated disc?', 'Spinal decompression may be considered for certain patients with herniated or bulging discs. A clinical evaluation is necessary to determine whether it is appropriate for your specific condition.'],
            ['Is spinal decompression the same as an adjustment?', 'No. Chiropractic adjustments use controlled movements to improve joint function, while decompression uses motorized traction to gently stretch targeted areas of the spine. The treatments may be used together when appropriate.'],
        ],
        'related' => 'chiropractic-care,accident-injury-care,sports-medicine',
        'body_areas' => 'neck,lower-back,hip-leg',
        'meta_title' => 'Spinal Decompression Therapy | Non-Surgical Pain Relief',
        'meta_description' => 'Experience non-surgical spinal decompression therapy at Columbia Basin Chiropractic for back pain relief and improved spine health.',
    ],
    [
        'slug' => 'accident-injury-care', 'title' => 'Accident & Injury Care', 'category' => 'chiropractic', 'icon' => 'car', 'featured' => 1,
        'excerpt' => 'Thoughtful evaluation and care following auto accidents, workplace injuries, and other physical trauma.',
        'hero_text' => 'Prompt, non-invasive care for pain, stiffness, whiplash, disc injuries, and movement problems following a motor vehicle accident, workplace injury, or fall.',
        'what_is' => $p(
            'After a car accident, work injury, or fall, adrenaline and stress can temporarily mask pain. Inflammation may build gradually, while soft-tissue and joint injuries can become more noticeable over the following hours or days.',
            'An early examination helps document what happened, identify areas of limited movement or irritation, and determine whether treatment or further medical evaluation is appropriate.'
        ) . $steps([
            ['Impact', 'Sudden force places stress on joints, muscles, ligaments, discs, and nerves.'],
            ['Adrenaline', 'The body’s stress response may temporarily reduce awareness of pain.'],
            ['Inflammation', 'Swelling, stiffness, and tissue irritation may increase over time.'],
            ['Symptoms', 'Pain, headaches, numbness, or movement limits may appear later.'],
        ]),
        'conditions' => "Whiplash and neck injuries\nLower and upper back pain\nHerniated or damaged discs\nHeadaches and migraines\nShoulder and joint pain\nNumbness and tingling\nArm and hand symptoms\nReduced movement and stiffness",
        'how_it_works' => $p('The goal is not only temporary pain relief. Your care plan may address joint restriction, muscle injury, reduced mobility, weakness, and the movement patterns that changed after the accident.') . $cards([
            ['Chiropractic Care', 'Controlled techniques designed to restore joint movement and reduce mechanical restriction.'],
            ['Soft-Tissue Support', 'Care focused on muscle tension, sensitivity, guarding, and tissue recovery.'],
            ['Rehabilitation', 'Progressive exercises to rebuild strength, mobility, control, and stability.'],
            ['Home Guidance', 'Individual recommendations that support recovery between appointments.'],
        ]) . '<h3>Clear documentation and coordinated care</h3>' . $p('For auto accident and workplace injury cases, organized clinical records can help create a clear picture of your symptoms, examination findings, treatment, and progress. This may include worker’s compensation cases and motor vehicle claims. Our team can help ensure that care is documented and coordinated appropriately.')
            . '<div class="callout"><h3>Helpful information to bring</h3><ul><li>Accident date and details</li><li>Insurance or claim information</li><li>Relevant medical records</li><li>Imaging or emergency-room reports</li></ul></div>',
        'benefits' => "Early evaluation\nPersonalized recovery plan\nClinical documentation for claims\nExperience with auto and worker’s compensation cases\nCare that progresses as your body heals",
        'what_to_expect' => $steps([
            ['Accident History', 'We review how the accident happened, the direction of impact, when symptoms began, and how they have changed.'],
            ['Examination', 'Your provider evaluates movement, posture, tenderness, joint function, strength, and relevant neurological findings.'],
            ['Clinical Explanation', 'We explain the findings, discuss whether additional testing is appropriate, and answer your questions.'],
            ['Recovery Plan', 'When appropriate, a care plan is built around your injury, current limitations, comfort, and functional goals.'],
        ]),
        'faqs' => [
            ['Should I be evaluated even if I feel fine?', 'Yes, it can be helpful to be evaluated because adrenaline may initially mask pain and some soft-tissue symptoms can develop later.'],
            ['Why did my pain or headaches start days later?', 'Inflammation and muscle guarding can build gradually. Neck, joint, disc, or soft-tissue injuries may not become fully noticeable immediately after the impact.'],
            ['Why do I have numbness or tingling in my arms or hands?', 'Nerves traveling from the neck into the arms and hands can become irritated after trauma. An evaluation can help determine whether nerve involvement may be contributing.'],
            ['Do you treat workplace injuries and auto accident cases?', 'The clinic commonly works with worker’s compensation and motor vehicle accident cases and can document care and progress.'],
            ['How long will recovery take?', 'Recovery time depends on the type and severity of injury, your history, how quickly care begins, and how your body responds. Recommendations are made after your evaluation.'],
        ],
        'related' => 'chiropractic-care,spinal-decompression,sports-medicine',
        'body_areas' => 'head,neck,shoulder,upper-back,lower-back',
        'meta_title' => 'Accident & Injury Care in Richland, WA | Whiplash & Work Injuries',
        'meta_description' => 'Prompt, non-invasive care for whiplash, back pain and injuries after car accidents, work injuries or falls at Columbia Basin Chiropractic in Richland, WA.',
    ],

    // ================================================================= SPECIALIZED CARE
    [
        'slug' => 'pregnancy-care', 'title' => 'Pregnancy Care', 'category' => 'specialized', 'icon' => 'heart', 'featured' => 1,
        'excerpt' => 'Gentle chiropractic care that may help support comfort and mobility throughout pregnancy.',
        'hero_text' => 'Gentle, pregnancy-specific chiropractic care designed to support comfort, mobility, posture, and healthy movement as your body changes.',
        'what_is' => $p(
            'Pregnancy changes posture, weight distribution, joint movement, and the way the body manages everyday activity. Our approach is gentle, individualized, and adapted to help expecting mothers feel supported and comfortable throughout each stage.',
            'Pregnancy chiropractic care uses modified techniques and comfortable positioning designed around the needs of expecting mothers. The approach is non-invasive and does not involve medication. Your provider considers your stage of pregnancy, symptoms, health history, comfort, and any recommendations from your prenatal care team.'
        ),
        'conditions' => "Lower back pain\nHip discomfort\nPelvic alignment concerns\nNeck and shoulder tension\nPostural changes\nGeneral pregnancy discomfort\nReduced mobility\nMuscle and joint tension",
        'how_it_works' => $p('As pregnancy progresses, increased weight and changes in posture can place additional stress on the lower back, hips, pelvis, neck, and shoulders. Your needs can change throughout the pregnancy journey, so care is adapted as your body, posture, mobility, and comfort change.') . $cards([
            ['Pre-Conception', 'Support movement, strength, balance, and overall physical readiness.'],
            ['Pregnancy', 'Gentle care focused on comfort, mobility, posture, and changing physical demands.'],
            ['Labor & Birth', 'Support comfortable movement and healthy biomechanics of the hips and spine.'],
            ['After Birth', 'Address the physical demands of recovery, feeding positions, carrying, and sleep changes.'],
        ]),
        'benefits' => "Drug-free, non-invasive approach\nPersonalized techniques\nMovement-focused care\nComfort-first positioning\nClear communication at every visit",
        'what_to_expect' => $p('Your comfort and safety remain the priority. Special positioning and support can be used to avoid unnecessary pressure and help you remain comfortable during the visit.') . $steps([
            ['Conversation', 'We discuss your pregnancy stage, symptoms, comfort, health history, and goals.'],
            ['Evaluation', 'Your provider assesses posture, movement, areas of tension, and joint function.'],
            ['Explanation', 'We explain the recommended approach and answer questions before beginning care.'],
            ['Gentle Care', 'Techniques and positioning are modified based on your stage, comfort, and findings.'],
        ]),
        'faqs' => [
            ['Will chiropractic care hurt my baby?', 'Pregnancy-specific care uses modified techniques and positioning designed to keep you comfortable. Your provider will explain the approach and adapt it to your pregnancy stage and needs.'],
            ['Can I lie comfortably during treatment?', 'Yes. Positioning and support are adapted so that you are not required to remain in an uncomfortable position or place unnecessary pressure on the abdomen.'],
            ['When during pregnancy can I receive care?', 'Care can be considered at different stages of pregnancy. Your provider will review your health history, symptoms, and pregnancy stage before recommending an approach.'],
            ['Can care help with lower-back or hip discomfort?', 'Pregnancy chiropractic care may help address lower-back pain, hip discomfort, pelvic movement concerns, postural changes, and muscle tension.'],
            ['Should I speak with my prenatal provider?', 'You are welcome to discuss chiropractic care with your prenatal provider, particularly when you have a high-risk pregnancy, specific restrictions, or questions about your individual medical situation.'],
        ],
        'related' => 'chiropractic-care,nutrition-lifestyle-support,accident-injury-care',
        'body_areas' => 'lower-back,hip-leg',
        'meta_title' => 'Chiropractic Care During Pregnancy | Gentle Care in Richland',
        'meta_description' => 'Experience safe chiropractic care at Columbia Basin Chiropractic to relieve pregnancy-related discomfort and improve overall comfort.',
    ],
    [
        'slug' => 'sports-medicine', 'title' => 'Sports Medicine', 'category' => 'specialized', 'icon' => 'dumbbell', 'featured' => 1,
        'excerpt' => 'Care for active adults and athletes who want to recover, restore motion, and return to what they enjoy.',
        'hero_text' => 'Performance-focused care for athletes and active individuals dealing with pain, restricted movement, sports injuries, or the demands of returning safely to activity.',
        'what_is' => $p(
            'Sports medicine focuses on the evaluation, treatment, rehabilitation, and prevention of musculoskeletal injuries affecting athletes and active individuals. It is not only for professional athletes: it is for anyone who wants to recover from injury, stay active, and move at a higher level.',
            'Dr. Eric Kurtz is recognized as a Certified Chiropractic Sports Practitioner (CCSP). Care may combine chiropractic treatment, movement assessment, rehabilitation equipment, exercise guidance, and strategies intended to support a safer return to activity.'
        ) . $steps([
            ['Evaluate', 'Identify the injured area, movement restrictions, and contributing factors.'],
            ['Restore', 'Improve joint movement, mobility, and comfort through personalized care.'],
            ['Rebuild', 'Develop strength, stability, coordination, and tolerance for activity.'],
            ['Return', 'Progress safely toward practice, exercise, competition, or normal activity.'],
        ]),
        'conditions' => "Neck and back injuries\nShoulder pain and restriction\nKnee and hip discomfort\nAnkle and foot injuries\nMuscle strains and sprains\nJoint stiffness and reduced mobility\nOveruse and repetitive stress injuries\nPerformance-limiting movement problems",
        'how_it_works' => $p('Your care plan is based on your examination, injury history, sport, training schedule, current limitations, and the level of activity you want to return to.') . $cards([
            ['Chiropractic Care', 'Controlled techniques designed to improve joint movement and reduce mechanical restriction.'],
            ['Mobility Work', 'Targeted movement strategies for areas that are stiff, restricted, or compensating.'],
            ['Rehabilitation', 'Progressive exercise using strength, control, coordination, and sports-specific movement.'],
            ['Injury Prevention', 'Guidance intended to reduce recurrence and support better mechanics during activity.'],
        ]) . '<h3>Sports care may also help with</h3><ul><li>Warm-up and movement preparation</li><li>Training-related mobility restrictions</li><li>Recurring pain during activity</li><li>Safe progression after time away</li><li>Improved movement awareness</li><li>Long-term injury prevention planning</li></ul>',
        'benefits' => "Sports injury evaluation\nRehabilitation support with equipment\nReturn-to-play planning\nInjury prevention strategies\nCare from a Certified Chiropractic Sports Practitioner",
        'what_to_expect' => $steps([
            ['Activity History', 'We discuss your sport, training demands, injury history, symptoms, schedule, and goals.'],
            ['Movement Evaluation', 'Your provider evaluates joint movement, mobility, strength, control, and the motions that reproduce symptoms.'],
            ['Clinical Explanation', 'We explain the findings and how they may be affecting pain, performance, or injury risk.'],
            ['Personalized Plan', 'Your plan is developed around recovery, activity demands, treatment needs, and return-to-play goals.'],
        ]),
        'faqs' => [
            ['Do I need to be a competitive athlete?', 'No. Sports medicine care may be appropriate for competitive athletes, recreational athletes, active adults, weekend warriors, and people returning to exercise after injury.'],
            ['Can sports chiropractic help prevent injuries?', 'Care may help identify mobility, strength, control, or movement issues that contribute to recurring strain. Prevention strategies are individualized to the athlete and activity.'],
            ['When can I return to practice or competition?', 'Return timing depends on the injury, healing, movement quality, strength, symptom response, and the demands of your sport. Your provider can help guide a gradual progression.'],
            ['Do you use rehabilitation equipment?', 'Yes. The clinic may use rehabilitation equipment and guided exercise to support healing, recovery, movement, and return to activity.'],
            ['Can care help an old injury that keeps returning?', 'Recurring problems may involve unresolved mobility, strength, control, or workload issues. An evaluation can help identify what may be contributing and whether care is appropriate.'],
        ],
        'related' => 'chiropractic-care,accident-injury-care,spinal-decompression',
        'body_areas' => 'neck,shoulder,lower-back,hip-leg',
        'meta_title' => 'Sports Medicine Care | Injury Prevention & Recovery',
        'meta_description' => 'Get tailored sports medicine care at Columbia Basin Chiropractic Center for injury recovery, prevention, and optimized performance.',
    ],
    [
        'slug' => 'nutrition-lifestyle-support', 'title' => 'Nutrition & Lifestyle Support', 'menu_label' => 'Nutrition & Lifestyle', 'category' => 'specialized', 'icon' => 'leaf', 'featured' => 1,
        'excerpt' => 'Practical guidance to support healthy routines, recovery, and your broader wellness goals.',
        'hero_text' => 'Clear, practical guidance designed to support energy, recovery, healthy habits, and long-term well-being without extreme or restrictive approaches.',
        'what_is' => $p(
            'Nutrition affects far more than what appears on a plate. It can influence energy, recovery, inflammation, daily performance, and how you feel over time. Lifestyle habits, movement, sleep, and everyday choices can also affect long-term well-being.',
            'By combining chiropractic care with practical nutrition and lifestyle support, the clinic takes a broader view of health instead of focusing only on one symptom or painful area. Our goal is to simplify the process and help you make realistic choices that support your health.'
        ) . $cards([
            ['Nutrition', 'Support the body with practical food choices and a more balanced approach.'],
            ['Lifestyle Habits', 'Identify daily patterns that may help or work against your goals.'],
            ['Daily Movement', 'Build activity and movement habits that fit your current ability and routine.'],
            ['Overall Wellness', 'Create a sustainable plan focused on how you want to feel and function.'],
        ]),
        'conditions' => "Energy and recovery goals\nBuilding healthier daily habits\nSupporting recovery alongside chiropractic care\nSupplement questions\nIdentifying dietary triggers\nLong-term wellness planning",
        'how_it_works' => $p('Nutrition information can feel confusing because advice is often conflicting, overly complicated, or disconnected from real life. The goal here is to make the process clearer and more practical.') . $cards([
            ['Nutrition Recommendations', 'Suggestions tailored to your goals, needs, preferences, and daily routine.'],
            ['Lifestyle Adjustments', 'Realistic changes involving habits, movement, recovery, and daily structure.'],
            ['Supplement Guidance', 'Education and recommendations when supplements may be appropriate.'],
            ['Health Education', 'A clearer understanding of how everyday choices may affect how you feel.'],
        ]) . '<h3>Assessment tools may include</h3><ul><li>Blood analysis</li><li>Hair analysis</li><li>Structured health assessments of symptoms, habits, history, goals, and areas of concern</li></ul>' . $p('These tools are considered as part of a broader evaluation and are not a replacement for appropriate medical diagnosis or treatment.'),
        'benefits' => "Personalized guidance\nPractical daily changes\nSustainable planning, not extreme diets\nFits your real schedule\nComplements chiropractic care",
        'what_to_expect' => $steps([
            ['Goals & History', 'We discuss your concerns, goals, current habits, health history, and what you have already tried.'],
            ['Current Routine', 'Your provider reviews how food, movement, sleep, work, and daily responsibilities fit together.'],
            ['Priorities', 'We identify the areas most likely to support meaningful, realistic improvement.'],
            ['Personalized Guidance', 'You receive clear recommendations designed around your needs, goals, and lifestyle.'],
        ]) . $p('Consider bringing a list of your goals, current supplements, medications, and questions about your routine.'),
        'faqs' => [
            ['Will I be placed on an extreme or restrictive diet?', 'The clinic’s approach emphasizes realistic and sustainable changes rather than extreme or highly restrictive plans.'],
            ['Is the guidance personalized?', 'Yes. Recommendations may be tailored to your goals, current routine, health concerns, preferences, and ability to maintain the plan over time.'],
            ['Do you recommend supplements?', 'Supplement guidance may be discussed when appropriate. Recommendations should be based on your individual situation and broader health plan.'],
            ['What types of assessments may be used?', 'Blood analysis, hair analysis, and health assessments are possible tools used to better understand areas where additional support may be needed.'],
            ['Can nutrition support be combined with chiropractic care?', 'Yes. Nutrition and lifestyle support is part of a more complete approach that can complement chiropractic care, movement, and overall wellness.'],
        ],
        'related' => 'chiropractic-care,sports-medicine,pregnancy-care',
        'body_areas' => 'whole-body',
        'meta_title' => 'Nutrition & Lifestyle Support | Columbia Basin Chiropractic',
        'meta_description' => 'Personalized, realistic nutrition and lifestyle guidance in Richland, WA to support energy, recovery and long-term wellness alongside chiropractic care.',
    ],

    // ================================================================= CONDITIONS (served at /<slug>/)
    [
        'slug' => 'lower-back-pain', 'title' => 'Lower Back Pain', 'category' => 'conditions', 'icon' => 'spine',
        'excerpt' => 'Find the cause—not only the symptom. Thorough evaluation and non-invasive care for lower back pain.',
        'hero_text' => 'A thorough, non-invasive approach focused on identifying what is causing your pain, restoring movement, and helping you return to daily life with greater comfort.',
        'what_is' => $p(
            'Lower back pain can make sitting, standing, sleeping, lifting, working, exercising, and even simple daily movement feel difficult. It may begin suddenly after lifting, bending, twisting, falling, or being involved in an accident. It may also build gradually from repetitive strain, poor movement patterns, prolonged sitting, physical work, disc changes, or unresolved injury.',
            'Because several structures can create similar symptoms, Columbia Basin Chiropractic begins with a thorough examination and diagnostic assessment before recommending care.'
        ),
        'conditions' => "Pulled or strained muscles\nHerniated or bulging discs\nIncorrect lifting or bending\nAccident or traumatic injury\nJoint restriction or irritation\nRepetitive physical overuse\nPoor posture or prolonged sitting\nDegenerative spinal changes\nSciatica or nerve irritation\nPain after previous back surgery",
        'benefits' => "Dull, aching lower back pain\nSharp pain with movement\nStiffness after sitting or sleeping\nPain into the buttock or leg\nMuscle tightness or spasm\nNumbness or tingling\nDifficulty bending or standing\nPain that keeps returning",
        'how_it_works' => $warn('Seek prompt medical attention for serious warning signs', [
            ['Loss of bladder or bowel control', 'New loss of control or numbness around the groin requires urgent evaluation'],
            ['Severe weakness or progressive numbness', 'Rapidly worsening neurological symptoms should be assessed promptly'],
            ['Major trauma, fever, or unexplained illness', 'Back pain with serious injury, fever, or systemic symptoms may need immediate medical care'],
        ]) . $p('Once the likely source of pain is identified, a personalized strategy may include one or more non-invasive treatment options.') . $cards([
            ['Chiropractic Care', 'Controlled treatment focused on joint movement, alignment, mobility, and mechanical restriction.'],
            ['Spinal Decompression', 'Specialized traction that may help certain disc-related and nerve-related lower back conditions.'],
            ['Rehabilitation', 'Guided exercises designed to rebuild mobility, strength, stability, and confidence.'],
            ['Soft-Tissue Support', 'Care focused on muscle tension, spasm, guarding, and tissue irritation.'],
        ]),
        'what_to_expect' => $steps([
            ['History', 'We discuss when the pain began, what affects it, previous injuries, treatment, work, and daily activity.'],
            ['Examination', 'Your provider evaluates posture, movement, tenderness, joint function, strength, and neurological findings.'],
            ['Diagnosis & Explanation', 'We explain what may be contributing and whether additional testing or imaging is appropriate.'],
            ['Personalized Plan', 'A non-invasive treatment and recovery strategy is developed when appropriate.'],
        ]),
        'faqs' => [
            ['What is causing my lower back pain?', 'Common causes include muscle strain, joint restriction, disc injury, nerve irritation, repetitive use, posture, accidents, or degenerative changes. An examination is needed to determine what may be involved in your case.'],
            ['Can chiropractic care help lower back pain?', 'Chiropractic care may help certain mechanical and musculoskeletal causes of lower back pain. The appropriate approach depends on your examination and diagnosis.'],
            ['Could my pain be coming from a disc?', 'Disc problems can cause localized back pain or symptoms into the hip or leg. Your provider may review your pain pattern, neurological findings, and imaging when relevant.'],
            ['Is spinal decompression always recommended?', 'No. Spinal decompression is intended for selected conditions and is recommended only when the evaluation suggests it may be appropriate.'],
            ['What should I bring to my appointment?', 'Bring relevant imaging, medical records, medication information, and details about prior injuries, surgery, or treatments when available.'],
        ],
        'related' => 'chiropractic-care,spinal-decompression,accident-injury-care',
        'body_areas' => 'lower-back',
        'meta_title' => 'Lower Back Pain Treatment in Richland, WA | Chiropractic Care',
        'meta_description' => 'Non-invasive lower back pain care in Richland, WA: a thorough evaluation to find the cause, then chiropractic care, decompression and rehab when appropriate.',
    ],
    [
        'slug' => 'upper-back-pain', 'title' => 'Upper Back Pain', 'category' => 'conditions', 'icon' => 'bone',
        'excerpt' => 'A thorough diagnostic assessment to find the root cause of back pain, then a non-invasive plan for relief.',
        'hero_text' => 'If you’re suffering from back pain in Richland, Washington, a thorough diagnostic assessment helps determine the root cause so the correct course of treatment can be chosen for your unique case.',
        'what_is' => $p(
            'Back pain can stem from a variety of causes. It is often caused by a pulled muscle or damaged disc, but can also result from a trauma like an accident or injury, or even something simple like lifting or bending incorrectly.',
            'At Columbia Basin Chiropractic, our team first works to provide each patient with a thorough examination and diagnostic assessment to find the cause of the pain. Embracing an integrated approach to wellness, we work hard to help people live more healthful, productive, and active lives.'
        ),
        'conditions' => "Pulled or strained muscles\nDamaged or irritated discs\nAccident or injury\nIncorrect lifting or bending\nSimple overuse\nPosture and prolonged sitting",
        'benefits' => "Aching or stiffness between the shoulder blades\nPain with turning or reaching\nMuscle tightness or spasm\nPain that keeps returning",
        'how_it_works' => $p(
            'Once the source of the pain has been determined, a strategy is created to provide relief using non-invasive treatments like chiropractic care, therapeutic exercises, massage therapy, physical rehabilitation, and more.',
            'Back pain relief has traditionally been provided using rest, hot and cold packs, pain medication, muscle relaxants, and massage therapy. When these fail, patients can be left to struggle with chronic pain. Our goal is to find and address what is driving your pain so you can move and feel better.'
        ) . $cards([
            ['Chiropractic Care', 'Controlled treatment focused on joint movement, mobility, and mechanical restriction.'],
            ['Therapeutic Exercise', 'Exercises to rebuild mobility, posture, strength, and stability.'],
            ['Massage Therapy', 'Hands-on care to release muscle tension and guarding.'],
            ['Physical Rehabilitation', 'Progressive rehab to support lasting function.'],
        ]),
        'what_to_expect' => $steps([
            ['History', 'We discuss when the pain began, what affects it, previous injuries, and daily activity.'],
            ['Examination', 'A thorough examination and diagnostic assessment of the spine and surrounding tissues.'],
            ['Explanation', 'We explain what may be contributing and the options that may help.'],
            ['Personalized Plan', 'A non-invasive treatment strategy is developed when appropriate.'],
        ]),
        'faqs' => [],
        'related' => 'chiropractic-care,accident-injury-care,sports-medicine',
        'body_areas' => 'upper-back',
        'meta_title' => 'Upper Back Pain Treatment | Columbia Basin Chiropractic',
        'meta_description' => 'Thorough evaluation and non-invasive care for upper back pain in Richland, WA, including chiropractic care, exercise, massage and rehabilitation.',
    ],
    [
        'slug' => 'neck-pain', 'title' => 'Neck Pain', 'category' => 'conditions', 'icon' => 'user',
        'excerpt' => 'Find the source of neck pain, improve mobility, and move comfortably again—without surgery or medication.',
        'hero_text' => 'A thorough, non-invasive approach focused on identifying the source of neck pain, improving mobility, reducing irritation, and helping you move comfortably again.',
        'what_is' => $p(
            'Neck pain can make sitting, sleeping, working, driving, and even simple daily movement feel difficult. It may begin suddenly after an accident or awkward movement, or build gradually from repetitive strain, poor posture, prolonged screen use, physical work, disc changes, or unresolved injury.',
            'Because several structures can create similar symptoms, Columbia Basin Chiropractic begins with a focused cervical examination and diagnostic assessment before recommending care.'
        ),
        'conditions' => "Muscle strain and tension\nHerniated or bulging cervical discs\nPoor posture or prolonged screen use\nWhiplash or accident injury\nCervical joint restriction or irritation\nRepetitive work or overuse\nChanges in the neck’s natural curve\nArthritis and disc degeneration\nCervical nerve root irritation\nStress-related muscle guarding",
        'benefits' => "Dull, aching neck pain\nSharp pain when turning the head\nStiffness after sleeping or screen use\nPain into the shoulder, arm, or hand\nMuscle tightness or spasm\nNumbness or tingling in the arm or hand\nDifficulty rotating or tilting the head\nHeadaches or recurring neck pain",
        'how_it_works' => $warn('Seek prompt medical attention for serious warning signs', [
            ['Severe trauma or suspected fracture', 'Major accidents, falls, or direct neck injury may require immediate medical imaging and stabilization'],
            ['Progressive weakness or loss of coordination', 'Worsening arm weakness, balance problems, or difficulty using the hands should be assessed promptly'],
            ['Fever, severe headache, or systemic illness', 'Neck pain with fever, confusion, or severe illness may indicate a condition needing urgent care'],
        ]) . $p('Once the likely source of neck pain is identified, a personalized strategy may include one or more non-invasive treatment options.') . $cards([
            ['Chiropractic Care', 'Controlled treatment focused on cervical joint movement, mobility, and mechanical restriction.'],
            ['Spinal Decompression', 'Specialized traction that may help selected cervical disc and nerve-related conditions.'],
            ['Rehabilitation', 'Guided exercises designed to improve posture, endurance, neck strength, control, and confidence.'],
            ['Soft-Tissue Support', 'Care focused on muscle tension, guarding, spasm, and irritation around the neck and shoulders.'],
        ]),
        'what_to_expect' => $steps([
            ['History', 'We discuss when the pain began, injuries, posture, work, headaches, arm symptoms, and previous treatment.'],
            ['Examination', 'Your provider evaluates posture, neck movement, tenderness, cervical joint function, strength, and neurological findings.'],
            ['Diagnosis & Explanation', 'We explain what may be contributing and whether additional testing or imaging is appropriate.'],
            ['Personalized Plan', 'A non-invasive treatment and recovery strategy is developed when appropriate.'],
        ]),
        'faqs' => [
            ['What is causing my neck pain?', 'Common causes include muscle tension, poor posture, whiplash, joint restriction, arthritis, disc degeneration, and irritation of cervical nerves. An examination is needed to determine what may be involved in your case.'],
            ['Can chiropractic care help neck pain?', 'Chiropractic care may help certain mechanical and musculoskeletal causes of neck pain. The appropriate approach depends on your examination and diagnosis.'],
            ['Can neck pain cause headaches?', 'Neck joints, muscles, and nerves may contribute to headaches, especially when pain begins near the base of the skull or is associated with stiffness and posture.'],
            ['Why is pain traveling into my arm or hand?', 'Radiating pain, numbness, or tingling may involve irritation of a cervical nerve root. An examination can help determine whether nerve involvement is present.'],
            ['Should I wait until the pain becomes severe?', 'It is generally better to have persistent or recurring neck pain evaluated before it becomes a crisis or causes greater limitation.'],
        ],
        'related' => 'chiropractic-care,spinal-decompression,accident-injury-care',
        'body_areas' => 'neck',
        'meta_title' => 'Neck Pain Treatment | Chiropractic Care for Lasting Relief',
        'meta_description' => 'Find lasting relief from neck pain with chiropractic care at Columbia Basin Chiropractic. Restore mobility without surgery or medication.',
    ],
    [
        'slug' => 'sciatica', 'title' => 'Sciatica', 'category' => 'conditions', 'icon' => 'zap',
        'excerpt' => 'Identify what is irritating the sciatic nerve, relieve leg pain, and restore mobility with non-invasive care.',
        'hero_text' => 'Non-invasive care focused on identifying what is irritating the sciatic nerve, relieving leg pain, restoring mobility, and helping you move confidently again.',
        'what_is' => $p(
            'Sciatica is more than ordinary leg pain. It is a pattern of symptoms caused by irritation or compression affecting the sciatic nerve. The right treatment depends on whether the nerve is being affected by a disc problem, joint change, bone spur, injury, inflammation, or another underlying condition.',
            'The sciatic nerve begins in the lower spine, passes through the hips and buttocks, and continues down each leg. When a nerve root becomes compressed or irritated, symptoms may radiate through this pathway.'
        ) . $steps([
            ['Lower Spine', 'Irritation often begins near lumbar discs or spinal joints.'],
            ['Hip & Buttock', 'Pain may feel deep, sharp, burning, or aching.'],
            ['Thigh & Calf', 'Symptoms may travel along the back or side of the leg.'],
            ['Foot & Toes', 'Numbness, tingling, or weakness may extend downward.'],
        ]),
        'conditions' => "Herniated or bulging lumbar disc\nBone spur or arthritic change\nSpinal stenosis\nDegenerative disc changes\nAccident or traumatic injury\nJoint restriction and inflammation\nRepetitive lifting or strain\nPregnancy-related pressure",
        'benefits' => "Radiating leg pain\nTingling or numbness\nBurning sensations\nMuscle weakness\nHip or buttock pain\nDifficulty sleeping\nLoss of mobility\nPain worsened by sitting",
        'how_it_works' => $warn('Seek urgent medical attention for severe neurological symptoms', [
            ['Loss of bladder or bowel control', 'New loss of control or groin numbness requires immediate evaluation'],
            ['Rapidly worsening weakness', 'Progressive weakness or foot drop should be assessed promptly'],
            ['Symptoms in both legs', 'Severe bilateral symptoms may need urgent care'],
        ]) . $cards([
            ['Chiropractic Care', 'Controlled treatment focused on spinal movement and mechanical irritation.'],
            ['Spinal Decompression', 'Specialized traction for selected lumbar disc and nerve-root conditions.'],
            ['Physical Rehabilitation', 'Guided mobility, strength, and stability work.'],
            ['Soft-Tissue Support', 'Care to reduce guarding, tension, and surrounding irritation.'],
        ]),
        'what_to_expect' => $steps([
            ['Symptom History', 'We review where the pain begins, how far it travels, and what worsens it.'],
            ['Neurological Examination', 'Strength, sensation, reflexes, movement, and nerve tension are assessed.'],
            ['Diagnostic Assessment', 'We explain the likely cause and whether imaging is appropriate.'],
            ['Customized Plan', 'A personalized non-invasive recovery plan is recommended when appropriate.'],
        ]),
        'faqs' => [
            ['What does sciatica feel like?', 'It may feel sharp, burning, electric, aching, tingling, or numb and often travels down one leg.'],
            ['What causes sciatic nerve pain?', 'Common causes include herniated discs, bone spurs, stenosis, degeneration, inflammation, and injury.'],
            ['Can chiropractic care help?', 'It may help selected mechanical causes of sciatica based on the examination findings.'],
            ['Is spinal decompression appropriate?', 'It may be considered when disc pressure is contributing, but it is not appropriate for everyone.'],
            ['Why does sitting make it worse?', 'Sitting may increase pressure in the lower spine and tension irritated tissues or nerves.'],
        ],
        'related' => 'spinal-decompression,chiropractic-care,accident-injury-care',
        'body_areas' => 'lower-back,hip-leg',
        'meta_title' => 'Sciatica Treatment in Richland, WA | Non-Invasive Care',
        'meta_description' => 'Relief for sciatic nerve pain in Richland, WA: a thorough nerve evaluation and personalized, non-invasive care including chiropractic and decompression.',
    ],
    [
        'slug' => 'herniated-slipped-discs', 'title' => 'Herniated / Slipped Discs', 'menu_label' => 'Herniated Discs', 'category' => 'conditions', 'icon' => 'circle-dot',
        'excerpt' => 'Identify disc damage, reduce nerve irritation, and restore movement with non-surgical care options.',
        'hero_text' => 'Advanced, non-invasive care focused on identifying disc damage, reducing nerve irritation, restoring movement, and helping you return to daily life with greater comfort.',
        'what_is' => $p(
            'Spinal discs sit between the vertebrae and help absorb force while allowing the spine to move. Each disc has a durable outer layer surrounding a softer, jelly-like center.',
            'A herniated or ruptured disc occurs when part of that softer center pushes through a weakened area or tear in the exterior. The disc does not literally slip out of place, but “slipped disc” is a common term used for this condition. Disc problems may affect the cervical spine in the neck or the lumbar spine in the lower back.'
        ) . $steps([
            ['Healthy Disc', 'The center remains contained and the disc maintains normal cushioning and spacing.'],
            ['Bulging Disc', 'The outer disc extends outward while the softer center remains contained.'],
            ['Herniated Disc', 'Inner disc material pushes through a tear or weakened outer layer.'],
            ['Nerve Compression', 'Disc material may contact or irritate a nearby nerve root.'],
        ]) . '<blockquote>Many age-related disc changes appear on imaging without causing major symptoms. Treatment should be based on the complete clinical picture rather than imaging alone.</blockquote>',
        'conditions' => "Age-related disc degeneration\nImproper lifting or bending\nFall, collision, or direct trauma\nRepeated twisting or physical strain\nPhysically demanding work\nProlonged sitting or poor mechanics\nReduced core strength and stability\nPrevious spinal injury",
        'benefits' => "Localized neck or lower back pain\nPain into an arm or leg\nSciatica\nNumbness or tingling\nMuscle weakness\nPain worsened by sitting or bending\nReduced spinal movement\nPain with coughing or sneezing",
        'how_it_works' => $warn('Seek urgent medical attention for severe neurological warning signs', [
            ['Bladder or bowel changes', 'New loss of control or groin numbness requires emergency evaluation'],
            ['Progressive weakness', 'Rapidly worsening weakness, foot drop, or loss of hand function should be assessed promptly'],
            ['Severe trauma or widespread symptoms', 'Major injury, symptoms in both legs, or loss of coordination may require urgent care'],
        ]) . $p('Disc-related care is personalized according to the location and severity of the problem, neurological findings, imaging, health history, and response to treatment.') . $cards([
            ['Spinal Decompression', 'Controlled, non-surgical traction intended to reduce pressure affecting selected discs and nerve roots.'],
            ['Chiropractic Care', 'Care focused on spinal movement, mechanical restriction, and surrounding joint function when appropriate.'],
            ['Physical Rehabilitation', 'Progressive mobility, strength, stability, and movement work designed to support the spine.'],
            ['Soft-Tissue Support', 'Care addressing guarding, spasm, and muscle tension around an injured disc.'],
        ]),
        'what_to_expect' => $steps([
            ['Symptom History', 'We review when symptoms began, where they travel, what worsens them, and previous treatment.'],
            ['Physical & Neurological Exam', 'Your provider assesses movement, strength, sensation, reflexes, and nerve-related findings.'],
            ['Imaging Review', 'Existing X-rays, MRI, or other records may be reviewed, and additional imaging may be recommended.'],
            ['Personalized Plan', 'When conservative care is appropriate, a treatment and rehabilitation strategy is built around your condition.'],
        ]),
        'faqs' => [
            ['Is a slipped disc actually out of place?', 'No. “Slipped disc” is a common term, but the disc does not literally slide out. It usually refers to a bulging, herniated, or ruptured disc.'],
            ['Can a herniated disc improve without surgery?', 'Many disc conditions are initially managed with conservative care. Whether that is appropriate depends on severity, neurological findings, and response to treatment.'],
            ['What symptoms suggest nerve involvement?', 'Radiating pain, numbness, tingling, weakness, sciatica, and changes in reflexes may indicate nerve-root irritation.'],
            ['Is spinal decompression appropriate for every disc?', 'No. Spinal decompression is intended for selected conditions and requires an evaluation first.'],
            ['Do I need an MRI before scheduling?', 'Not every patient needs an MRI before the first visit. Bring existing imaging if available, and the provider can determine whether more is needed.'],
            ['Why can a herniated disc cause sciatica?', 'A lumbar disc may irritate a nerve root that forms the sciatic nerve, creating pain, tingling, numbness, or weakness into the leg.'],
        ],
        'related' => 'spinal-decompression,chiropractic-care,sciatica',
        'body_areas' => 'neck,lower-back',
        'meta_title' => 'Herniated & Slipped Disc Treatment | Non-Surgical Care',
        'meta_description' => 'Non-surgical care for herniated and slipped discs in Richland, WA: detailed disc evaluation, spinal decompression, chiropractic care and rehabilitation.',
    ],
    [
        'slug' => 'headaches-migraines', 'title' => 'Headaches & Migraines', 'category' => 'conditions', 'icon' => 'radio',
        'excerpt' => 'Find the root cause of tension headaches, migraines and cluster headaches—and reduce how often they happen.',
        'hero_text' => 'Non-invasive care designed to identify the root cause of tension headaches, migraines, and cluster headaches—not just manage the pain, but reduce how often and how severely they happen.',
        'what_is' => $p(
            'While an occasional headache may be easy enough to manage with over-the-counter medication, chronic headaches and migraines often need a deeper look at what is actually causing them.',
            'Headaches can be caused by many contributing factors, including stress, environment, illness or disease, poor posture, and food allergies or sensitivities. Because the causes vary so widely, an effective plan starts with a comprehensive diagnostic assessment rather than guesswork. Adjustments of the vertebrae in the neck and upper back can be especially helpful for tension headaches, particularly those that stem from the neck area.'
        ) . $cards([
            ['Tension', 'A dull ache on one or both sides of the head, or a tight-band feeling, often caused by stress in the spine and muscles of the neck and back. Can last minutes to days.'],
            ['Migraine', 'Intense, throbbing pain with nausea, light and noise sensitivity, and sometimes visual symptoms like flashing lights.'],
            ['Cluster', 'Usually one-sided, behind the eyes, occurring one or more times a day for days at a time, then disappearing for months or years. Often at night.'],
        ]),
        'conditions' => "Stress in the neck and upper back\nPoor posture\nEnvironmental factors\nIllness or underlying disease\nFood allergies or sensitivities\nGenetic predisposition (migraines)\nChanges in brain blood flow\nSpinal subluxation",
        'benefits' => "Pressure or a tight band around the head\nThrobbing, one-sided pain\nSensitivity to light or noise\nNausea with headaches\nPain starting at the base of the skull\nNeck stiffness with headaches",
        'how_it_works' => $p('Headache treatment incorporates a range of non-invasive procedures designed to reduce both frequency and intensity, not just mask symptoms. Because identifying dietary triggers is a big part of treating migraines, nutritional counseling is included in our approach.') . $cards([
            ['Spinal Adjustment', 'Gentle manipulation of the neck and upper back to correct subluxations linked to tension headaches.'],
            ['Trigger Point Therapy', 'Targets tight, tender muscle points in the neck and shoulders that can refer pain into the head.'],
            ['Massage Therapy', 'Helps release muscular tension that contributes to both tension headaches and migraines.'],
            ['Nutritional Counseling', 'Helps identify dietary triggers that may be driving migraine frequency and severity.'],
        ]),
        'what_to_expect' => $steps([
            ['Symptom History', 'We review headache frequency, location, duration, and any known triggers.'],
            ['Comprehensive Assessment', 'A diagnostic assessment of the neck, spine, and posture to identify contributing factors.'],
            ['Trigger Review', 'Dietary, environmental, and lifestyle factors are discussed as part of nutritional counseling when relevant.'],
            ['Personalized Plan', 'A plan combining adjustment, massage, trigger point therapy, or nutritional counseling is built around your findings.'],
        ]),
        'faqs' => [
            ['Can a tension headache really come from my neck?', 'Yes. Tension headaches are often caused by stress carried in the spine and muscles of the neck and back.'],
            ['How is migraine treatment different from tension headache treatment?', 'Migraine care often includes nutritional counseling to identify dietary triggers, alongside chiropractic adjustment, massage, and trigger point therapy.'],
            ['What makes cluster headaches different?', 'Cluster headaches are usually one-sided and occur behind the eyes, happening in clusters over days before disappearing for months or years, often at night.'],
            ['Are spinal adjustments safe for headache treatment?', 'Adjustments use gentle manipulation to realign the spine and are a common, non-invasive approach for headaches linked to the neck and upper back.'],
            ['Do I need to know my headache type before booking?', 'No. Part of your first visit is a diagnostic assessment to help identify which type of headache you’re experiencing and what’s driving it.'],
            ['Can diet really affect migraines?', 'Yes. Identifying dietary triggers is a significant part of migraine treatment, which is why nutritional counseling is included in our approach.'],
        ],
        'related' => 'chiropractic-care,nutrition-lifestyle-support,neck-pain',
        'body_areas' => 'head,neck',
        'meta_title' => 'Headache & Migraine Relief | Chiropractic Care in Richland',
        'meta_description' => 'Get relief from chronic headaches and migraines with chiropractic care. We treat the root causes to reduce frequency and intensity.',
    ],
    [
        'slug' => 'shoulder-pain', 'title' => 'Shoulder Pain', 'category' => 'conditions', 'icon' => 'accessibility',
        'excerpt' => 'Shoulder pain doesn’t always start in the shoulder. We evaluate the joint, soft tissue and neck to find the source.',
        'hero_text' => 'Comprehensive, non-invasive care focused on identifying the source of shoulder pain, calming irritated nerves and soft tissue, and restoring movement so you can get back to daily activities.',
        'what_is' => $p(
            'The shoulder is the most mobile joint in the body, which also makes it one of the most vulnerable to injury, overuse, and degeneration. Pain can come from the joint itself, the rotator cuff and surrounding soft tissue, or from nerve irritation that begins elsewhere.',
            'Many shoulder issues are connected to the brachial plexus, a network of nerves that runs from the lower neck through the upper shoulder and controls movement and sensation in the shoulder, arm, and wrist. When these nerves are irritated near the neck, the pain is often felt in the shoulder—so an evaluation for shoulder pain typically includes a look at the neck as well as the shoulder joint itself.'
        ),
        'conditions' => "Arthritis in the shoulder joint\nPrevious injury or trauma\nRotator cuff strain or tearing\nRepetitive overhead movement\nReferred pain from the neck\nPoor posture or prolonged sitting\nMuscle imbalance or weakness\nFrozen shoulder / joint stiffness",
        'benefits' => "Decreased ability to hold objects\nReduced arm function or strength\nPain that persists even at rest\nPain lasting more than a day or two\nUnusual twinges or aches\nStiffness or reduced range of motion\nNumbness or tingling into the arm\nPain that worsens with overhead reaching",
        'how_it_works' => $warn('Some symptoms warrant prompt evaluation', [
            ['Sudden, severe pain', 'Especially after a fall, collision, or direct trauma to the shoulder'],
            ['Progressive weakness', 'Ongoing loss of strength or function in the arm or hand should be assessed promptly'],
            ['Numbness spreading down the arm', 'May indicate nerve involvement originating at the neck'],
        ]) . $p('Because shoulder pain can begin in the neck, the joint, or the soft tissue, our doctors begin with an exam of the neck area as well as the shoulder itself. Care is then personalized to the findings.') . $cards([
            ['Chiropractic Manipulation', 'Gentle, targeted adjustment intended to help the shoulder and related joints return toward normal function.'],
            ['Physical Therapy', 'Guided exercises to rebuild strength, stability, and healthy movement patterns around the shoulder.'],
            ['Heat & Ice Application', 'Used to help manage pain and inflammation as part of an active treatment plan.'],
            ['Stretching & Strengthening', 'Take-home exercises designed to support recovery between visits.'],
        ]),
        'what_to_expect' => $steps([
            ['Symptom History', 'We review when the pain began, what makes it better or worse, and any prior injuries or treatment.'],
            ['Neck & Shoulder Exam', 'Since shoulder pain often begins in the neck, we examine both areas along with strength, motion, and reflexes.'],
            ['Imaging Review', 'Existing imaging may be reviewed, and additional imaging may be recommended when appropriate.'],
            ['Personalized Plan', 'A treatment plan combining chiropractic, physical therapy, and at-home exercise is built around your findings.'],
        ]),
        'faqs' => [
            ['Why does my shoulder pain seem connected to my neck?', 'The brachial plexus nerves run from the lower neck through the shoulder. Irritation near the neck can be felt as shoulder pain, which is why we evaluate both areas.'],
            ['Can shoulder pain improve without surgery?', 'Many cases of shoulder pain respond to conservative care such as chiropractic treatment, physical therapy, and targeted exercise, depending on severity and findings.'],
            ['What symptoms suggest nerve involvement?', 'Numbness, tingling, or weakness traveling down the arm can indicate nerve irritation rather than a purely local shoulder issue.'],
            ['Is a herniated disc ever related to shoulder pain?', 'Yes. A herniated or bulging disc in the neck can irritate a nerve that refers pain into the shoulder or arm.'],
            ['Do I need imaging before my first visit?', 'Not always. Bring any existing imaging you have, and the provider will determine whether more is needed after the exam.'],
            ['How long does treatment typically take?', 'Timelines vary by severity and cause. Your provider will outline an expected plan and timeframe after your evaluation.'],
        ],
        'related' => 'chiropractic-care,sports-medicine,neck-pain',
        'body_areas' => 'shoulder',
        'meta_title' => 'Shoulder Pain Treatment | Chiropractic Care for Mobility',
        'meta_description' => 'Get effective shoulder pain relief with chiropractic care at Columbia Basin Chiropractic. Improve mobility and reduce discomfort for daily activities.',
    ],
    [
        'slug' => 'scoliosis', 'title' => 'Scoliosis', 'category' => 'conditions', 'icon' => 'activity',
        'excerpt' => 'Scoliosis is an abnormal sideways curve of the spine. Early detection and a natural approach can help.',
        'hero_text' => 'Scoliosis is an abnormal sideways curvature of the spine. It begins as a postural distortion and may become a permanent deformity—so early detection can be helpful.',
        'what_is' => $p(
            'Scoliosis is an abnormal sideways curvature of the spine. It begins as a postural distortion and may become a permanent deformity. Allowed to advance, it may interfere with the lungs, heart and other vital organs.',
            'An alert parent may notice that a high shoulder or low hip make clothing fit poorly, or uneven shoe wear offers a clue. Back and leg pains may develop but are often dismissed as “growing pains.”'
        ),
        'conditions' => "Congenital (genetic predisposition)\nHabitual (behavioral routines)\nIdiopathic (unknown cause)",
        'benefits' => "A high shoulder or low hip\nClothing that fits unevenly\nUneven shoe wear\nBack or leg pain",
        'how_it_works' => $p(
            'Allowed to run its course, scoliosis may often worsen. Severe cases may involve bracing or surgery that attaches steel rods to force the spine to straighten.',
            'A schedule of specific chiropractic adjustments may help improve the function and structure of the spine. As muscles are retrained and strengthened, posture may improve. Bring your child in so we can discuss your concerns and explore the possibilities.'
        ),
        'what_to_expect' => $steps([
            ['Conversation', 'We discuss your concerns, what you have noticed, and your child’s health history.'],
            ['Examination', 'Your provider evaluates posture, spinal curves, and movement.'],
            ['Explanation', 'We explain the findings and the options that may be appropriate.'],
            ['Care Plan', 'When appropriate, a schedule of specific adjustments and exercises is recommended.'],
        ]),
        'faqs' => [
            ['Isn’t a certain amount of sideways curvature normal?', 'Any type of sideways curvature is abnormal. From the back, the spine should appear straight. When scoliosis is present, there are usually two curves: a primary curve in one direction and a compensatory curve in the opposite direction.'],
            ['Isn’t scoliosis caused by a lack of calcium?', 'No. However, a growing number of people believe uncorrected spinal damage from the birth process may be involved. Some theorize that “walkers” may prematurely interrupt the crawling stage so essential for proper spinal development.'],
            ['Do heavy backpacks cause scoliosis?', 'Not necessarily, however this is a growing concern. Make sure your son or daughter uses both shoulder straps so the weight is evenly distributed, and weigh their bag: it shouldn’t exceed 10–15% of their body weight.'],
        ],
        'related' => 'chiropractic-care,upper-back-pain,lower-back-pain',
        'body_areas' => 'upper-back,lower-back',
        'meta_title' => 'Scoliosis Care in Richland, WA | Columbia Basin Chiropractic',
        'meta_description' => 'Early detection and natural chiropractic care for scoliosis in Richland, WA. Learn the causes, the clues to watch for, and how care may help.',
    ],
    [
        'slug' => 'stress', 'title' => 'Stress & Tension', 'category' => 'conditions', 'icon' => 'wind',
        'excerpt' => 'Stress is physical. Chiropractic care can help relieve the tension and stiffness building in your body.',
        'hero_text' => 'We use chiropractic care to help relieve the tension and stiffness that may be building in your body. The better you feel, the easier it is to cope with stress.',
        'what_is' => $p(
            'Studies have shown that levels of stress have risen nationwide. Stress is physical: your body and mind are attuned to each other. If you are mentally stressed, that can make you physically stressed.',
            'Each person handles stress differently, whether it is an upset stomach, inability to sleep or back pain—the physical aspects of stress are real.'
        ),
        'conditions' => "Work and life pressures\nPoor sleep\nPosture and prolonged sitting\nMuscle tension and guarding",
        'benefits' => "Tight neck and shoulders\nTension headaches\nBack pain or stiffness\nTrouble sleeping\nUpset stomach",
        'how_it_works' => $p(
            'Chiropractic care can be an excellent tool in dealing with the stress of today’s world. It provides an optimal first step toward dealing with the physical issues: relieving tension and stiffness so your body can function more fully.',
            'The better you feel, the easier it is to cope with stress—which in turn helps your body function more fully.'
        ),
        'what_to_expect' => '',
        'faqs' => [],
        'related' => 'chiropractic-care,nutrition-lifestyle-support,headaches-migraines',
        'body_areas' => 'head,whole-body',
        'meta_title' => 'Chiropractic Stress Relief | Columbia Basin Chiropractic',
        'meta_description' => 'Reduce stress and tension with chiropractic care at Columbia Basin Chiropractic. Relax, feel better, and manage stress more effectively.',
    ],
    [
        'slug' => 'subluxations', 'title' => 'Subluxations', 'category' => 'conditions', 'icon' => 'target',
        'excerpt' => 'Spinal bones with improper motion or position can affect communication between your brain and body.',
        'hero_text' => 'A subluxation is the result of spinal bones with improper motion or position affecting nerve communications between your brain and your body.',
        'what_is' => $p(
            'A subluxation is a stress response. Muscles go into spasm. Spinal bones lock up. And adjacent nerves are choked or chafed. This interferes with the control and regulation of your body and garbles communications between the brain and parts of your body.',
            'Your nervous system controls every cell, tissue, organ and system of your body. These nerve impulses travel through your spine, so having a spine free of subluxations is essential for optimal health.'
        ),
        'conditions' => "Physical stress (slips, falls, accidents, posture)\nRepetitive strain\nEmotional stress",
        'benefits' => "Muscle spasm\nSpinal stiffness\nHeadaches\nBack pain",
        'how_it_works' => $p(
            'A chiropractic examination can detect subluxations, and chiropractic adjustments can reduce their effect on your nervous system, naturally.',
            'Find out if you have subluxations before they become more serious and more difficult to correct.'
        ),
        'what_to_expect' => '',
        'faqs' => [],
        'related' => 'chiropractic-care,stress,neck-pain',
        'body_areas' => 'whole-body',
        'meta_title' => 'Subluxation Treatment | Chiropractic Care for Better Health',
        'meta_description' => 'Treat subluxations with chiropractic care at Columbia Basin Chiropractic. Restore spinal alignment and enhance overall well-being.',
    ],
];
