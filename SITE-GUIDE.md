# Site Guide — Columbia Basin Chiropractic (new site)

Staging: https://new.columbiabasinchiropractic.com · Dashboard: `/admin/` · Backend reference: `BACKEND.md`

## 1. Putting images on the site

Every image area on the site is an **image slot**. Empty slots show a branded green placeholder, so the site never looks broken. There are three ways to fill a slot:

1. **Media Library → select a file → "Assign to…"** Tick every place the image should appear (homepage sections, services, conditions, team photos, page heroes, office photo, logo) and click **Assign**. You can search the list, e.g. "hero", "sciatica" or "Kurtz".
2. **From the website itself.** While you're signed in to the dashboard, every image slot on the public site shows an **"＋ Assign image"** chip (or **"✎ Change image"** when it's filled). Click it to open the Media Library with that slot pre-selected, choose or upload a file, then click **Use here**. Visitors never see these chips.
3. **Auto-assign by file name.** Upload files with the names below, then open Media Library → **Auto-assign images**.

### Site-wide slots (Settings → Site Images)

| File name | Slot | Where it appears |
|---|---|---|
| `home-hero` | Homepage — hero photo | Large arched photo at the top of the homepage. |
| `home-hero-2` | Homepage — hero inset photo | Small round photo overlapping the hero photo. |
| `home-about` | Homepage — "Care built around you" photo | Beside the introduction under the hero. |
| `home-feature` | Homepage — featured treatment photo | Spinal decompression feature section. |
| `home-purpose` | Homepage — purpose / mission photo | Beside "Love God. Love people." |
| `home-testimonials` | Homepage — reviews photo | Beside patient reviews on the homepage. |
| `home-videos` | Homepage — patient education cover | Video teaser on the homepage. |
| `home-gallery-1` | Practice gallery — photo 1 (large) | "Inside our practice" gallery on the homepage and About page. |
| `home-gallery-2` | Practice gallery — photo 2 | "Inside our practice" gallery. |
| `home-gallery-3` | Practice gallery — photo 3 | "Inside our practice" gallery. |
| `home-gallery-4` | Practice gallery — photo 4 | "Inside our practice" gallery. |
| `home-gallery-5` | Practice gallery — photo 5 | "Inside our practice" gallery. |
| `about-team` | About page — team photo | Wide team photo on the About page. |
| `home-cta` | Call-to-action background | Background of the "Ready to take the next step?" band at the bottom of most pages. |
| `logo` / `logo-light` / `favicon` | Branding | Header logo, footer logo, browser tab icon (the built-in mark is used until set) |
| `social-share` | SEO | Image used when the site is shared on social media |

### Record slots

| File name | Goes to |
|---|---|
| `chiropractic-care.jpg`, `spinal-decompression.jpg`, `accident-injury-care.jpg`, `pregnancy-care.jpg`, `sports-medicine.jpg`, `nutrition-lifestyle-support.jpg` | The service's card and page hero |
| `lower-back-pain.jpg`, `upper-back-pain.jpg`, `neck-pain.jpg`, `sciatica.jpg`, `herniated-slipped-discs.jpg`, `headaches-migraines.jpg`, `shoulder-pain.jpg`, `scoliosis.jpg`, `stress.jpg`, `subluxations.jpg` | The condition's card and page hero |
| `dr-eric-kurtz.jpg`, `dr-rick-folkman.jpg`, `dr-liz-faletti.jpg`, `griselda-jensen.jpg`, `makayla-hunt.jpg`, `evie-jensen.jpg`, `kristi-forsberg.jpg`, `sofia-jensen.jpg`, `alexis-amador.jpg` | Team photos. After assigning, click the photo in the provider editor to set the focal point so faces aren't cropped |
| `page-services.jpg`, `page-conditions.jpg`, `page-about-us.jpg`, `page-new-patient.jpg`, `page-patient-portal.jpg` | Page heroes |
| `location-richland.jpg` | Office photo on the Contact page |

Recommended sizes: hero and arch photos 1600–2400 px on the long side; team photos portrait (4:5); gallery photo 1 square or portrait. Uploads are automatically resized and converted to WebP.

## 2. Where content came from

All copy was migrated from the current WordPress database (pages, services, condition pages, doctor bios, Google reviews, FAQs, privacy and SMS policies). Every service and condition is flagged **Needs review** in the dashboard so the practice can approve it.

Things to confirm with the practice before launch:

- **Office hours.** The WordPress site lists both "Mon–Fri 9–6" (homepage) and "Mon, Wed–Fri 9–6 · Tue 10–6" (service pages, footer, scheduling page). The second is used; edit it in Dashboard → Locations.
- **Patient portal default PIN** ("1111") is published as on the old site. Remove it from the Patient Portal page if it shouldn't be public.
- **Not migrated** (theme demo or placeholder content): Billing & Insurance (lorem ipsum prices), Testimonials page (demo quotes; replaced with the real Google reviews), Work With Us (job post for Columbia Basin Medical Center with a placeholder phone number), blog pages, shop products, portfolio items. Their URLs redirect to the closest new page.
- **Patient paperwork PDFs** don't exist in the old site. Upload `new-patient-form-english.pdf` (etc., see `Content::FORM_GROUPS`) and they appear on New Patients and Book Appointment.

## 3. URLs and redirects

- Services: `/service/<slug>/` (same as WordPress). Conditions: `/<slug>/` (same as WordPress). Doctors: `/doctor/<slug>/` (same as WordPress).
- 301 redirects are seeded for every other old URL: `/services/<slug>/`, `/conditions/<slug>/`, `/schedule-an-appointment/`, `/make-appointment/`, `/location/`, `/our-doctor/`, `/patient-paperwork/`, `/sports-medicine/`, `/stress-tension/`, `/slipped-discs/`, `/sms-policy/`, blog/demo pages and more (Dashboard → Redirects).

## 4. Booking

"Book online" buttons open Jane (`https://cbc.janeapp.com/`), set in Settings → General → Online booking link. Clear it to send people to the built-in request form instead. The appointment page offers both.

## 5. Deploying

See `BACKEND.md` §9. Summary: add the SiteGround SSH secrets to the GitHub repository, run **Actions → Deploy to SiteGround** (tick *first_deploy* the first time), then open `/install/`.
