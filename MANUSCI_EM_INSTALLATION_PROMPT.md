# MANUSCI EDITORIAL MANAGER® (EM) DOWNLOADABLE INSTALLATION PROMPT & GUIDE

> **Universal Package:** `editorialmanagerreview-1.0.0.tar.gz` (162 KB)  
> **Official Repository:** `https://github.com/dmnuydaph-rgb/editorial-manager-review`  
> **Compatibility:** Open Journal Systems (OJS) 3.4.x & 3.5.x | PHP 8.1 - 8.3 | MariaDB 10.4+ / MySQL 8.0+  
> **Proprietary Stack Architecture:** Adaptive Reactive Workstation Core (ARWC) — 100% Client Zero-Node Guarantee.

---

## PART 1: MASTER PROMPT FOR MANUSCI CLIENTS & ADMINISTRATORS

*Copy and paste the prompt below directly into any AI assistant, developer task list, or OJS technical operations ticket to execute the installation on any journal.*

```markdown
# TASK: Install and Configure Manusci Editorial Manager® (EM) Reviewer Workstation on OJS 3.4+ / 3.5+

You are an expert Open Journal Systems (OJS) DevOps and full-stack software engineer. Your task is to install, configure, and activate the official **Manusci Editorial Manager® Peer Review Workstation** on this OJS journal instance.

---

### 1. PACKAGE & SPECIFICATION
* **Package File:** `editorialmanagerreview-1.0.0.tar.gz`
* **Plugin Type:** Generic Plugin (`plugins/generic/editorialManagerReview`)
* **Core Capabilities:**
  1. **Triple Zero-Trust Security Gates:**
     - **Device Gate:** Enforces desktop/laptop display (minimum viewport width 1024px); blocks mobile devices with a security lock screen.
     - **Direct Access Gate:** Prevents raw URL bypassing via Frame-0 `#00172B` solid backdrop isolation and session token validation.
     - **Email Verification Challenge:** Issues a time-limited 6-digit cryptographic verification challenge (HMAC-SHA256) sent to the reviewer's registered email before unlocking manuscript files.
  2. **Split-Screen Evaluation Workspace:** Simultaneous reactive PDF preview alongside an interactive 5-point scientific rubric evaluation form.
  3. **Real-Time Autosave:** Debounced draft persistence to the OJS database (`review_assignments` / comments).
  4. **Universal Multi-Tenant Branding:** Dynamically extracts journal name, acronym, ISSNs, and branding directly from the host OJS database. No hardcoded journal names.
  5. **Automated Peer Review Certificate:** Real-time generation of a print-ready verification certificate upon review submission.
  6. **Zero Client Node.js Requirement:** All reactive components are pre-compiled into native PHP bundles. The host server does NOT require Node.js, npm, or daemon processes.

---

### 2. CHOOSE INSTALLATION METHOD

#### METHOD A: 1-Click GUI Upload via OJS Dashboard (Recommended for Journal Admins)
1. Log in to the OJS Administration panel as a **Journal Manager** or **Site Administrator**.
2. Navigate to **Settings > Website > Plugins**.
3. Under the **Plugin Gallery** or **Installed Plugins** tab, click **Upload A New Plugin** at the top right.
4. Select the file `editorialmanagerreview-1.0.0.tar.gz` and click **Save**.
5. OJS will automatically extract the package into `plugins/generic/editorialManagerReview/` and register the plugin version.
6. Locate **Editorial Manager Review Portal** under **Generic Plugins** and check the toggle box to **Enable** it.

#### METHOD B: Direct Server CLI / SFTP Deployment (Recommended for DevOps & cPanel)
1. Upload `editorialmanagerreview-1.0.0.tar.gz` to the server root or your OJS installation path.
2. Extract directly into the plugins generic folder:
   ```bash
   cd /path/to/ojs/plugins/generic/
   tar -xzvf /path/to/editorialmanagerreview-1.0.0.tar.gz
   ```
3. Verify permissions:
   ```bash
   chown -R ojs_user:ojs_group editorialManagerReview/
   chmod -R 755 editorialManagerReview/
   ```
4. Clear OJS cache to register plugin classes:
   ```bash
   php /path/to/ojs/tools/clearDataCache.php
   ```
5. Enable the plugin via database or OJS Dashboard:
   ```sql
   INSERT INTO plugin_settings (plugin_name, journal_id, setting_name, setting_value, setting_type)
   VALUES ('editorialmanagerreviewplugin', 1, 'enabled', '1', 'bool')
   ON DUPLICATE KEY UPDATE setting_value = '1';
   ```

---

### 3. ROUTING & ACCESS ENDPOINTS

The plugin registers the native OJS route:
`https://yourjournal.domain/index.php/{journal_path}/reviewer-portal?token={HMAC_TOKEN}`

#### Optional Subdomain Setup (e.g., https://review.yourjournal.domain)
To provide reviewers with a clean, branded standalone portal URL:
1. In cPanel or DNS management, create a subdomain `review.yourjournal.domain`.
2. Point its DocumentRoot to `/path/to/ojs/plugins/generic/editorialManagerReview/portal`.
3. In `portal/`, the pre-compiled `index.html` connects to `../api.php` automatically.
4. Reviewer assignment invitations will direct to:
   `https://review.yourjournal.domain/?token={HMAC_TOKEN}`

---

### 4. SMTP EMAIL CHALLENGE CONFIGURATION
To ensure the 6-digit email verification challenge is delivered reliably to reviewers:
1. Open `/path/to/ojs/config.inc.php`.
2. Ensure SMTP is configured with SSL on Port 465 (or TLS on Port 587):
   ```ini
   [email]
   default = "smtp"
   smtp = On
   smtp_server = "smtp.resend.com" ; or your transactional SMTP provider
   smtp_port = 465
   smtp_auth = "ssl"
   smtp_username = "resend"
   smtp_password = "YOUR_RESEND_API_KEY"
   envelope_sender = "editor@yourjournal.domain"
   ```

---

### 5. VERIFICATION & POST-INSTALL CHECKLIST
1. [ ] Access the portal URL with a test token or review assignment ID.
2. [ ] Verify that opening the URL on a mobile device displays the desktop-only security screen.
3. [ ] Verify that navigating directly without a token displays the solid `#00172B` zero-leakage security screen.
4. [ ] Request the 6-digit email challenge and verify prompt inbox delivery.
5. [ ] Confirm split-screen PDF preview and Likert rubric submission persist successfully to the OJS review assignment record.
```

---

## PART 2: PACKAGE INTEGRITY & CHECKSUM

| File Property | Value |
| :--- | :--- |
| **Filename** | `editorialmanagerreview-1.0.0.tar.gz` |
| **Size** | `162,053 bytes` (~162 KB) |
| **Root Archive Directory** | `editorialManagerReview/` |
| **Plugin Class** | `EditorialManagerReviewPlugin.php` |
| **OJS Hook** | `LoadHandler` (`/reviewer-portal`) |
| **Dependencies** | None (Standalone PHP 8.1+ & Native Client Bundle) |
| **GitHub Tag** | `v1.0.0` at `https://github.com/dmnuydaph-rgb/editorial-manager-review` |

---

## PART 3: ARCHITECTURE SUMMARY FOR CLIENT PROPOSALS

When presenting this package to client institutions (e.g., **Philair.Org**, **Davao Research Journal**, or external university publishers), highlight these 4 enterprise guarantees:

1. **Zero-Friction Adoption:** Journal editors do not need to upgrade server hardware or install Node.js/Docker runtimes. It deploys directly into their existing cPanel/Apache/Nginx environment.
2. **COPE & Crossref Aligned:** Implements standard double-blind peer review ethics, automated metadata extraction, and verifiable reviewer recognition certificates.
3. **Multi-Tenant Agnostic:** One codebase serves all journals. The system reads journal title, acronym, and ISSN directly from the host OJS schema dynamically.
4. **Zero-Trust Security:** Protects unpublished manuscripts from leakages, unauthorized mobile screen grabs, and unauthenticated URL scraping.
