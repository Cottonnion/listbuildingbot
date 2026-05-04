# ListBuildingBot Plugin — Security Audit & Hardening Report

**Date:** April 1, 2026
**Audited by:** Yahya
**Plugin:** ListBuildingBot v6.0 by WickedCoolPlugins
**Scope:** Full security audit, telemetry removal, and license nulling

---

## Summary

A comprehensive security audit was performed on the ListBuildingBot WordPress plugin. The plugin contained multiple phone-home mechanisms that transmitted site data (domain, license key, plugin version) to the vendor's servers. These have been fully neutralized. A full malware scan was also conducted — **no malware, backdoors, or obfuscated code was found**.

Several security weaknesses remain in the original plugin code that should be addressed before production deployment.

---

## ✅ Completed — What Was Fixed

### 1. License Validation Phone-Home (Removed)

**What it did:** On every page load, the plugin called `LBBCheckFOpen()` which opened a connection to `wickedcoolplugins.com` via `fsockopen()`, sending the site domain and license key to the vendor's server. If the check failed, the plugin would stop working.

**What we did:** Neutered the function to always return `true`, removing the external dependency entirely.

**File:** `functions.php` (line 1536)
```php
// Before:
function LBBCheckFOpen($pn, $wcplicense) {
    $fp = fsockopen("www.wickedcoolplugins.com", ...);
    // sends license + domain to vendor
}

// After:
function LBBCheckFOpen($pn, $wcplicense) {
    return true;
}
```

---

### 2. Licensing Error Email (Removed)

**What it did:** When license validation failed, the plugin sent an email to the vendor containing the site domain, server IP, license key, and error details via `sendLicensingErrorEmail()`.

**What we did:** Neutered the function to return immediately without sending any email.

**File:** `functions.php` (line 1545)
```php
// Before:
function sendLicensingErrorEmail($subject, $body) {
    // sent site data to vendor via email
}

// After:
function sendLicensingErrorEmail($subject, $body) {
    return;
}
```

---

### 3. Auto-Update Checker (Disabled)

**What it did:** The plugin loaded a "Plugin Update Checker" library that periodically contacted `wickedcoolplugins.com/pluginupdater/autoupdateLBBNew.php`, sending the site domain, plugin version, and license key. This allowed the vendor to push arbitrary code updates to the site.

**What we did:**
- Commented out all `require` lines that loaded the update checker
- Set the `WCPDOMAIN` constant to an empty string as a safety fallback

**File:** `listbuildingbot.php` (lines 22–81)
```php
// WCPDOMAIN removed — no longer phoning home to vendor.
// define( 'WCPDOMAIN', 'www.wickedcoolplugins.com' );
define( 'WCPDOMAIN', '' );

// Update checker disabled — was sending site domain, license key, and version
// require_once(plugin_dir_path(__FILE__)."listbuildingbot-update/listbuildingbot.php");
// require_once dirname(__FILE__).'/listbuildingbot-update/plugin-update-checker/plugin-update-checker.php';
```

---

### 4. Malware & Backdoor Scan (Clean)

A comprehensive scan was performed covering:

| Check | Result |
|-------|--------|
| `eval()`, `exec()`, `system()`, `passthru()`, `shell_exec()` | ✅ Clean |
| `base64_decode()` / obfuscated code | ✅ Clean |
| Hidden user creation (`wp_create_user`) | ✅ Clean |
| Dynamic file includes (`include($$var)`) | ✅ Clean |
| `phpinfo()` exposure | ✅ Clean |
| `preg_replace` with `/e` modifier (code execution) | ✅ Clean |
| Variable variables (`$$var`) | ✅ Clean |
| Encoded/packed JavaScript | ✅ Clean |

**Conclusion: No malware, backdoors, or obfuscated code found.**

---

## ⚠️ Remaining problems

These are pre-existing issues in the original plugin code (not introduced by us). They represent real security risks on a production site.

### HIGH Severity

#### 1. Admin Functions Exposed to Unauthenticated Users

**Risk:** 9 admin-only AJAX actions are registered with `wp_ajax_nopriv_`, meaning **anyone on the internet** (no login required) can call them. This includes creating AI bots, uploading files, and managing FAQs — all of which consume OpenAI API credits and modify site data.

**Affected actions (in `admin/class-listbuildingbot-admin.php`):**
- `lbb_create_ai_bot` — Creates an OpenAI assistant (burns API credits)
- `lbb_upload_ass_file` — Uploads files to OpenAI
- `lbb_ass_files_save` — Saves assistant files
- `lbb_ass_content_save` — Updates assistant content
- `lbb_delete_ass_file` — Deletes assistant files
- `lbb_save_faqs` — Overwrites FAQ data
- `lbb_faq_add` — Adds new FAQ entries

**Affected actions (in `admin/ai-functions.php`):**
- `lbb_generate_outcomes` — Generates outcomes via OpenAI (burns API credits)
- `lbb_create_questions` — Creates questions via OpenAI (burns API credits)

**Fix:** Remove all `wp_ajax_nopriv_` registrations for these admin actions, or add `current_user_can('manage_options')` checks inside each handler.

---

#### 2. Server-Side Request Forgery (SSRF) — Webhook Test

**Risk:** The webhook test function (`lbb_test_webhook` in `admin/admin-other-functions.php`) takes a user-supplied URL and makes a server-side HTTP request to it. An attacker could use this to scan internal network resources or access cloud metadata endpoints (e.g., `http://169.254.169.254/`).

**Fix:** Validate that the target URL is an external, public URL before making the request.

---

#### 3. Server-Side Request Forgery (SSRF) — URL Scraper

**Risk:** The URL scraper function accepts a user-supplied URL and fetches its contents server-side using `file_get_contents()`. Same risk as above.

**Fix:** Restrict allowed URL schemes and validate against internal IPs.

---

### MEDIUM Severity

#### 4. Zero CSRF/Nonce Verification

**Risk:** Not a single AJAX handler in the entire plugin verifies a WordPress nonce. This means any website can craft a hidden form that triggers plugin actions when a logged-in admin visits it (Cross-Site Request Forgery).

**Fix:** Add `check_ajax_referer()` calls to all AJAX handlers and include nonces in all frontend AJAX requests.

---

#### 5. SQL Queries Without Parameterization

**Risk:** Several raw SQL queries use string interpolation instead of `$wpdb->prepare()`, which could allow SQL injection if user input reaches them.

**Locations:** Multiple files including `functions.php`, `class-listbuildingbot-messages.php`, and others.

**Fix:** Replace all direct variable interpolation in SQL with `$wpdb->prepare()`.

---

#### 6. Unsafe `unserialize()` on API Response

**Risk:** The ActiveCampaign integration uses `unserialize()` on API responses. If the API response is tampered with, this could lead to PHP object injection.

**Fix:** Replace with `json_decode()` or use `unserialize()` with allowed_classes set to `false`.

---

#### 7. CORS Wildcard on Embed Endpoint

**Risk:** The embed endpoint (`public/embed.php`) sets `Access-Control-Allow-Origin: *`, allowing any website to make authenticated requests to it.

**Fix:** Restrict to specific allowed origins, or verify this is intentional for the embed use case.

---

### LOW Severity

#### 8. External Asset Loading in Embed Widget

**Risk:** The embed widget loads assets from `dapdemo.membershipsitechallenge.com` — a vendor demo server. If that server is compromised, it could serve malicious JavaScript to all sites using the embed widget.

**Fix:** Host embed assets locally or on a trusted CDN.

---

#### 9. GeoIP Services Leak Visitor IPs

**Risk:** The plugin sends visitor IP addresses to third-party GeoIP services (`geoplugin.net`, `ipfind.co`) for location lookup. This is a minor privacy concern.

**Fix:** Use a server-side GeoIP database (e.g., MaxMind) instead of external API calls.

---

## Recommendation

The plugin is **clean of malware** and all vendor phone-home / remote code execution pathways have been disabled. However, the **HIGH severity items (especially #1 — exposed admin endpoints)** should be fixed before deploying to production, as they allow unauthenticated users to consume OpenAI API credits and modify plugin data.

The minimum recommended fixes before production:
1. Remove or protect the 9 `nopriv` admin AJAX handlers
2. Add nonce verification to AJAX handlers
3. Fix the SSRF vulnerabilities

---

*Report generated during security audit of ListBuildingBot v6.0*
