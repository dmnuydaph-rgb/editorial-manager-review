<template>
  <!-- Extension Request Dialog -->
  <q-dialog :model-value="store.isExtensionModalOpen" transition-show="scale" transition-hide="scale">
    <q-card class="bg-white text-slate-800 rounded-borders shadow-24" style="max-width: 440px; width: 100%; border-radius: 14px;">
      <q-card-section class="bg-primary text-white q-pa-md row items-center justify-between" style="border-bottom: 3px solid #D4A017;">
        <span class="text-subtitle2 text-weight-bold flex items-center q-gutter-x-xs">
          <q-icon name="update" color="secondary" size="20px" />
          <span>Request Deadline Extension</span>
        </span>
        <q-btn flat round dense icon="close" color="white" @click="store.isExtensionModalOpen = false" />
      </q-card-section>

      <q-card-section class="q-pa-md column q-gutter-y-sm text-caption">
        <div class="text-grey-7">
          Current Due Date: <strong>{{ store.assignment?.date_due ? store.assignment.date_due.substring(0, 10) : 'Standard 21 Days' }}</strong>
        </div>
        <div>
          <label class="text-weight-bold text-grey-8">Requested Additional Days:</label>
          <q-select v-model="extensionDays" :options="[7, 14, 21]" outlined dense emit-value map-options class="q-mt-xs" />
        </div>
        <div>
          <label class="text-weight-bold text-grey-8">Reason for Request (Sent to Editor):</label>
          <q-input v-model="extensionReason" type="textarea" outlined dense rows="3" placeholder="Brief explanation for the editorial office..." class="q-mt-xs" />
        </div>
      </q-card-section>

      <q-card-actions align="right" class="bg-grey-1 q-pa-md">
        <q-btn flat color="grey-7" label="Cancel" @click="store.isExtensionModalOpen = false" />
        <q-btn color="primary" label="Submit Request" @click="submitExtension" />
      </q-card-actions>
    </q-card>
  </q-dialog>

  <!-- Decline Review Dialog -->
  <q-dialog :model-value="store.isDeclineModalOpen" transition-show="scale" transition-hide="scale">
    <q-card class="bg-white text-slate-800 rounded-borders shadow-24" style="max-width: 440px; width: 100%; border-radius: 14px;">
      <q-card-section class="bg-negative text-white q-pa-md row items-center justify-between">
        <span class="text-subtitle2 text-weight-bold flex items-center q-gutter-x-xs">
          <q-icon name="cancel" size="20px" />
          <span>Decline Review Invitation</span>
        </span>
        <q-btn flat round dense icon="close" color="white" @click="store.isDeclineModalOpen = false" />
      </q-card-section>

      <q-card-section class="q-pa-md column q-gutter-y-sm text-caption">
        <div class="text-grey-8">
          Are you sure you wish to decline reviewing <strong>DRJ-{{ store.assignment?.submission_id }}</strong>?
        </div>
        <div>
          <label class="text-weight-bold text-grey-8">Reason for Declining:</label>
          <q-select v-model="declineReason" :options="['Conflict of interest', 'Unavailable / Schedule constraints', 'Outside my specific field of expertise', 'Other']" outlined dense class="q-mt-xs" />
        </div>
      </q-card-section>

      <q-card-actions align="right" class="bg-grey-1 q-pa-md">
        <q-btn flat color="grey-7" label="Cancel" @click="store.isDeclineModalOpen = false" />
        <q-btn color="negative" label="Confirm Decline" @click="confirmDecline" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useReviewStore } from '../stores/reviewStore'

const store = useReviewStore()
const extensionDays = ref(7)
const extensionReason = ref('')
const declineReason = ref('Unavailable / Schedule constraints')

function submitExtension() {
  alert(`Deadline extension of ${extensionDays.value} days requested. The Editorial Office has been notified.`)
  store.isExtensionModalOpen = false
}

function confirmDecline() {
  alert('Review assignment declined. Thank you for notifying the Editorial Office.')
  store.isDeclineModalOpen = false
}
</script>
