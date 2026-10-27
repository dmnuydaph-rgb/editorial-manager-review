<template>
  <q-dialog :model-value="store.isNoTokenBlocked" persistent maximized transition-show="fade" transition-hide="fade">
    <div class="row items-center justify-center full-width full-height bg-slate-950-backdrop q-pa-md">
      <q-card class="bg-white text-slate-800 rounded-borders shadow-24" style="max-width: 500px; width: 100%; border-radius: 16px; overflow: hidden;">
        <!-- Header -->
        <div class="bg-primary text-white text-center q-pa-lg" style="border-bottom: 4px solid #D4A017;">
          <div class="q-mx-auto flex flex-center rounded-borders text-secondary" style="width: 64px; height: 64px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%;">
            <q-icon name="lock" size="32px" />
          </div>
          <div class="text-h6 text-weight-bolder text-secondary q-mt-sm text-uppercase" style="letter-spacing: 1px; font-family: serif;">
            {{ store.journalName }}
          </div>
          <div class="text-caption text-grey-4">
            Editorial Manager® Peer Review Portal
          </div>
        </div>

        <!-- Body -->
        <q-card-section class="q-pa-lg column q-gutter-y-md text-body2">
          <div class="text-center">
            <q-badge color="amber-1" text-color="amber-10" class="q-px-sm q-py-xs text-weight-bold" style="border: 1px solid #fcd34d;">
              🛡️ Direct Access Restricted
            </q-badge>
            <div class="text-h6 text-weight-bold text-slate-900 q-mt-xs">
              Reviewer Invitation Required
            </div>
          </div>

          <div class="bg-grey-1 text-grey-8 q-pa-md rounded-borders text-caption" style="border: 1px solid #e2e8f0; line-height: 1.6;">
            Access to the manuscript evaluation workspace is by <strong>confidential editorial invitation only</strong>. To review an assigned submission, please click the secure link contained in your formal peer review invitation email.
          </div>

          <!-- Paste Link Input -->
          <div class="column q-gutter-y-xs">
            <div class="text-caption text-weight-bold text-grey-8 text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">
              Have an Invitation Link or Token?
            </div>
            <div class="row q-gutter-x-sm">
              <q-input
                v-model="manualLink"
                outlined
                dense
                placeholder="Paste full invitation URL or parameters here..."
                class="col text-caption"
                @keydown.enter="handleManualSubmit"
              />
              <q-btn
                color="primary"
                label="Access"
                dense
                class="q-px-md text-weight-bold text-caption"
                @click="handleManualSubmit"
              />
            </div>
            <div v-if="linkError" class="text-negative text-caption text-weight-bold q-mt-xs">
              {{ linkError }}
            </div>
          </div>

          <!-- Action Link Back to DRJ -->
          <div class="q-pt-sm">
            <q-btn
              flat
              bordered
              class="full-width bg-grey-2 text-grey-8 text-weight-bold text-caption q-py-sm"
              href="https://philair.org"
              label="← Return to Philair.Org Homepage"
            />
          </div>

          <div class="text-center text-caption text-grey-6 q-pt-sm" style="border-top: 1px solid #f1f5f9;">
            Questions? Contact the <a href="mailto:editor@philair.org" class="text-primary text-weight-bold">Editorial Office</a>.
          </div>
        </q-card-section>
      </q-card>
    </div>
  </q-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useReviewStore } from '../stores/reviewStore'

const store = useReviewStore()
const manualLink = ref('')
const linkError = ref('')

function handleManualSubmit() {
  const val = manualLink.value.trim()
  if (!val) {
    linkError.value = 'Please enter an invitation link or token.'
    return
  }

  if (val.includes('reviewId=') && val.includes('token=')) {
    if (val.startsWith('http://') || val.startsWith('https://')) {
      window.location.href = val
    } else if (val.startsWith('?')) {
      window.location.href = window.location.pathname + val
    } else {
      window.location.href = window.location.pathname + '?' + val
    }
  } else {
    linkError.value = 'Invalid format. Please paste the complete review link from your email.'
  }
}
</script>

<style scoped>
.bg-slate-950-backdrop {
  background-color: #00172B !important;
}
</style>
