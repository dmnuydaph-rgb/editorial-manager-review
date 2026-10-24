# TERMS OF REFERENCE (TOR)

**Project Title:** Procurement of Technical Consultancy and Systems Development Services for the Davao Research Journal (DRJ) Modern Editorial Manager® Ecosystem  
**Procuring Entity / Client:** Davao Oriental State University (DORSU) — Publication and Research Office  
**Beneficiary Publication:** Davao Research Journal (DRJ) • ISSN: 2094-814X (Print), 2984-7540 (Online)  
**Contract Type:** Technical Consultancy & Custom Software Engineering  
**Duration:** 90 Calendar Days  
**Applicable Standard:** Commission on Higher Education (CHED) Journal Accreditations / Scopus / Web of Science Indexing Readiness Guidelines  

---

## 1. Project Background and Rationale

The Davao Oriental State University (DORSU), through the Davao Research Journal (DRJ), publishes high-impact multidisciplinary scientific papers. To meet international journal indexing benchmarks (Scopus, Web of Science, ASEAN Citation Index) and satisfy CHED research dissemination criteria, academic publishing systems must provide:
* High-speed, transparent peer review cycles.
* Uncompromised manuscript confidentiality and blind review integrity.
* Frictionless experience for international reviewers and local scholars.

The current baseline implementation of Open Journal Systems (OJS 3.4) requires modernization to overcome user interface constraints, lack of split-screen evaluation workspaces, and high reviewer bounce rates. This Terms of Reference (TOR) outlines the specifications and contractual obligations for delivering a **complete 3-Module Editorial Suite** integrating with DORSU's live OJS 3.4 installation.

---

## 2. Scope of Work (SOW)

The Lead Software Architect / Consultant shall engineer, configure, integrate, and deploy three (3) distinct yet seamlessly connected modules:

### Module 1: Author Submission & Tracking Portal
1. **Interactive Guided Submission Engine:**
   * Step-by-step progress stepper with dynamic client-side field validation.
   * Auto-save capability ensuring authors never lose text during prolonged sessions.
2. **Metadata & Blinded File Segregation:**
   * Automated partitioning of blinded manuscripts (removing author names/affiliations) from separate Title Pages.
   * Mandatory declaration forms (Conflict of Interest, Authorship Contributions, Ethical Clearances).
3. **Live Manuscript Tracking Dashboard:**
   * Visual milestone progress indicators reflecting real-time OJS workflow states.
4. **Author Revision Workspace:**
   * Dedicated interface for submitting revised drafts alongside point-by-point rebuttal matrices.

### Module 2: Editorial Management & Decision Hub
1. **Editorial Triage Cockpit:**
   * High-level Kanban board organizing submissions across Pre-Review Triage, Under Review, In Revision, and Final Decision.
2. **Reviewer Matching & Workload Tracking:**
   * Reviewer lookup system tracking current active assignments, past performance velocity, and subject matter alignment.
3. **Automated Reminders & Deadline Chaser Engine:**
   * SMTP-integrated cron scheduler issuing early warning, due date, and overdue reminder emails to assigned reviewers.
4. **Decision Synthesis & Dispatcher:**
   * Multi-reviewer score collation matrix automatically populating formal editorial letters (Accept, Minor Revision, Major Revision, Reject) while preserving comment privacy boundaries.

### Module 3: Aries Editorial Manager® Reviewer Workspace *(Completed & Live)*
1. **Split-Screen Workspace:**
   * Drag-resizable split-pane interface (`q-splitter`) synchronizing manuscript document reading and evaluation rubric scoring.
2. **Scientific Rubric & Compliance Questionnaire:**
   * 5-point Likert evaluation matrix across five scientific standards.
   * Reviewer compliance questions covering statistical integrity, language, and conflicts of interest.
3. **Dual-Tier Feedback Isolation:**
   * Strict database-level isolation between Blinded Author Feedback and Confidential Editor Comments.
4. **Triple Zero-Trust Security Gates:**
   * *Device Gate:* Automatic detection and blocking of smartphone viewports (< 1024px) with clipboard link copy functionality.
   * *Direct Access Gate:* Frame-0 solid `#00172B` backdrop blocking unauthenticated domain snooping.
   * *Email Verification Gate:* Cryptographic HMAC-SHA256 signature binding with mandatory email identity verification.
5. **Printable Official Certificate of Reviewing:**
   * Automated generation of formal **Certificate of Peer Review Recognition** bearing institutional publication credentials and DRJ seal.

---

## 3. Technical Deliverables and Acceptance Criteria

| Deliverable | Detailed Requirements | Verification & Acceptance Standard |
| :--- | :--- | :--- |
| **D1: Reviewer Portal SPA (Module 3)** | Vue 3 + Quasar + TypeScript SPA deployed at `review.davaoresearchjournal.ph`. | Verified live on production server with zero build warnings; all 3 security gates active. *(PASSED)* |
| **D2: REST API & Security Bridge** | PHP 8.2 API (`api.php`) with PDO connection to OJS database (`drj2024_35beta`). | Cryptographic HMAC-SHA256 validation confirmed; unauthorized access rejected. *(PASSED)* |
| **D3: Outbound SMTP Integration** | Resend SMTP Relay integrated over Port 465 SSL in `config.inc.php`. | Invitation emails delivered directly to external Gmail/academic inboxes without spam flagging. *(PASSED)* |
| **D4: OJS 3.4 Generic Plugin** | `editorialManagerReview` generic plugin installed in OJS. | Registered in OJS Plugin Gallery; hooks into `LoadHandler` for URL routing. *(PASSED)* |
| **D5: Author Portal (Module 1)** | Guided submission wizard with live file segregation and status tracker. | End-to-end author submission successfully writes into OJS `submissions` table. |
| **D6: Editorial Hub (Module 2)** | Triage dashboard, reviewer workload tracker, automated reminders. | Editors can assign reviewers and synthesize decisions from OJS admin panel. |
| **D7: Technical Documentation & Code** | Full TDD, architectural diagrams, and repository on GitHub. | Clean commit history and comprehensive documentation on GitHub. *(PASSED)* |

---

## 4. Responsibilities of Parties

### 4.1 Responsibilities of the Consultant / Lead Software Architect:
* Design, build, test, and deploy the software modules in accordance with the specifications in this TOR.
* Ensure all code strictly follows secure development practices, avoiding SQL injection, cross-site scripting (XSS), and unauthorized data exposure.
* Provide technical training and system handover documentation to the DRJ editorial board and technical staff.
* Deliver complete source code through the official GitHub repository.

### 4.2 Responsibilities of the Client (DORSU / DRJ):
* Provide necessary cPanel/SSH access to the staging and production VPS hosting environments.
* Ensure the continuity of the core OJS 3.4 server infrastructure and domain/DNS routing.
* Test, evaluate, and formally sign off on milestones within five (5) business days of submission.

---

## 5. Intellectual Property & Code Ownership

1. **Institutional Ownership:** All custom code, plugins, templates, and configurations developed under this contract shall be the property of **Davao Oriental State University (DORSU)** and the **Davao Research Journal (DRJ)**.
2. **Open-Source Compliance:** In accordance with OJS licensing, the custom software shall be governed by the **GNU General Public License v3.0 (GPLv3)**, granting DORSU perpetual, royalty-free rights to modify, deploy, and maintain the software indefinitely.

---

## 6. Milestone Schedule & Payment Terms

Payments shall be released in tranches upon formal written acceptance of verified deliverables:

| Milestone | Deliverables Covered | Percentage | Amount (PHP) |
| :---: | :--- | :---: | :---: |
| **Milestone 1**<br>*(Immediate)* | **Module 3: Peer Review Portal (Vue 3 / Quasar / TypeScript)**<br>• Fully deployed on `review.davaoresearchjournal.ph`<br>• Triple security gates & document streaming<br>• Resend SMTP relay configuration<br>• Technical Design Document & GitHub repo | **40%** | **₱216,000** |
| **Milestone 2**<br>*(Day 45)* | **Module 1: Author Submission & Tracking Portal**<br>• Fast-track guided submission wizard<br>• Blinded file separator & live status tracker | **30%** | **₱162,000** |
| **Milestone 3**<br>*(Day 90)* | **Module 2: Editorial Management & Decision Hub**<br>• Triage Kanban board & automated chaser engine<br>• End-to-end integration simulation<br>• Editorial board training & final handover | **30%** | **₱162,000** |
| **TOTAL:** | **Complete 3-Module Modernization Suite** | **100%** | **₱540,000 PHP** |

---

## 7. Warranty, Support & Service Level Agreement (SLA)

* **Warranty Period:** Six (6) months of warranty support commencing from the date of final system acceptance.
* **Scope of Warranty:** Rapid resolution of any software defects, bugs, regression issues resulting from OJS minor updates, or email deliverability adjustments.
* **Response Times:**
  * *Critical (System Outage / Review Blocked):* Response within 4 hours; resolution within 24 hours.
  * *Standard (Minor UI / Enhancement Requests):* Response within 24 hours; resolution within 72 hours.

---

## 8. Conforme & Signatures

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
