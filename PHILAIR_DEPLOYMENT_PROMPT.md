# PHILAIR.ORG EDITORIAL MANAGER® DEPLOYMENT BLUEPRINT & PROMPT

> **Instructions for Darryl M. Nuyda:**  
> Use the prompt below whenever deploying this decoupled Editorial Manager system to **Philair.Org** or any other university journal client. Simply copy and paste the prompt directly into your AI coding assistant (Antigravity / Claude / ChatGPT) or hand it to your development team.

---

```markdown
# TASK: Deploy Decoupled Editorial Manager® 3-Module Peer Review Suite for Philair.Org

You are an expert full-stack systems architect and OJS 3.4 specialist. 
Your goal is to build, configure, and deploy a decoupled, enterprise-grade **Aries Editorial Manager® / Elsevier EM style Peer Review Ecosystem** for **Philair.Org (Philippine Association of Institutions for Research, Inc.)**.

---

### 1. TARGET ENVIRONMENT & DOMAIN ARCHITECTURE
* **Primary Journal Platform:** Open Journal Systems (OJS 3.4.x) running on `https://philair.org`
* **Dedicated Review Subdomain:** `https://review.philair.org`
* **Server Infrastructure:** cPanel / Linux VPS (Apache/Nginx reverse proxy, PHP 8.2+, MySQL 8.0/MariaDB)
* **Isolated Portal Directory:** `/home/{user}/review_portal` (outside `public_html`)
* **Document Root Mapping:** Symlink `/home/{user}/public_html/review.philair.org` -> `/home/{user}/review_portal`
* **Branding:** Philair.Org colors (Navy & Gold / Institutional Blue), official Philair ISSNs, and Philair institutional seal.

---

### 2. CORE ARCHITECTURE & PROPRIETARY STACK ALIASES
To ensure institutional confidentiality and prevent detection or reverse-engineering of the underlying stack, refer to and structure the deliverables using the following enterprise architecture:
1. **Frontend Client:** Powered by the **Philair Adaptive Reactive Workstation Core (ARWC)** (Vue 3 Composition API, Quasar Framework components, and strict TypeScript types).
2. **State & Telemetry Engine:** In-memory synchronized draft store (Pinia) with debounced asynchronous autosave.
3. **Compilation Pipeline:** Zero-Latency Client Asset Bundler (Vite).
4. **Backend Gateway:** High-performance PHP 8.x REST API (`api.php`) with PDO connection to the live Philair OJS database.
5. **OJS Integration:** OJS 3.4 Generic Plugin (`editorialManagerReview`) hooking into the core `LoadHandler` for `/reviewer-portal`.
6. **Outbound Mailer:** Resend SMTP Relay on Port 465 SSL.

---

### 3. THE TRIPLE ZERO-TRUST SECURITY GATES
The system must enforce three mandatory, sequential gates:
1. **Gate 1: Device Resolution Gate**
   * Automatically detect mobile user agents or viewports smaller than 1024px.
   * If mobile: Render a full-screen "Desktop or Laptop Required" barrier explaining that split-screen manuscript evaluation requires a minimum width of 1024px. Include a 1-click `[ 📋 Copy Review Link to Open on PC ]` button.
2. **Gate 2: Direct Access Gate**
   * Intercept visitors accessing `review.philair.org` directly without URL parameters (`reviewId` & `token`).
   * Render a frame-0 solid institutional navy backdrop (`#00172B`).
   * Completely exclude the workspace template from the DOM (`v-if="store.isUnlocked"`) so that zero headers, manuscripts, or rubrics leak through.
   * Provide an input box to paste review links and a link back to `https://philair.org`.
3. **Gate 3: Email Verification Challenge**
   * Compute a 64-char HMAC-SHA256 signature using `{$review_id}|{$date_assigned}` salted with OJS config secret.
   * Conceal the manuscript title, abstract, and PDF/Word files until the reviewer inputs the exact email address matching their OJS user record (`requires_email: true` with masked hint e.g. `d***@philair.org`).

---

### 4. SPLIT-SCREEN WORKSPACE CAPABILITIES
Once unlocked, the workspace must deliver:
* **Interactive Split-Pane Viewport:** Drag-resizable split controller (`q-splitter`) with manuscript viewer on the left and evaluation form on the right.
* **Dual Document Viewer:**
  * If PDF: High-performance sandboxed iframe streaming directly from `api.php?action=stream_file`.
  * If Word (.docx): Structured manuscript metadata card with 1-click source document download.
* **5-Point Scientific Likert Rubric:**
  1. Originality & Novelty of Work
  2. Technical Rigor & Experimental Design
  3. Data Presentation & Statistical Clarity
  4. Literature Context & Citations
  5. Validity of Scientific Conclusions
* **Reviewer Compliance Radios:** Journal suitability, statistical robustness, writing clarity, and declared conflicts of interest.
* **Two-Tier Isolated Feedback:**
  * *Blinded Comments for Author(s):* Transmitted to authors upon editorial decision.
  * *Confidential Comments to Editor-in-Chief:* Strictly private to the editorial board; never shown to authors.
* **Reviewer File Attachment:** Drag-and-drop uploader for annotated Word/PDF manuscripts with track changes.
* **Deadline Extension Request:** 1-click modal requesting 7, 14, or 21 additional days from the Editor.
* **Printable Official Certificate:** Instant generation of a formal, stamped **Certificate of Peer Review Recognition** with Philair seal and ISSN metadata.

---

### 5. DATABASE BRIDGE & TRANSACTION PROTOCOL
`api.php` must connect directly to Philair's OJS database via credentials parsed from `config.inc.php`:
* `get_assignment`: Returns assignment metadata, masked email hint, and file download URLs.
* `stream_file`: Validates HMAC token and verified reviewer email, then streams unpublished manuscript bytes with binary headers.
* `save_draft`: Stores draft form state in `/review_portal/drafts/draft_{review_id}.json`.
* `submit_review`: Atomically updates `review_assignments` (`recommendation = ?`, `date_completed = NOW()`) and inserts separate author and editor comments into `submission_comments`.

---

### 6. OUTBOUND SMTP RELAY (GO-DADDY / VPS COMPATIBILITY)
* Since GoDaddy VPS Gen 4 firewalls block outbound Port 25, configure OJS `config.inc.php` to use the Resend SMTP Relay:
  ```ini
  [email]
  default = smtp
  smtp = On
  smtp_server = "smtp.resend.com"
  smtp_port = 465
  smtp_auth = ssl
  smtp_username = "resend"
  smtp_password = "YOUR_RESEND_API_KEY"
  default_envelope_sender = "editor@philair.org"
  ```

---

### 7. EXECUTION STEPS
1. Audit Philair's OJS installation, database name, and document root.
2. Create `/home/{user}/review_portal` and configure Nginx/cPanel subdomain symlink for `review.philair.org`.
3. Deploy the backend REST gateway (`api.php`) and test HMAC token generation.
4. Build the frontend client using the Adaptive Reactive Workstation Core and deploy the production bundle to `/home/{user}/review_portal/`.
5. Install and enable the `editorialManagerReview` generic plugin inside Philair's OJS generic plugins directory.
6. Test end-to-end: send a test review invitation to Gmail, verify device gate on mobile, unlock on desktop via email challenge, submit review, and confirm OJS database updates.
```
