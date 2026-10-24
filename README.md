# Davao Research Journal — Editorial Manager® Peer Review Portal

A decoupled, high-performance academic Peer Review Workspace styled after **Aries Editorial Manager® / Elsevier EM**, built with **Vue 3, Quasar Framework, and TypeScript**, integrated directly with **Open Journal Systems (OJS 3.4)**.

---

## 🌟 Key Architecture

```
┌────────────────────────────────────────────────────────┐
│              Davao Research Journal (OJS 3.4)          │
│              https://davaoresearchjournal.ph           │
└───────────────────────────┬────────────────────────────┘
                            │
               ┌────────────┴────────────┐
               ▼                         ▼
┌──────────────────────────────┐ ┌───────────────────────────────────────┐
│     OJS Generic Plugin       │ │    Standalone Review Portal           │
│ plugins/generic/             │ │    https://review.davaoresearchjournal.ph
│ editorialManagerReview       │ │                                       │
│                              │ │  • Vue 3 + Quasar + TypeScript SPA    │
│ • Registers /reviewer-portal │ │  • Device Gate (Mobile block)         │
│ • Deep-links with token      │ │  • Identity Verification Gate         │
└──────────────────────────────┘ │  • Split-Screen Manuscript & Rubrics  │
                                 │  • Real-time Auto-Save Engine         │
                                 │  • Reviewer Certificate Generator     │
                                 └──────────────────┬────────────────────┘
                                                    │
                                                    ▼
                                 ┌───────────────────────────────────────┐
                                 │ REST API Gateway (api.php)            │
                                 │                                       │
                                 │ • Cryptographic HMAC-SHA256 Token     │
                                 │ • Live OJS MySQL Bridge               │
                                 │ • Direct File Streaming (.pdf, .docx) │
                                 └───────────────────────────────────────┘
```

---

## 🚀 Features

### 1. Security & Zero-Trust Gates
* **Device Gate**: Automatically detects mobile devices (< 1024px) and presents a desktop/laptop requirement screen with 1-click clipboard link copying.
* **Direct Access Gate**: Blocks unauthorized visitors attempting to visit `review.davaoresearchjournal.ph` directly without an invitation token.
* **Email Verification Gate**: Conceals the manuscript title, abstract, and files until the reviewer enters the authorized email address matching the assignment.

### 2. Aries Editorial Manager® Workspace
* **Interactive Split-Screen**: Drag-resizable split workspace (`q-splitter`) with manuscript viewer on the left and evaluation form on the right.
* **Manuscript Reader**: High-performance streaming for PDF proofs and Word (.docx) source manuscripts.
* **Scientific Criteria Matrix**: 5-point evaluation scale (Originality, Technical Rigor, Data Presentation, Literature Context, Conclusions).
* **Blinded & Confidential Feedback**: Distinct channels for comments to author versus confidential notes to the Editor-in-Chief.
* **Reviewer Attachments**: Upload marked-up manuscripts (.docx, .pdf) with track changes.
* **Deadline Extension Request**: 1-click extension requests submitted directly to the editorial board.
* **Printable Reviewer Certificate**: Generates an official, stamped **Certificate of Peer Review Recognition** for completed reviews.

---

## 🛠️ Tech Stack

* **Frontend**: Vue 3 (Composition API), Quasar Framework 2.x, TypeScript, Pinia, Vite.
* **Backend**: PHP 8.x REST API (`api.php`) with PDO MySQL connection to OJS 3.4 database.
* **CMS / Journal Platform**: Open Journal Systems (OJS) 3.4.0-7.
* **Email Gateway**: Resend SMTP Relay (Port 465 SSL).

---

## 📦 Directory Structure

```
├── api.php                                 # REST API bridge to OJS 3.4 database & file storage
├── frontend/                               # Vue 3 + Quasar + TypeScript SPA
│   ├── src/
│   │   ├── components/                     # Workspace Header, Manuscript Viewer, Rubric Form, Gates
│   │   ├── stores/                         # Pinia store managing review state & autosave
│   │   ├── types/                          # TypeScript definitions for OJS assignments & rubrics
│   │   ├── App.vue                         # Main layout with Quasar splitter & dialogs
│   │   └── main.ts                         # Quasar & Pinia initialization
│   ├── vite.config.ts                      # Vite configuration with Quasar plugin
│   └── package.json
└── plugins/
    └── generic/
        └── editorialManagerReview/         # OJS 3.4 Generic Plugin
            ├── EditorialManagerReviewPlugin.php
            ├── version.xml
            └── pages/
                └── ReviewerPortalHandler.php
```

---

## 📄 License
Distributed under the GNU General Public License v3.0 (GPLv3).
Copyright (c) 2026 Davao Oriental State University / Davao Research Journal.
