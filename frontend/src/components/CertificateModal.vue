<template>
  <q-dialog :model-value="store.isCertificateModalOpen" transition-show="scale" transition-hide="scale">
    <q-card class="bg-white text-slate-800 rounded-borders shadow-24" style="max-width: 680px; width: 100%; border-radius: 16px; overflow: hidden;">
      <!-- Certificate Visual Container (Printable) -->
      <div id="printableCertificate" class="q-pa-xl text-center bg-white" style="border: 12px double #002B49; margin: 16px; position: relative;">
        <!-- Watermark / Seal Icon -->
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.04; pointer-events: none;">
          <q-icon name="workspace_premium" size="300px" color="primary" />
        </div>

        <!-- Top Header -->
        <div class="text-caption text-weight-bold text-secondary text-uppercase font-serif" style="letter-spacing: 2px;">
          Davao Oriental State University • Publication Office
        </div>
        <div class="text-h5 text-weight-bolder text-primary text-uppercase font-serif q-mt-xs" style="letter-spacing: 1.5px;">
          Davao Research Journal
        </div>
        <div class="text-caption text-grey-6 text-italic q-mb-lg">
          ISSN: 2094-814X (Print) • 2984-7540 (Online)
        </div>

        <q-separator color="secondary" inset style="height: 2px; max-width: 250px; margin: 0 auto 24px auto;" />

        <div class="text-h6 text-weight-bold text-slate-800 text-uppercase" style="letter-spacing: 1px;">
          Certificate of Peer Review Recognition
        </div>

        <div class="text-caption text-grey-7 q-mt-md">
          This official recognition is gratefully presented to
        </div>

        <!-- Reviewer Name -->
        <div class="text-h4 text-weight-bolder text-primary font-serif q-my-md" style="border-bottom: 2px solid #D4A017; display: inline-block; padding: 0 32px 4px 32px;">
          {{ store.assignment?.reviewer_name || 'Designated Peer Reviewer' }}
        </div>

        <div class="text-caption text-grey-8 q-mx-auto" style="max-width: 500px; line-height: 1.6;">
          in grateful acknowledgment of your scholarly dedication, rigorous expert critique, and invaluable contribution as an external peer reviewer for manuscript:
        </div>

        <div class="text-subtitle2 text-weight-bolder text-slate-900 q-my-md q-px-lg font-serif text-italic" style="line-height: 1.4;">
          "{{ store.assignment?.title }}"
        </div>

        <div class="row justify-around items-center q-mt-xl text-caption text-grey-8">
          <div class="column items-center">
            <span class="text-weight-bold text-slate-900 font-mono">MS ID: DRJ-{{ store.assignment?.submission_id }}</span>
            <span class="text-grey-6" style="font-size: 11px;">Completed: {{ completionDate }}</span>
          </div>

          <div class="column items-center">
            <span class="text-weight-bold font-serif text-slate-900" style="border-bottom: 1px solid #94a3b8; padding-bottom: 2px; min-width: 160px;">
              Editorial Board
            </span>
            <span class="text-grey-6" style="font-size: 11px;">Davao Research Journal</span>
          </div>
        </div>
      </div>

      <!-- Action Footer -->
      <q-card-actions align="between" class="bg-grey-1 q-px-lg q-py-md">
        <q-btn flat color="grey-7" label="Close" @click="store.isCertificateModalOpen = false" />
        <div class="row q-gutter-x-sm">
          <q-btn color="primary" icon="print" label="Print / Save PDF" @click="printCertificate" />
        </div>
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useReviewStore } from '../stores/reviewStore'

const store = useReviewStore()

const completionDate = computed(() => {
  if (store.assignment?.date_completed) {
    return store.assignment.date_completed.substring(0, 10)
  }
  return new Date().toISOString().substring(0, 10)
})

function printCertificate() {
  window.print()
}
</script>
