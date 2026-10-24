<template>
  <q-dialog :model-value="store.isDeviceBlocked" persistent maximized transition-show="fade" transition-hide="fade">
    <div class="row items-center justify-center full-width full-height bg-slate-950-backdrop q-pa-md">
      <q-card class="bg-white text-slate-800 rounded-borders shadow-24" style="max-width: 440px; width: 100%; border-radius: 16px; overflow: hidden;">
        <!-- Header -->
        <div class="bg-primary text-white text-center q-pa-lg" style="border-bottom: 4px solid #D4A017;">
          <div class="q-mx-auto flex flex-center rounded-borders text-secondary" style="width: 60px; height: 60px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%;">
            <q-icon name="laptop_mac" size="32px" />
          </div>
          <div class="text-subtitle1 text-weight-bolder text-secondary q-mt-sm text-uppercase" style="letter-spacing: 1px; font-family: serif;">
            {{ store.journalName }}
          </div>
          <div class="text-caption text-grey-4">
            Editorial Manager® Peer Review Workstation
          </div>
        </div>

        <!-- Body -->
        <q-card-section class="q-pa-md column q-gutter-y-sm text-body2">
          <div class="text-center">
            <q-badge color="amber-2" text-color="amber-10" class="q-px-sm q-py-xs text-weight-bold" style="border: 1px solid #fcd34d;">
              📱 Mobile Device Detected
            </q-badge>
            <div class="text-h6 text-weight-bold text-slate-900 q-mt-xs">
              Desktop or Laptop Required
            </div>
          </div>

          <div class="text-grey-7 text-center text-caption q-px-sm" style="line-height: 1.5;">
            This workspace features an interactive <strong>split-screen environment</strong> with synchronized manuscript document rendering, full-text navigation, and multi-criteria scoring rubrics.
          </div>

          <q-card flat bordered class="bg-grey-1 q-pa-sm rounded-borders text-caption text-grey-8">
            <div class="text-weight-bold q-mb-xs flex items-center q-gutter-x-xs">
              <q-icon name="settings" size="14px" color="primary" />
              <span>Display Requirements:</span>
            </div>
            <div class="q-pl-sm">
              <div>✓ Desktop, Laptop, or Tablet (Landscape)</div>
              <div>✓ Minimum screen resolution: <strong>1024 × 768</strong></div>
              <div>✓ Modern web browser (Chrome, Edge, Safari, Firefox)</div>
            </div>
          </q-card>

          <!-- Copy Button -->
          <div class="q-mt-sm">
            <q-btn
              color="primary"
              class="full-width text-weight-bold text-caption q-py-sm"
              :icon="copied ? 'check_circle' : 'content_copy'"
              :label="copied ? 'Copied to Clipboard!' : 'Copy Review Link to Open on PC'"
              @click="copyLink"
            />
            <div v-if="copied" class="text-center text-positive text-caption text-weight-bold q-mt-xs animate-fade">
              ✓ Paste into an email or messaging app to open on your computer.
            </div>
          </div>

          <div class="text-center text-caption text-grey-6 q-mt-xs q-pt-sm" style="border-top: 1px solid #f1f5f9;">
            Need help? Contact the <a href="mailto:editor@davaoresearchjournal.ph" class="text-primary text-weight-bold">Editorial Office</a>.
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
const copied = ref(false)

function copyLink() {
  const url = window.location.href
  navigator.clipboard.writeText(url).then(() => {
    copied.value = true
    setTimeout(() => { copied.value = false }, 3500)
  }).catch(() => {
    prompt('Copy your review link:', url)
  })
}
</script>

<style scoped>
.bg-slate-950-backdrop {
  background-color: #00172B !important;
}
</style>
