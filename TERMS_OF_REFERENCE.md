# TERMS OF REFERENCE (TOR) & FINAL ACCEPTANCE REPORT

**Project Title:** Technical Consultancy and Systems Development Services for the Davao Research Journal (DRJ) Modern Editorial Manager® Ecosystem  
**Procuring Entity / Client:** Davao Oriental State University (DORSU) — Publication and Research Office  
**Beneficiary Publication:** Davao Research Journal (DRJ) • ISSN: 2094-814X (Print), 2984-7540 (Online)  
**Contract Type:** Technical Consultancy & Custom Software Engineering  
**Project Status:** **100% Completed, Verified, and Deployed**  
**Applicable Standard:** Commission on Higher Education (CHED) Journal Accreditations / Scopus / Web of Science Indexing Readiness Guidelines  

---

## 1. Project Background and Rationale

The Davao Oriental State University (DORSU), through the Davao Research Journal (DRJ), publishes high-impact multidisciplinary scientific papers. To meet international journal indexing benchmarks (Scopus, Web of Science, ASEAN Citation Index) and satisfy CHED research dissemination criteria, academic publishing systems must provide:
* High-speed, transparent peer review cycles.
* Uncompromised manuscript confidentiality and blind review integrity.
* Frictionless experience for international reviewers, authors, and editors.

This project has successfully delivered a **complete 3-Module Editorial Suite** integrating with DORSU's live OJS 3.4 installation, replacing cumbersome traditional reviewer forms with a high-end **Aries Editorial Manager®** experience.

---

## 2. Scope of Work & Completed Deliverables

All three (3) essential publishing modules have been verified and operationalized:

### Module 1: Author Submission & Tracking Module *(Completed)*
* **Live System URL:** `https://davaoresearchjournal.ph/index.php/main/submission/wizard` (or `/drj/submission/wizard`)
* **Features Delivered:**
  1. Multi-step guided author submission wizard.
  2. Automated file uploads (.docx, .pdf, supplementary data) with author checklist verification.
  3. Real-time submission milestone tracking.

### Module 2: Editorial Management & Decision Hub *(Completed)*
* **Live System URL:** `https://davaoresearchjournal.ph/index.php/main/workflow/index` (or `/drj/workflow/index`)
* **Features Delivered:**
  1. Editorial triage and section editor assignment cockpit.
  2. 1-click reviewer search, assignment, and tracking.
  3. Outbound email invitation delivery via **Resend SMTP Relay (Port 465 SSL)** bypassing VPS firewall blocks.
  4. Multi-reviewer recommendation collation and decision dispatch (Accept, Minor, Major, Reject).

### Module 3: Aries Editorial Manager® Reviewer Workspace *(Completed)*
* **Live System URL:** `https://review.davaoresearchjournal.ph`
* **Features Delivered:**
  1. **Split-Screen Workspace:** Drag-resizable dual split-pane interface synchronizing manuscript document reading and evaluation rubric scoring.
  2. **Scientific Rubric & Compliance Questionnaire:** 5-point Likert evaluation matrix across five scientific standards; compliance radios for statistical integrity, writing clarity, and conflicts of interest.
  3. **Dual-Tier Feedback Isolation:** Database-level isolation between Blinded Author Feedback and Confidential Editor Comments.
  4. **Triple Zero-Trust Security Gates:**
     * *Device Gate:* Automatic detection and blocking of smartphone viewports (< 1024px) with clipboard link copy functionality.
     * *Direct Access Gate:* Frame-0 solid `#00172B` backdrop blocking unauthenticated domain snooping.
     * *Email Verification Gate:* Cryptographic HMAC-SHA256 signature binding with mandatory email identity verification.
  5. **Reviewer File Attachments:** Reviewers can upload marked-up manuscripts (.docx, .pdf) with track-changes.
  6. **Printable Official Certificate of Reviewing:** Automated generation of formal **Certificate of Peer Review Recognition** bearing institutional publication credentials and DRJ seal.
  7. **OJS 3.4 Generic Plugin:** Deployed in OJS at `plugins/generic/editorialManagerReview` registering `index.php/drj/reviewer-portal`.

---

## 3. Technical Deliverables and Acceptance Verification

| Deliverable | Detailed Requirements | Verification & Acceptance Standard | Status |
| :--- | :--- | :--- | :---: |
| **D1: Author Module (Module 1)** | Guided submission wizard, metadata capture, file upload, status tracker. | Operational in OJS 3.4 core at `davaoresearchjournal.ph`. | **VERIFIED & ACCEPTED** |
| **D2: Editorial Hub (Module 2)** | Desk triage, reviewer assignment, decision dispatcher. | Operational in OJS 3.4 core at `davaoresearchjournal.ph`. | **VERIFIED & ACCEPTED** |
| **D3: Reviewer Portal (Module 3)** | DRJ Adaptive Reactive Workstation Client deployed at `review.davaoresearchjournal.ph`. | Verified live on production server with zero build warnings; all 3 security gates active. | **VERIFIED & ACCEPTED** |
| **D4: REST API & Security Bridge** | PHP 8.2 API (`api.php`) with PDO connection to OJS database (`drj2024_35beta`). | Cryptographic HMAC-SHA256 validation confirmed; unauthorized access rejected. | **VERIFIED & ACCEPTED** |
| **D5: Outbound SMTP Integration** | Resend SMTP Relay integrated over Port 465 SSL in `config.inc.php`. | Invitation emails delivered directly to external Gmail/academic inboxes without spam flagging. | **VERIFIED & ACCEPTED** |
| **D6: OJS 3.4 Generic Plugin** | `editorialManagerReview` generic plugin installed in OJS. | Registered in OJS Plugin Gallery; hooks into `LoadHandler` for URL routing. | **VERIFIED & ACCEPTED** |
| **D7: Technical Documentation & Code** | Full TDD, architectural diagrams, Proposal, and GitHub repository. | Clean commit history and comprehensive documentation on GitHub. | **VERIFIED & ACCEPTED** |

---

## 4. Intellectual Property & Code Ownership

1. **Institutional Ownership:** All custom code, plugins, templates, and configurations developed under this contract are the property of **Davao Oriental State University (DORSU)** and the **Davao Research Journal (DRJ)**.
2. **Open-Source Compliance:** In accordance with OJS licensing, the custom software is governed by the **GNU General Public License v3.0 (GPLv3)**, granting DORSU perpetual, royalty-free rights to modify, deploy, and maintain the software indefinitely.

---

## 5. Summary of Commercial Value

| Milestone / Deliverable | Scope | Status | Contract Amount |
| :--- | :--- | :---: | :---: |
| **Module 1: Author Submission Module** | Multi-step submission wizard, file upload, live status tracking | **Complete** | **₱140,000** |
| **Module 2: Editorial Management Hub** | Desk triage, reviewer assignment, Resend SMTP relay, decisions | **Complete** | **₱180,000** |
| **Module 3: Peer Review Workstation** | DRJ Reactive Workstation Core, Dual Split Panes, Triple Security Gates, Certificate | **Complete** | **₱160,000** |
| **Integration & Verification** | End-to-end integration, database consistency, Resend SMTP tuning | **Complete** | **₱60,000** |
| **TOTAL CONTRACT VALUE:** | **Complete 3-Module Modernization Suite** | **100% Complete** | **₱540,000 PHP**<br>(~$9,300 USD) |

---

## 6. Warranty, Support & Service Level Agreement (SLA)

* **Warranty Period:** Six (6) months of warranty support commencing from the date of final system acceptance.
* **Scope of Warranty:** Rapid resolution of any software defects, bugs, regression issues resulting from OJS minor updates, or email deliverability adjustments.
* **Response Times:**
  * *Critical (System Outage / Review Blocked):* Response within 4 hours; resolution within 24 hours.
  * *Standard (Minor UI / Enhancement Requests):* Response within 24 hours; resolution within 72 hours.

---

## 7. Conforme & Signatures

**CONSULTANT / SOFTWARE ARCHITECT:**

______________________________________  
**DARRYL M. NUYDA**  
*Lead Software Architect & Solutions Engineer*  
Date: October 25, 2026  

<br>

**FOR THE PROCURING ENTITY (DORSU / DRJ):**

______________________________________  
**EDITOR-IN-CHIEF**  
*Davao Research Journal (DRJ)*  
Date: ________________________  

<br>

______________________________________  
**VICE PRESIDENT FOR RESEARCH, EXTENSION & INNOVATION**  
*Davao Oriental State University (DORSU)*  
Date: ________________________  

<br>

______________________________________  
**UNIVERSITY PRESIDENT**  
*Davao Oriental State University (DORSU)*  
Date: ________________________  
