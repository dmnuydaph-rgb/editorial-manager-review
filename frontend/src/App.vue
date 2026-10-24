<template>
  <div v-if="!store.isUnlocked" class="full-height full-width flex flex-center select-none font-sans" style="background-color: #00172B; height: 100vh; width: 100vw; overflow: hidden;">
    <!-- Gates (Only one active at a time) -->
    <DeviceGate v-if="store.isDeviceBlocked" />
    <InvitationGate v-else-if="store.isNoTokenBlocked" />
    <EmailGateModal v-else-if="store.isEmailGateOpen" />
    
    <!-- Loading spinner during initial assignment fetch -->
    <div v-else class="column items-center q-gutter-y-md text-white">
      <q-spinner-orbit color="amber-4" size="56px" />
      <div class="text-caption text-grey-4">Connecting to Editorial Manager Workspace...</div>
    </div>
  </div>

  <q-layout v-else view="hHh lpR fFf" class="bg-slate-100 full-height overflow-hidden select-none font-sans">
    <!-- Modals -->
    <SubmitConfirmModal />
    <CertificateModal />
    <ExtensionModal />

    <!-- Workspace Header -->
    <q-header elevated class="bg-primary">
      <WorkspaceHeader />
    </q-header>

    <!-- Workspace Body -->
    <q-page-container class="full-height overflow-hidden">
      <q-page class="full-height column overflow-hidden">
        <!-- Layout Mode: Split (EM Style) -->
        <q-splitter
          v-if="store.layoutMode === 'split'"
          v-model="store.splitterModel"
          :limits="[30, 70]"
          class="col full-width full-height"
          separator-class="bg-blue-grey-2 hover-separator"
          style="height: 100%;"
        >
          <template v-slot:before>
            <ManuscriptViewer class="full-height" />
          </template>

          <template v-slot:after>
            <ReviewerEvaluationForm class="full-height" />
          </template>
        </q-splitter>

        <!-- Layout Mode: Manuscript Only -->
        <div v-else-if="store.layoutMode === 'manuscript'" class="col full-width full-height">
          <ManuscriptViewer class="full-height" />
        </div>

        <!-- Layout Mode: Rubric Only -->
        <div v-else class="col full-width full-height">
          <ReviewerEvaluationForm class="full-height" />
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useReviewStore } from './stores/reviewStore'

import DeviceGate from './components/DeviceGate.vue'
import InvitationGate from './components/InvitationGate.vue'
import EmailGateModal from './components/EmailGateModal.vue'
import WorkspaceHeader from './components/WorkspaceHeader.vue'
import ManuscriptViewer from './components/ManuscriptViewer.vue'
import ReviewerEvaluationForm from './components/ReviewerEvaluationForm.vue'
import SubmitConfirmModal from './components/SubmitConfirmModal.vue'
import CertificateModal from './components/CertificateModal.vue'
import ExtensionModal from './components/ExtensionModal.vue'

const store = useReviewStore()

onMounted(() => {
  store.init()
})
</script>

<style>
/* Global resets & scrollbars */
html, body, #app {
  height: 100%;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #00172B;
}

::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}
::-webkit-scrollbar-track {
  background: #f1f5f9;
}
::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.hover-separator {
  width: 6px;
  transition: background 0.2s;
}
.hover-separator:hover {
  background-color: #002B49 !important;
}
</style>
