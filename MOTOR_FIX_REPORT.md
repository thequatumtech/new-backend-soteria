# Motor Insurance – Testing Round 14 Fix Report

Branch: `rd_soteria_local` (local only, nothing pushed). Database: local `new_soteria`.
Source: *Testing Round 14 – Backend* and *Frontend* documents (Motor sections only).

## A. Fixed in this repository

| # | Client point | What was wrong | What was done | Files |
|---|---|---|---|---|
| 1 | New plan added in Admin Basic > Motor Plan does not appear in Motor Plans | Sidebar had 6 fixed links; plan types with id > 6 were never shown and the plan pages/API only knew ids 1-6 | Sidebar now lists every Motor Plan type with id > 6 automatically. New pages: list (`motor-plan-type/{id}`) and add (`add-motor-plan-type/{id}`), same layout as the other plans. Save / edit / delete / premium calculation treat these types like Comprehensive. New API `GET /getMotorPlansByType?motor_plan_id=` returns only plans of the selected type | `PlanController.php`, `sidebar.blade.php`, `routes/web.php`, `routes/api.php`, `Api/MotorInsuranceController.php`, `add_/edit_motor_plan_comprehensive.blade.php` |
| 2 | Insurance company not a dropdown when editing | Edit screens showed a disabled text box | Replaced with a dropdown on all 6 edit screens (Comprehensive, Compulsory 3/6/9/12, Total Loss); the chosen company is saved | 6 `edit_motor_plan_*.blade.php`, `PlanController.php` |
| 3 | Claim deductible table with CLAIM NO. and PERCENTAGE | Table only had a `name` column | New `percentage` column (migration, run on local DB). List, add and edit screens now show Claim No. (letters/numbers) and Percentage (0-100, required). English and Arabic labels added | migration `2026_10_28_000001_...`, `ClaimDeductible.php`, `ClaimDeductibleController.php`, `claim_deductible.blade.php`, `lang/en|ar/messages.php` |
| 4 | Date format DD/MM/YYYY in draft policy / policy | PDF used `m-d-Y` | Effective, expiry and manufacturing dates now `d/m/Y` | `pdf/motor_policy.blade.php` |
| 5 | Tax field missing from draft policy | Tax row was hidden when the plan had no tax value | Sales Tax row always shown, `0` when tax is 0 | `pdf/motor_policy.blade.php` |
| 6 | Geographical coverage must come from the user's country | Not in the policy | New "Geographical Coverage" row taken from the client's country | `pdf/motor_policy.blade.php`, `Api/MotorInsuranceController.php` |
| 7 | All underwriting information must show in the draft policy | Only a few fields were printed | New "Vehicle & Underwriting Information" section (brand, category, type, engine type/capacity, plate, registration, engine, chassis, manufacturing date, value, accidents, tickets, points). Policy covers and additional benefits were already listed | `pdf/motor_policy.blade.php` |
| 8 | Title "Motor Insurance Policy", not contract | Title was already correct; the footer still said "contract" | Footer now says "policy" | `pdf/motor_policy.blade.php` |
| 9 | Only plans of the selected name should appear | API list endpoints were fixed per type | `getMotorPlansByType` filters by the selected type | `Api/MotorInsuranceController.php` |

Verified: PHP lint on all edited files, Blade compile of all edited views, the policy view rendered with sample data (date, tax, country, underwriting section), and the list/add/edit admin pages rendered with the new plan type 7 and the edit dropdown.

## B. Already working in the existing code (no change needed)

* Company name, logo, stamp and authorised signature are printed in the policy.
* Premium is annual ("Annual in advance"); currency comes from the insurance company / client country (JOD).
* Backend restrictions (country, city, district, age, vehicle type, brand, category, engine type) are checked when a motor policy is created.
* Items the developer already marked "Solved / Working fine" in the document were not re-tested.

## C. Not done here (mobile app / needs a decision)

* **Live video of the 4 sides of the vehicle with date and time** – the capture screen is in the mobile app (APK); this backend only stores photos.
* **"Motor" instead of "Term Plan" label, monthly vs annual wording on app screens, premium mismatch between screens, tick-box behaviour, list of quotes on screen** – these are app (frontend) screens.
* **Sub-admin authorisation per insurance company** (Sub-Admin section, not Motor) – not part of this request.
* **New plan types and premium structure** – new types behave like Comprehensive (schedule by brand/category/value). A type that needs a different premium structure (e.g. a new compulsory type) needs its own form.

## D. Notes for deployment

* Run `php artisan migrate` on the target database to add `claim_deductibles.percentage`. Existing rows keep an empty percentage until edited (the field is required on next edit).
* In the local database the Motor Plan list already has ids 7, 8, 9 (e.g. "Motor Compulsory 18 months"); they now appear in the sidebar.
* `public/` is git-ignored in this repo, so it was copied locally from `soteria`.
