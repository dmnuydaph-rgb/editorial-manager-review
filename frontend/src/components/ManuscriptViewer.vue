<template>
  <div class="column full-height bg-grey-2" style="overflow: hidden;">
    <!-- Top toolbar of manuscript viewer -->
    <div class="bg-blue-grey-9 text-white q-px-md q-py-xs row items-center justify-between no-wrap">
      <div class="row items-center q-gutter-x-sm">
        <q-icon name="menu_book" size="18px" color="amber-4" />
        <span class="text-caption text-weight-bold">
          {{ isPdf ? 'Manuscript PDF Viewer' : 'Word (.docx) Manuscript' }}
        </span>
      </div>

      <div class="row items-center q-gutter-x-xs">
        <q-btn
          flat
          dense
          color="white"
          :icon="isFullscreen ? 'fullscreen_exit' : 'fullscreen'"
          :label="isFullscreen ? 'Exit Fullscreen' : 'Fullscreen'"
          class="text-caption"
          @click="toggleFullscreen"
        />
        <q-btn
          flat
          dense
          color="amber-4"
          icon="download"
          label="Download"
          class="text-caption"
          :disable="!store.activeDocumentUrl"
          @click="downloadFile"
        />
      </div>
    </div>

    <!-- Viewer Body -->
    <div ref="viewerContainer" class="col column full-width" style="overflow: auto;">
      <!-- Case 1: PDF Viewer -->
      <iframe
        v-if="isPdf && store.assignment?.active_pdf_url"
        :src="store.assignment.active_pdf_url"
        class="col full-width border-none"
        style="min-height: 100%; border: none;"
      ></iframe>

      <!-- Case 2: Word .docx Card Preview -->
      <div v-else class="col q-pa-lg flex flex-center">
        <q-card flat bordered class="bg-white q-pa-lg shadow-4 rounded-borders" style="max-width: 650px; width: 100%; border-radius: 12px;">
          <div class="row items-start q-gutter-x-md q-mb-md">
            <q-avatar square size="48px" class="bg-blue-1 text-primary rounded-borders">
              <q-icon name="description" size="28px" />
            </q-avatar>
            <div class="col">
              <q-badge color="primary" class="q-mb-xs text-weight-bold">MANUSCRIPT FOR REVIEW</q-badge>
              <div class="text-subtitle1 text-weight-bolder text-slate-900" style="line-height: 1.3;">
                {{ store.assignment?.title || 'Loading Manuscript Title...' }}
              </div>
            </div>
          </div>

          <q-separator class="q-my-md" />

          <!-- Abstract -->
          <div class="q-mb-md">
            <div class="text-caption text-weight-bold text-uppercase text-grey-8 q-mb-xs">Abstract</div>
            <div
              class="text-body2 text-grey-8 bg-grey-1 q-pa-md rounded-borders"
              style="line-height: 1.6; max-height: 260px; overflow-y: auto;"
              v-html="store.assignment?.abstract || '<em>No abstract available.</em>'"
            ></div>
          </div>

          <!-- Document Download Card -->
          <div class="bg-amber-1 q-pa-md rounded-borders row items-center justify-between" style="border: 1px solid #fde68a;">
            <div class="column">
              <span class="text-caption text-weight-bold text-amber-10">Manuscript Source File:</span>
              <span class="text-caption text-grey-8 text-italic font-mono">
                {{ fileName }}
              </span>
            </div>
            <q-btn
              color="primary"
              icon="download"
              label="Download File"
              class="text-weight-bold text-caption"
              :disable="!store.activeDocumentUrl"
              @click="downloadFile"
            />
          </div>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useReviewStore } from '../stores/reviewStore'

const store = useReviewStore()
const viewerContainer = ref<HTMLElement | null>(null)
const isFullscreen = ref(false)

const isPdf = computed(() => !!store.assignment?.active_pdf_url)
const fileName = computed(() => {
  if (store.assignment?.files && store.assignment.files.length > 0) {
    return store.assignment.files[0].filename
  }
  return 'Manuscript_Proof.docx'
})

function downloadFile() {
  if (store.activeDocumentUrl) {
    window.location.href = store.activeDocumentUrl
  }
}

function toggleFullscreen() {
  if (!viewerContainer.value) return
  if (!document.fullscreenElement) {
    viewerContainer.value.requestFullscreen().then(() => { isFullscreen.value = true })
  } else {
    document.exitFullscreen().then(() => { isFullscreen.value = false })
  }
}
</script>
