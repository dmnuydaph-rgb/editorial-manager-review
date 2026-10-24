<template>
  <header class="bg-primary text-white shadow-3 z-top" style="border-bottom: 2px solid #D4A017;">
    <!-- Top Bar -->
    <div class="q-px-md q-py-xs row items-center justify-between no-wrap">
      <!-- Left: Journal & MS ID info -->
      <div class="row items-center q-gutter-x-sm no-wrap">
        <q-avatar square size="32px" class="bg-white-10 rounded-borders text-secondary">
          <q-icon name="article" size="20px" />
        </q-avatar>
        <div class="column">
          <div class="row items-center q-gutter-x-xs no-wrap">
            <span class="text-weight-bolder text-secondary text-uppercase text-caption" style="letter-spacing: 0.5px; font-family: serif;">
              Davao Research Journal
            </span>
            <q-badge color="dark" class="text-caption text-weight-bold" style="font-size: 10px;">
              EM Reviewer Central
            </q-badge>
          </div>
          <div class="row items-center q-gutter-x-xs text-caption text-grey-4" style="font-size: 11px;">
            <span class="text-white text-weight-medium">Editorial Manager®</span>
            <span>•</span>
            <span class="text-amber-3 text-weight-bold font-mono">
              MS ID: DRJ-{{ store.assignment ? store.assignment.submission_id : '---' }}
            </span>
            <span>•</span>
            <span class="text-positive text-weight-medium flex items-center q-gutter-x-xs">
              <span class="pulse-dot"></span> OJS Bridge Connected
            </span>
          </div>
        </div>
      </div>

      <!-- Center: Meta badges -->
      <div class="gt-sm row items-center q-gutter-x-sm text-caption text-grey-3" style="font-size: 11px;">
        <q-chip dense dark color="blue-grey-10" text-color="white" icon="category">
          <span class="text-grey-4 q-mr-xs">Section:</span> <strong>{{ store.assignment?.section_title || 'Peer Review Stage' }}</strong>
        </q-chip>
        <q-chip dense dark color="blue-grey-10" text-color="white" icon="event">
          <span class="text-grey-4 q-mr-xs">Due:</span> <strong>{{ store.assignment?.date_due ? store.assignment.date_due.substring(0, 10) : 'Standard 21 Days' }}</strong>
        </q-chip>
      </div>

      <!-- Right: Save status & Reviewer profile -->
      <div class="row items-center q-gutter-x-sm no-wrap">
        <div class="row items-center q-gutter-x-xs text-caption text-grey-3" style="font-size: 11px;">
          <q-spinner-dots v-if="store.isSaving" color="amber" size="14px" />
          <q-icon v-else name="cloud_done" color="positive" size="14px" />
          <span>{{ store.saveStatus }}</span>
        </div>

        <q-separator vertical dark inset />

        <q-btn-dropdown dense flat no-caps color="white" icon="account_circle" :label="store.assignment?.reviewer_name || 'Reviewer'">
          <q-list dense style="min-width: 200px;">
            <q-item-label header class="text-caption">Reviewer Portal Menu</q-item-label>
            <q-item clickable v-close-popup @click="store.isExtensionModalOpen = true">
              <q-item-section avatar><q-icon name="update" color="primary" /></q-item-section>
              <q-item-section class="text-caption">Request Deadline Extension</q-item-section>
            </q-item>
            <q-item clickable v-close-popup @click="store.isDeclineModalOpen = true">
              <q-item-section avatar><q-icon name="cancel" color="negative" /></q-item-section>
              <q-item-section class="text-caption">Decline Review Assignment</q-item-section>
            </q-item>
            <q-separator />
            <q-item clickable v-close-popup href="https://davaoresearchjournal.ph" target="_blank">
              <q-item-section avatar><q-icon name="open_in_new" color="grey-7" /></q-item-section>
              <q-item-section class="text-caption">Davao Research Journal</q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </div>
    </div>

    <!-- Secondary Command Bar -->
    <div class="bg-blue-grey-10 q-px-md q-py-xs row items-center justify-between text-caption" style="border-top: 1px solid rgba(255,255,255,0.1); font-size: 11px;">
      <div class="row items-center q-gutter-x-xs">
        <span class="text-grey-4 text-weight-bold text-uppercase q-mr-xs">Documents:</span>
        <q-btn
          dense
          flat
          color="white"
          icon="file_download"
          label="Download Manuscript Proof"
          class="text-caption text-weight-bold"
          :disable="!store.activeDocumentUrl"
          @click="downloadDocument"
        />
        <q-btn
          dense
          flat
          color="grey-4"
          icon="help_outline"
          label="Reviewer Guidelines"
          class="text-caption"
          @click="openInstructions"
        />
      </div>

      <!-- Layout Switcher -->
      <div class="row items-center q-gutter-x-xs">
        <span class="text-grey-4 q-mr-xs gt-xs">Layout:</span>
        <q-btn-toggle
          v-model="store.layoutMode"
          dense
          rounded
          toggle-color="primary"
          color="grey-9"
          text-color="grey-4"
          :options="[
            { label: 'Manuscript', value: 'manuscript' },
            { label: 'Split (EM Style)', value: 'split' },
            { label: 'Review Form', value: 'rubric' }
          ]"
        />
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { useReviewStore } from '../stores/reviewStore'

const store = useReviewStore()

function downloadDocument() {
  if (store.activeDocumentUrl) {
    window.location.href = store.activeDocumentUrl
  }
}

function openInstructions() {
  alert('DAVAO RESEARCH JOURNAL - REVIEWER GUIDELINES:\n\n1. Maintain confidentiality of all unpublished manuscript contents.\n2. Provide constructive, respectful, and evidence-based critiques.\n3. Identify any suspected plagiarism, duplicate publication, or conflict of interest in the Confidential Comments to Editor.\n4. Complete and submit evaluation within the agreed review timeline.')
}
</script>

<style scoped>
.pulse-dot {
  width: 7px;
  height: 7px;
  background-color: #10b981;
  border-radius: 50%;
  display: inline-block;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
  70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(16, 185, 129, 0); }
  100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
</style>
