export interface ReviewFile {
  file_id: number;
  filename: string;
  filesize?: number;
  extension: string;
  is_pdf: boolean;
  is_docx: boolean;
}

export interface ReviewDraft {
  criteria?: Record<string, number>;
  questions?: Record<string, string>;
  author_comments?: string;
  editor_comments?: string;
  recommendation?: number;
  attachments?: string[];
  updated_at?: string;
}

export interface ReviewAssignment {
  review_id: number;
  submission_id: number;
  reviewer_name: string;
  reviewer_email: string;
  date_assigned: string;
  date_due: string | null;
  date_completed: string | null;
  is_completed: boolean;
  recommendation: number | null;
  title: string;
  abstract: string;
  section_title: string;
  files: ReviewFile[];
  active_pdf_url: string | null;
  active_docx_url: string | null;
  draft: ReviewDraft | null;
}

export interface EvaluationCriterion {
  id: string;
  label: string;
  desc: string;
}

export interface ComplianceQuestion {
  id: string;
  label: string;
  options: { label: string; value: string }[];
}

export interface ApiResponse<T = any> {
  success: boolean;
  error?: string;
  message?: string;
  requires_email?: boolean;
  email_hint?: string;
  data?: T;
}
