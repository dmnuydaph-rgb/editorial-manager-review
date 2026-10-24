<template>
  <div class="column full-height bg-white" style="overflow: hidden;">
    <!-- Evaluation Header & Progress -->
    <div class="q-px-md q-py-sm bg-grey-1 row items-center justify-between no-wrap" style="border-bottom: 1px solid #e2e8f0;">
      <div class="column">
        <span class="text-subtitle2 text-weight-bolder text-slate-900">
          REVIEWER EVALUATION FORM
        </span>
        <span class="text-caption text-grey-6" style="font-size: 11px;">
          Aries Editorial Manager® Standard Evaluation Rubric
        </span>
      </div>

      <div class="row items-center q-gutter-x-sm">
        <span class="text-caption text-weight-bold text-grey-8" style="font-size: 11px;">
          Form Progress:
        </span>
        <div style="width: 120px;">
          <q-linear-progress
            :value="store.completionPercentage / 100"
            color="primary"
            track-color="grey-3"
            rounded
            size="8px"
          />
        </div>
        <span class="text-caption text-weight-bolder text-primary font-mono" style="font-size: 11px;">
          {{ store.completionPercentage }}%
        </span>
      </div>
    </div>

    <!-- Scrollable Form Body -->
    <div class="col q-pa-md scroll column q-gutter-y-lg">
      <!-- Completed Review Banner -->
      <q-banner v-if="store.assignment?.is_completed" rounded class="bg-positive text-white q-pa-md shadow-2">
        <template v-slot:avatar>
          <q-icon name="check_circle" size="32px" />
        </template>
        <div class="text-weight-bold text-subtitle2">
          Review Successfully Finalized & Transmitted to Editor
        </div>
        <div class="text-caption">
          Submitted on {{ store.assignment.date_completed ? store.assignment.date_completed.substring(0, 10) : 'Recorded' }}. The editorial office has received your recommendation.
        </div>
        <template v-slot:action>
          <q-btn
            flat
            color="white"
            class="bg-white-20 text-weight-bold text-caption"
            icon="workspace_premium"
            label="Download Certificate"
            @click="store.isCertificateModalOpen = true"
          />
        </template>
      </q-banner>

      <!-- Section I: Criteria Matrix -->
      <q-card flat bordered class="rounded-borders">
        <q-card-section class="bg-blue-grey-1 q-py-xs q-px-md row items-center justify-between">
          <div class="text-caption text-weight-bold text-slate-900">
            PART I: SCIENTIFIC CRITERIA EVALUATION MATRIX
          </div>
          <div class="text-caption text-grey-6" style="font-size: 10px;">
            Scale: 1 (Poor) to 5 (Outstanding)
          </div>
        </q-card-section>

        <q-markup-table dense flat class="em-table">
          <thead>
            <tr class="bg-grey-2 text-grey-8 text-caption">
              <th class="text-left" style="width: 55%;">Evaluation Criterion</th>
              <th class="text-center" style="width: 9%;">1<br><span style="font-size: 9px;">Poor</span></th>
              <th class="text-center" style="width: 9%;">2<br><span style="font-size: 9px;">Fair</span></th>
              <th class="text-center" style="width: 9%;">3<br><span style="font-size: 9px;">Good</span></th>
              <th class="text-center" style="width: 9%;">4<br><span style="font-size: 9px;">V. Good</span></th>
              <th class="text-center" style="width: 9%;">5<br><span style="font-size: 9px;">Outst.</span></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in store.criteriaList" :key="c.id" class="hover-bg">
              <td class="text-left q-py-sm">
                <div class="text-weight-bold text-slate-800 text-caption">{{ c.label }}</div>
                <div class="text-grey-6" style="font-size: 11px;">{{ c.desc }}</div>
              </td>
              <td v-for="val in [1, 2, 3, 4, 5]" :key="val" class="text-center">
                <q-radio
                  v-model="store.criteriaAnswers[c.id]"
                  :val="val"
                  dense
                  color="primary"
                  :disable="store.assignment?.is_completed"
                  @update:model-value="store.triggerAutoSave"
                />
              </td>
            </tr>
          </tbody>
        </q-markup-table>
      </q-card>

      <!-- Section II: Questionnaire -->
      <q-card flat bordered class="rounded-borders">
        <q-card-section class="bg-blue-grey-1 q-py-xs q-px-md">
          <span class="text-caption text-weight-bold text-slate-900">
            PART II: REVIEWER QUESTIONNAIRE & COMPLIANCE
          </span>
        </q-card-section>
        <q-card-section class="column q-gutter-y-sm q-pa-md">
          <div v-for="q in store.questionsList" :key="q.id" class="row items-center justify-between q-py-xs border-bottom">
            <span class="text-caption text-weight-medium text-slate-800 col-7">
              {{ q.label }}
            </span>
            <div class="row q-gutter-x-md">
              <q-radio
                v-for="opt in q.options"
                :key="opt.value"
                v-model="store.questionAnswers[q.id]"
                :val="opt.value"
                :label="opt.label"
                dense
                color="primary"
                :disable="store.assignment?.is_completed"
                @update:model-value="store.triggerAutoSave"
              />
            </div>
          </div>
        </q-card-section>
      </q-card>

      <!-- Section III: Blinded Comments for Author -->
      <q-card flat bordered class="rounded-borders">
        <q-card-section class="bg-blue-grey-1 q-py-xs q-px-md row items-center justify-between">
          <span class="text-caption text-weight-bold text-slate-900 flex items-center q-gutter-x-xs">
            <q-icon name="edit_note" color="primary" size="18px" />
            <span>BLINDED COMMENTS FOR THE AUTHOR(S)</span>
            <span class="text-negative text-weight-bold">*</span>
          </span>
          <span class="text-caption text-grey-6" style="font-size: 11px;">
            Transmitted directly to authors upon decision
          </span>
        </q-card-section>
        <q-card-section class="q-pa-md">
          <div class="text-caption text-grey-7 q-mb-xs" style="font-size: 11px;">
            Please structure feedback with Major Strengths, Major Concerns (methodology, theory, data), and Minor Suggestions (figures, citations).
          </div>
          <q-input
            v-model="store.authorComments"
            type="textarea"
            outlined
            dense
            rows="6"
            placeholder="Enter structured constructive comments for the author(s)..."
            :disable="store.assignment?.is_completed"
            @update:model-value="store.triggerAutoSave"
          />
        </q-card-section>
      </q-card>

      <!-- Section IV: Confidential Comments to Editor -->
      <q-card flat bordered class="rounded-borders" style="border: 2px solid #fbbf24;">
        <q-card-section class="bg-amber-1 q-py-xs q-px-md row items-center justify-between">
          <span class="text-caption text-weight-bold text-amber-10 flex items-center q-gutter-x-xs">
            <q-icon name="lock" color="amber-9" size="16px" />
            <span>CONFIDENTIAL COMMENTS TO THE EDITOR-IN-CHIEF</span>
          </span>
          <span class="text-caption text-amber-9 text-weight-bold" style="font-size: 11px;">
            Strictly NOT revealed to authors
          </span>
        </q-card-section>
        <q-card-section class="q-pa-md">
          <q-input
            v-model="store.editorComments"
            type="textarea"
            outlined
            dense
            rows="4"
            placeholder="Confidential assessment, concerns regarding ethical oversight, suspected overlap, or sensitive feedback for the Editor..."
            :disable="store.assignment?.is_completed"
            @update:model-value="store.triggerAutoSave"
          />
        </q-card-section>
      </q-card>

      <!-- Section V: File Attachments -->
      <q-card flat bordered class="rounded-borders">
        <q-card-section class="bg-blue-grey-1 q-py-xs q-px-md row items-center justify-between">
          <span class="text-caption text-weight-bold text-slate-900 flex items-center q-gutter-x-xs">
            <q-icon name="attach_file" color="primary" size="16px" />
            <span>REVIEWER FILE ATTACHMENTS (OPTIONAL)</span>
          </span>
          <span class="text-caption text-grey-6" style="font-size: 11px;">
            Annotated PDF or Word document with track changes
          </span>
        </q-card-section>
        <q-card-section class="q-pa-md column q-gutter-y-sm">
          <q-file
            v-model="attachmentFile"
            outlined
            dense
            label="Upload marked-up manuscript (.docx, .pdf)..."
            accept=".docx,.pdf,.doc"
            :disable="store.assignment?.is_completed"
            @update:model-value="handleFileUpload"
          >
            <template v-slot:prepend>
              <q-icon name="cloud_upload" />
            </template>
          </q-file>
          <div v-if="uploadedName" class="text-caption text-positive flex items-center q-gutter-x-xs">
            <q-icon name="check_circle" />
            <span>Attached: <strong>{{ uploadedName }}</strong></span>
          </div>
        </q-card-section>
      </q-card>

      <!-- Section VI: Final Recommendation -->
      <q-card flat bordered class="rounded-borders">
        <q-card-section class="bg-blue-grey-1 q-py-xs q-px-md">
          <span class="text-caption text-weight-bold text-slate-900 flex items-center q-gutter-x-xs">
            <q-icon name="gavel" color="primary" size="16px" />
            <span>FINAL EDITORIAL RECOMMENDATION</span>
            <span class="text-negative text-weight-bold">*</span>
          </span>
        </q-card-section>
        <q-card-section class="q-pa-md">
          <q-select
            v-model="store.recommendation"
            outlined
            dense
            emit-value
            map-options
            :options="recommendationOptions"
            placeholder="Select your recommendation..."
            :disable="store.assignment?.is_completed"
            @update:model-value="store.triggerAutoSave"
          />
        </q-card-section>
      </q-card>
    </div>

    <!-- Sticky Bottom Bar -->
    <div class="q-pa-md bg-grey-1 row items-center justify-between no-wrap" style="border-top: 2px solid #e2e8f0;">
      <div class="row items-center q-gutter-x-sm">
        <q-btn
          outline
          color="grey-8"
          icon="save"
          label="Save Draft"
          class="text-caption text-weight-bold"
          :disable="store.assignment?.is_completed"
          @click="store.triggerAutoSave"
        />
        <q-btn
          flat
          color="primary"
          icon="update"
          label="Request Extension"
          class="text-caption"
          :disable="store.assignment?.is_completed"
          @click="store.isExtensionModalOpen = true"
        />
      </div>

      <div>
        <q-btn
          v-if="!store.assignment?.is_completed"
          color="primary"
          icon-right="send"
          label="Submit Review Recommendation"
          class="text-weight-bolder text-caption q-px-lg q-py-sm"
          :disable="!canSubmit"
          @click="store.isSubmitModalOpen = true"
        />
        <q-btn
          v-else
          color="positive"
          icon="verified"
          label="Review Finalized (Certificate Available)"
          class="text-weight-bold text-caption q-px-md"
          @click="store.isCertificateModalOpen = true"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useReviewStore } from '../stores/reviewStore'

const store = useReviewStore()
const attachmentFile = ref<File | null>(null)
const uploadedName = ref('')

const recommendationOptions = [
  { label: 'Accept Submission (Publish as is)', value: 1 },
  { label: 'Revisions Required (Minor Editorial / Formatting Changes)', value: 2 },
  { label: 'Resubmit for Review (Major Scientific Revision Required)', value: 3 },
  { label: 'Decline Submission (Reject - Substantial Deficiencies)', value: 4 }
]

const canSubmit = computed(() => {
  return (
    store.recommendation !== null &&
    store.authorComments.trim().length > 10 &&
    !store.assignment?.is_completed
  )
})

function handleFileUpload(file: File | null) {
  if (!file) return
  uploadedName.value = file.name
  store.triggerAutoSave()
}
</script>

<style scoped>
.border-bottom {
  border-bottom: 1px solid #f1f5f9;
}
.hover-bg:hover {
  background-color: #f8fafc;
}
</style>
