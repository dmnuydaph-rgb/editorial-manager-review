<template>
  <q-dialog :model-value="store.isSubmitModalOpen" persistent transition-show="scale" transition-hide="scale">
    <q-card class="bg-white text-slate-800 rounded-borders shadow-24" style="max-width: 500px; width: 100%; border-radius: 16px; overflow: hidden;">
      <!-- Header -->
      <div class="bg-primary text-white q-pa-lg text-center" style="border-bottom: 4px solid #D4A017;">
        <div class="q-mx-auto flex flex-center rounded-borders text-secondary" style="width: 56px; height: 56px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%;">
          <q-icon name="send" size="28px" />
        </div>
        <div class="text-subtitle1 text-weight-bolder text-secondary q-mt-sm text-uppercase" style="letter-spacing: 0.5px; font-family: serif;">
          Confirm Review Submission
        </div>
        <div class="text-caption text-grey-4">
          {{ store.journalName }} • Editorial Manager® System
        </div>
      </div>

      <!-- Content -->
      <q-card-section class="q-pa-lg column q-gutter-y-md text-body2">
        <div class="bg-amber-1 text-amber-10 q-pa-md rounded-borders text-caption" style="border: 1px solid #fde68a;">
          <strong>Final Review Transmittal:</strong><br>
          You are about to transmit your official evaluation to the {{ store.journalName }} Editorial Board. Once submitted, your scores and comments will be permanently logged in OJS and cannot be edited.
        </div>

        <!-- Summary -->
        <q-card flat bordered class="bg-grey-1 q-pa-md rounded-borders column q-gutter-y-xs text-caption">
          <div class="row justify-between">
            <span class="text-grey-7">Manuscript:</span>
            <strong class="text-slate-900">{{ store.journalAcronym }}-{{ store.assignment?.submission_id }}</strong>
          </div>
          <div class="row justify-between">
            <span class="text-grey-7">Recommendation:</span>
            <strong class="text-primary">{{ recommendationLabel }}</strong>
          </div>
          <div class="row justify-between">
            <span class="text-grey-7">Blinded Author Feedback:</span>
            <strong class="text-positive">✓ Entered ({{ store.authorComments.length }} chars)</strong>
          </div>
          <div class="row justify-between">
            <span class="text-grey-7">Confidential Editor Notes:</span>
            <strong :class="store.editorComments ? 'text-positive' : 'text-grey-6'">
              {{ store.editorComments ? '✓ Entered' : 'None' }}
            </strong>
          </div>
        </q-card>

        <!-- Actions -->
        <div class="row q-gutter-x-sm q-pt-sm">
          <q-btn
            flat
            color="grey-7"
            label="Cancel / Continue Editing"
            class="col text-caption text-weight-bold"
            :disable="store.isSubmitting"
            @click="store.isSubmitModalOpen = false"
          />
          <q-btn
            color="primary"
            class="col text-caption text-weight-bold"
            :loading="store.isSubmitting"
            icon="verified"
            label="Confirm & Transmit to Editor"
            @click="confirmSubmit"
          />
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useReviewStore } from '../stores/reviewStore'

const store = useReviewStore()

const recommendationLabel = computed(() => {
  switch (store.recommendation) {
    case 1: return 'Accept Submission'
    case 2: return 'Revisions Required (Minor)'
    case 3: return 'Resubmit for Review (Major)'
    case 4: return 'Decline Submission (Reject)'
    case 5: return 'See Comments Below'
    default: return 'None selected'
  }
})

async function confirmSubmit() {
  const ok = await store.submitFinalReview()
  if (ok) {
    store.isCertificateModalOpen = true
  }
}
</script>
