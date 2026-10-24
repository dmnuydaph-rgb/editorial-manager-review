# MANUSCI.COM OFFICIAL DOWNLOADS & MARKETPLACE CATALOG PROMPT

> **Instructions for Darryl M. Nuyda:**  
> Use the prompt below to generate and build the complete **Manusci Downloads & Plugin Marketplace Page** (`https://manusci.com/downloads` or `https://manusci.com/plugins`). You can feed this prompt directly into your AI assistant or development team.

---

```markdown
# TASK: Build the Official Manusci.Com Plugin & Theme Marketplace Download Page

You are an expert frontend engineer and UI/UX designer. Your objective is to design and build a modern, high-converting, publication-grade **Plugin, Theme & Editorial Suite Marketplace Catalog Page** for **Manusci (https://manusci.com)**.

This page showcases Manusci's complete ecosystem of enterprise-grade plugins, custom themes, and decoupled workstations built specifically for **Open Journal Systems (OJS 3.4+ & 3.5+)**.

---

## 1. BRANDING & VISUAL IDENTITY
* **Company:** Manusci (Academic Publishing Software & Editorial Engineering)
* **Design Language:** Modern enterprise SaaS (Stripe/Tailwind style). Clean slate backgrounds (`#0F172A`), deep navy headers (`#002B49`), electric indigo/emerald accents (`#6366F1` / `#10B981`), high-contrast typography, and subtle border glows.
* **Header Banner:**
  * Title: **Manusci Open Journal Systems (OJS) Plugin & Theme Suite**
  * Subtitle: *Enterprise-grade, zero-bloat, production-ready plugins and modern academic themes engineered for OJS 3.4 and OJS 3.5.*
  * Badges: `100% Client Zero-Node Guarantee` • `OJS 3.4.x & 3.5.x Compatible` • `GPLv3 Open Source & Enterprise Support`

---

## 2. CATALOG PRODUCTS & DELIVERABLES TO DISPLAY

The page must feature all six (6) core plugins and custom themes with dedicated product cards, release tags, and direct download links:

### 🌟 FLAGSHIP WORKFLOW SUITE
#### 1. Editorial Manager® Peer Review Workstation (Universal EM Suite)
* **Category:** Workflow & Peer Review
* **Release Package:** `editorialmanagerreview-1.0.0.tar.gz`
* **GitHub:** `https://github.com/dmnuydaph-rgb/editorial-manager-review`
* **Key Features:**
  * Decoupled split-screen manuscript evaluation environment (`q-splitter`).
  * Triple Zero-Trust Security Gates: Device Gate (mobile blocker), Direct Access Gate (frame-0 solid backdrop), and Email Verification Challenge (HMAC-SHA256).
  * 5-point scientific Likert criteria matrix + compliance questionnaire.
  * Real-time debounced draft autosaving.
  * Stamped & printable **Certificate of Peer Review Recognition**.
  * Dynamic multi-tenant journal branding (automatically detects journal name, ISSN, and acronym).
* **Compatibility:** OJS 3.4.x, OJS 3.5.x • PHP 8.1 - 8.3
* **Badges:** `Flagship Suite` • `Zero-Trust Security` • `Mobile Gate Protected`

---

### 📚 CITATION & INDEXING PLUGINS
#### 2. Crossref Reference Linker
* **Category:** Citations & Indexing
* **Release Package:** `crossrefreferencelinker-1.0.0.tar.gz`
* **GitHub:** `https://github.com/dmnuydaph-rgb/ojs-plugins`
* **Key Features:**
  * Automated batch bibliographic reference parsing.
  * Live Crossref REST API resolution and DOI matching.
  * Official Crossref DOI Resources schema 4.3.0 (`<doi_citations>`) XML generator.
  * Direct asynchronous HTTP deposit to Crossref via `doDOICitUpload` using saved OJS credentials.
  * Automatic workspace reset upon confirmed deposit.
* **Compatibility:** OJS 3.4.x, OJS 3.5.x • PHP 8.1+

#### 3. CrossMark XML Generator
* **Category:** Citations & Indexing
* **Release Package:** `crossmarkxmlgenerator-1.0.0.tar.gz`
* **GitHub:** `https://github.com/dmnuydaph-rgb/ojs-plugins`
* **Key Features:**
  * Generates official CrossMark 2.0 compliant XML (Crossref schema 4.3.6 & 5.3.1).
  * Embedded reactive manager dashboard with search, issue filters, and batch status badges.
  * One-click in-browser XML syntax preview and instant file export.
* **Compatibility:** OJS 3.4.x, OJS 3.5.x • PHP 8.1+

---

### 📊 METRICS, VISIBILITY & GOVERNANCE PLUGINS
#### 4. Article Statistics Plugin
* **Category:** Analytics & Readership
* **Release Package:** `articlestatistics-1.0.0.tar.gz`
* **GitHub:** `https://github.com/dmnuydaph-rgb/ojs-plugins`
* **Key Features:**
  * 100% standalone and independent: relies solely on OJS's internal `metrics_submission` records with zero external API calls.
  * Displays verified Abstract views, PDF galley views, and Total views.
  * Pure inline SVG daily activity chart (renders vector graph from PHP with zero external Chart.js/D3 dependencies).
  * Built-in 1-hour caching using `\Illuminate\Support\Facades\Cache`.
* **Compatibility:** OJS 3.4.x, OJS 3.5.x • PHP 8.1+

#### 5. Editorial Board Suite
* **Category:** Governance & Transparency
* **Release Package:** `editorialboard-1.0.0.tar.gz`
* **GitHub:** `https://github.com/dmnuydaph-rgb/ojs-plugins`
* **Key Features:**
  * Complete OJS Dashboard admin suite (`Settings > Website > Editorial Board Suite`).
  * Dynamic public masthead page with live category filter chips and instant type-to-filter search.
  * Academic profile modals displaying biographies, affiliations, and verified links to ORCID, Scopus, Google Scholar, and ResearchGate.
  * Automated database schema migration (`editorial_board_members` table).
* **Compatibility:** OJS 3.4.x, OJS 3.5.x • PHP 8.1+

#### 6. Open Impact Score Ecosystem
* **Category:** Open Science Metrics
* **Key Features:**
  * Transparent, auditable alternative to closed commercial indices (Scopus/Web of Science).
  * Direct OpenAlex API integration with polite-pool rate limiting and self-citation filtering.
  * Composite scoring algorithm factoring citation recency, diversity, and cartel protection.
* **Compatibility:** Standalone Microservice / OJS Bridge

---

### 🎨 ACADEMIC THEMES
#### 7. Modern Academic Responsive Journal Theme
* **Category:** Themes & Frontend
* **Release Package:** `manusci-academic-theme-1.0.0.tar.gz`
* **Key Features:**
  * High-density academic typography optimized for reading scientific abstracts and citations.
  * Fully responsive mobile, tablet, and desktop navigation with off-canvas drawers.
  * Built-in sticky header, dark mode support, and accessibility (WCAG 2.1 AA) compliance.
* **Compatibility:** OJS 3.4.x, OJS 3.5.x

---

## 3. INTERACTIVE PAGE FUNCTIONALITIES
1. **Filter Tabs:** `All` • `Workflow & Peer Review` • `Citations & Indexing` • `Metrics & Analytics` • `Themes`.
2. **Instant Search Bar:** Filter cards dynamically by keyword (e.g. "Crossref", "Review", "Statistics", "ORCID").
3. **Card Actions:**
   * Primary Button: `[ ⬇️ Download .tar.gz ]` (Direct release package download).
   * Secondary Button: `[ 📖 Documentation ]` / `[ 💻 GitHub ]`.
   * Live Demo Link: `[ 👁️ Live Demo ]` (links to live instance).
4. **Interactive "How to Install" Modal:**
   * Tab 1: Web Admin Upload (Step-by-step OJS Dashboard upload via *Settings > Website > Plugins > Upload A New Plugin*).
   * Tab 2: SFTP / Server Deployment (`/home/{user}/public_html/plugins/generic/` extract instructions).
5. **Client Guarantee Callout:**
   * *"Zero Client Node.js Dependency: All web assets and reactive components are pre-compiled into native PHP bundles. Your OJS server does not require Node.js, npm, Docker, or external daemons."*

---

## 4. CODE IMPLEMENTATION GUIDELINES
* Write clean, accessible, modern HTML5/CSS3 (or Vue/Nuxt/React component depending on the Manusci stack).
* Use responsive CSS Grid (`grid-template-columns: repeat(auto-fit, minmax(360px, 1fr))`).
* Ensure SEO metadata, OpenGraph tags, and Schema.org `SoftwareApplication` JSON-LD structured data are included for high search engine visibility.
```
