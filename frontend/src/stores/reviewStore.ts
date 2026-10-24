import { defineStore } from 'pinia'
import type { ReviewAssignment, EvaluationCriterion, ComplianceQuestion, ReviewDraft, ApiResponse } from '../types/review'

const DEFAULT_CRITERIA: EvaluationCriterion[] = [
  { id: 'crit_originality', label: '1. Originality & Novelty of Work', desc: 'Does the manuscript provide distinct scientific value?' },
  { id: 'crit_rigor', label: '2. Technical Rigor & Experimental Design', desc: 'Is methodology sound, reproducible, and appropriate?' },
  { id: 'crit_clarity', label: '3. Data Presentation & Statistical Clarity', desc: 'Are figures, tables, and statistics lucid and accurate?' },
  { id: 'crit_citations', label: '4. Literature Context & Proper Citations', desc: 'Is background comprehensive and accurately cited?' },
  { id: 'crit_conclusions', label: '5. Validity of Scientific Conclusions', desc: 'Do results directly support the proposed conclusions?' }
]

const DEFAULT_QUESTIONS: ComplianceQuestion[] = [
  {
    id: 'comp_suitable',
    label: '1. Is the subject matter suitable for publication in this journal?',
    options: [{ label: 'Yes', value: 'yes' }, { label: 'No', value: 'no' }]
  },
  {
    id: 'comp_stats',
    label: '2. Are statistical analyses robust and correctly interpreted?',
    options: [{ label: 'Yes', value: 'yes' }, { label: 'Marginal', value: 'marginal' }, { label: 'No', value: 'no' }]
  },
  {
    id: 'comp_language',
    label: '3. Is the language, clarity, and academic writing acceptable?',
    options: [{ label: 'Fluent', value: 'fluent' }, { label: 'Needs Polish', value: 'polish' }, { label: 'Unacceptable', value: 'unacceptable' }]
  },
  {
    id: 'comp_coi',
    label: '4. Are there any competing or financial conflicts of interest?',
    options: [{ label: 'None', value: 'none' }, { label: 'Declared', value: 'declared' }]
  }
]

export const useReviewStore = defineStore('review', {
  state: () => ({
    reviewId: null as number | null,
    token: '' as string,
    verifiedEmail: '' as string,
    emailHint: '' as string,
    detectedJournalName: '' as string,
    
    // Gates
    isDeviceBlocked: false,
    isNoTokenBlocked: false,
    isEmailGateOpen: false,
    gateError: '' as string,
    isGateVerifying: false,

    // Data
    assignment: null as ReviewAssignment | null,
    criteriaList: DEFAULT_CRITERIA,
    questionsList: DEFAULT_QUESTIONS,

    // Form inputs
    criteriaAnswers: {} as Record<string, number>,
    questionAnswers: {} as Record<string, string>,
    authorComments: '',
    editorComments: '',
    recommendation: null as number | null,
    uploadedAttachments: [] as { name: string; url: string; size: string }[],

    // UI state
    saveStatus: 'Ready' as string,
    isSaving: false,
    isSubmitting: false,
    layoutMode: 'split' as 'split' | 'manuscript' | 'rubric',
    splitterModel: 50, // 50% split width
    saveTimer: null as any,

    // Modals
    isSubmitModalOpen: false,
    isCertificateModalOpen: false,
    isExtensionModalOpen: false,
    isDeclineModalOpen: false,
  }),

  getters: {
    hasValidParams(state): boolean {
      return !!state.reviewId && !!state.token
    },
    isUnlocked(state): boolean {
      return !state.isDeviceBlocked && !state.isNoTokenBlocked && !state.isEmailGateOpen && !!state.assignment
    },
    journalName(state): string {
      return state.assignment?.journal?.name || state.detectedJournalName || 'Editorial Manager® Reviewer Portal'
    },
    journalAcronym(state): string {
      return state.assignment?.journal?.acronym || 'EM'
    },
    completionPercentage(state): number {
      let filled = 0
      const total = 5 + 4 + 2 + 1 // 5 criteria + 4 questions + 2 comments + 1 recommendation = 12 items

      state.criteriaList.forEach(c => {
        if (state.criteriaAnswers[c.id]) filled++
      })
      state.questionsList.forEach(q => {
        if (state.questionAnswers[q.id]) filled++
      })
      if (state.authorComments && state.authorComments.trim().length > 10) filled++
      if (state.editorComments && state.editorComments.trim().length > 5) filled++
      if (state.recommendation !== null) filled++

      return Math.round((filled / total) * 100)
    },
    activeDocumentUrl(state): string | null {
      if (!state.assignment) return null
      return state.assignment.active_pdf_url || state.assignment.active_docx_url
    }
  },

  actions: {
    init() {
      // 1. Check Device (mobile screen width < 1024 or mobile UA)
      this.checkDevice()
      window.addEventListener('resize', () => this.checkDevice())

      // 2. Initialize criteria defaults
      this.criteriaList.forEach(c => {
        this.criteriaAnswers[c.id] = 4 // Default Good
      })
      this.questionsList.forEach(q => {
        this.questionAnswers[q.id] = q.options[0].value
      })

      // 3. Parse query params
      const params = new URLSearchParams(window.location.search)
      const rId = params.get('reviewId') || params.get('review_id')
      const tok = params.get('token')

      if (!rId || !tok) {
        this.isNoTokenBlocked = true
        return
      }

      this.reviewId = parseInt(rId, 10)
      this.token = tok

      // 4. Check cached email
      const cached = localStorage.getItem(`drj_reviewer_email_${this.reviewId}`)
      if (cached) {
        this.verifiedEmail = cached
        this.loadAssignment(cached)
      } else {
        this.loadAssignment('')
      }
    },

    checkDevice() {
      const isMobileUA = /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
      const isSmall = window.innerWidth < 1024
      this.isDeviceBlocked = isMobileUA || isSmall
    },

    async loadAssignment(email: string) {
      if (!this.reviewId || !this.token) return
      this.saveStatus = 'Connecting to OJS...'

      try {
        const res = await fetch(`api.php?action=get_assignment&review_id=${this.reviewId}&token=${this.token}&email=${encodeURIComponent(email)}`)
        const data: ApiResponse<ReviewAssignment> = await res.json()

        if (data.requires_email) {
          this.isEmailGateOpen = true
          this.emailHint = data.email_hint || ''
          if (data.journal_name) this.detectedJournalName = data.journal_name
          this.saveStatus = 'Verification Required'
          return
        }

        if (!data.success || !data.data) {
          this.gateError = data.error || 'Failed to authenticate review assignment.'
          this.saveStatus = 'Unauthorized'
          return
        }

        // Verification successful!
        this.assignment = data.data
        this.verifiedEmail = email
        localStorage.setItem(`drj_reviewer_email_${this.reviewId}`, email)
        this.isEmailGateOpen = false
        this.gateError = ''

        // Restore draft if any
        if (this.assignment.draft) {
          this.restoreDraft(this.assignment.draft)
        }

        this.saveStatus = 'Ready'
      } catch (err: any) {
        console.error(err)
        this.saveStatus = 'Connection Error'
      }
    },

    async verifyEmail(email: string) {
      this.isGateVerifying = true
      this.gateError = ''
      try {
        await this.loadAssignment(email.trim().toLowerCase())
      } finally {
        this.isGateVerifying = false
      }
    },

    restoreDraft(draft: ReviewDraft) {
      if (draft.criteria) {
        this.criteriaAnswers = { ...this.criteriaAnswers, ...draft.criteria }
      }
      if (draft.questions) {
        this.questionAnswers = { ...this.questionAnswers, ...draft.questions }
      }
      if (draft.author_comments) this.authorComments = draft.author_comments
      if (draft.editor_comments) this.editorComments = draft.editor_comments
      if (draft.recommendation !== undefined && draft.recommendation !== null) {
        this.recommendation = draft.recommendation
      }
    },

    triggerAutoSave() {
      if (!this.reviewId || !this.token || !this.verifiedEmail || this.assignment?.is_completed) return

      this.isSaving = true
      this.saveStatus = 'Saving draft...'

      clearTimeout(this.saveTimer)
      this.saveTimer = setTimeout(async () => {
        const payload: ReviewDraft = {
          criteria: this.criteriaAnswers,
          questions: this.questionAnswers,
          author_comments: this.authorComments,
          editor_comments: this.editorComments,
          recommendation: this.recommendation || undefined,
          updated_at: new Date().toISOString()
        }

        try {
          const res = await fetch('api.php?action=save_draft', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              review_id: this.reviewId,
              token: this.token,
              email: this.verifiedEmail,
              draft: payload
            })
          })
          const d = await res.json()
          if (d.success) {
            this.saveStatus = 'Saved ' + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' })
          } else {
            this.saveStatus = 'Auto-save failed'
          }
        } catch (e) {
          this.saveStatus = 'Offline'
        } finally {
          this.isSaving = false
        }
      }, 1200)
    },

    async submitFinalReview() {
      if (!this.reviewId || !this.token || !this.verifiedEmail) return false
      this.isSubmitting = true

      try {
        const payload = {
          review_id: this.reviewId,
          token: this.token,
          email: this.verifiedEmail,
          recommendation: this.recommendation,
          comments_for_author: this.authorComments,
          comments_for_editor: this.editorComments,
          criteria_scores: this.criteriaAnswers,
          compliance_responses: this.questionAnswers
        }

        const res = await fetch('api.php?action=submit_review', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })

        const data: ApiResponse = await res.json()
        if (data.success) {
          if (this.assignment) {
            this.assignment.is_completed = true
            this.assignment.date_completed = new Date().toISOString()
          }
          this.isSubmitModalOpen = false
          return true
        } else {
          alert('Submission error: ' + (data.error || 'Unknown error'))
          return false
        }
      } catch (err: any) {
        alert('Network transmission failed. Please try again.')
        return false
      } finally {
        this.isSubmitting = false
      }
    }
  }
})
