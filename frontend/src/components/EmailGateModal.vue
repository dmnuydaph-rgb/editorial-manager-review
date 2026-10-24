<template>
  <q-dialog :model-value="store.isEmailGateOpen" persistent transition-show="scale" transition-hide="scale">
    <q-card class="bg-white text-slate-800 rounded-borders shadow-24" style="max-width: 460px; width: 100%; border-radius: 16px; overflow: hidden;">
      <!-- Header -->
      <div class="bg-primary text-white text-center q-pa-lg" style="border-bottom: 4px solid #D4A017;">
        <div class="q-mx-auto flex flex-center rounded-borders text-secondary" style="width: 56px; height: 56px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%;">
          <q-icon name="verified_user" size="30px" />
        </div>
        <div class="text-subtitle1 text-weight-bolder text-secondary q-mt-sm text-uppercase" style="letter-spacing: 1px; font-family: serif;">
          Davao Research Journal
        </div>
        <div class="text-caption text-grey-4">
          Editorial Manager® Identity Verification Gate
        </div>
      </div>

      <!-- Body -->
      <q-card-section class="q-pa-lg column q-gutter-y-md text-body2">
        <div class="bg-amber-1 text-amber-10 q-pa-md rounded-borders text-caption" style="border: 1px solid #fde68a; line-height: 1.6;">
          <strong>Confidential Manuscript Access:</strong><br>
          This workspace contains confidential, unpublished scientific research. To access this manuscript, please enter the authorized email address where your review invitation was received.
        </div>

        <!-- Input -->
        <div class="column q-gutter-y-xs">
          <label class="text-caption text-weight-bold text-grey-9 text-uppercase" style="font-size: 11px;">
            Reviewer Email Address:
          </label>
          <q-input
            v-model="emailInput"
            outlined
            dense
            type="email"
            placeholder="Enter your email address..."
            class="text-caption"
            :disable="store.isGateVerifying"
            @keydown.enter="submitVerification"
          />
          <div v-if="store.emailHint" class="text-caption text-grey-6 text-italic" style="font-size: 11px;">
            Invitation sent to: {{ store.emailHint }}
          </div>
        </div>

        <!-- Error Alert -->
        <q-banner v-if="store.gateError" inline-actions class="bg-red-1 text-negative rounded-borders q-pa-sm text-caption text-weight-medium" style="border: 1px solid #fca5a5;">
          {{ store.gateError }}
        </q-banner>

        <!-- Submit Button -->
        <q-btn
          color="primary"
          class="full-width text-weight-bold text-caption q-py-sm"
          :loading="store.isGateVerifying"
          icon="lock_open"
          label="Verify & Unlock Workspace"
          @click="submitVerification"
        />

        <div class="text-center text-caption text-grey-6 q-pt-sm" style="border-top: 1px solid #f1f5f9; font-size: 11px;">
          If you are not the designated reviewer, please contact the <a href="mailto:editor@davaoresearchjournal.ph" class="text-primary text-weight-bold">Editorial Office</a>.
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useReviewStore } from '../stores/reviewStore'

const store = useReviewStore()
const emailInput = ref('')

function submitVerification() {
  const email = emailInput.value.trim()
  if (!email || !email.includes('@')) {
    store.gateError = 'Please enter a valid email address.'
    return
  }
  store.verifyEmail(email)
}
</script>
