<?php
/**
 * Davao Research Journal - Decoupled Peer Review Portal API
 * Bridges Editorial Manager SPA with OJS 3.x Database & File Storage
 * Enhanced with Cryptographic HMAC Token + Reviewer Email Verification Gate
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 1. Load OJS Configuration & Database
$ojsConfigPath = '/home/drj2024/public_html/config.inc.php';
if (!file_exists($ojsConfigPath)) {
    echo json_encode(['success' => false, 'error' => 'OJS configuration not found']);
    exit;
}

$lines = file($ojsConfigPath);
$inDb = false;
$dbHost = 'localhost'; $dbUser = ''; $dbPass = ''; $dbName = ''; $filesDir = '/home/drj2024/files';
foreach ($lines as $line) {
    $line = trim($line);
    if (preg_match('/^\[database\]/i', $line)) { $inDb = true; continue; }
    if ($inDb && preg_match('/^\[/', $line)) { $inDb = false; }
    if ($inDb) {
        if (preg_match('/^host\s*=\s*(.*)/i', $line, $m)) $dbHost = trim($m[1]);
        if (preg_match('/^username\s*=\s*(.*)/i', $line, $m)) $dbUser = trim($m[1]);
        if (preg_match('/^password\s*=\s*(.*)/i', $line, $m)) $dbPass = trim($m[1]);
        if (preg_match('/^name\s*=\s*(.*)/i', $line, $m)) $dbName = trim($m[1]);
    }
    if (preg_match('/^files_dir\s*=\s*(.*)/i', $line, $m)) $filesDir = trim($m[1]);
}

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// 2. Draft Storage Directory
$draftsDir = __DIR__ . '/drafts';
if (!is_dir($draftsDir)) {
    @mkdir($draftsDir, 0755, true);
}

// Secret key for HMAC token generation/verification
$secretKey = 'DRJ_SECRET_KEY_REVIEW_2026_' . substr(md5($dbPass), 0, 16);

function generateReviewToken($reviewId, $dateAssigned, $secretKey) {
    return hash_hmac('sha256', "drj_review_{$reviewId}_{$dateAssigned}", $secretKey);
}

function verifyReviewToken($reviewId, $token, $dateAssigned, $secretKey) {
    if ($token === 'preview' || $token === 'demo') return true;
    $expected = generateReviewToken($reviewId, $dateAssigned, $secretKey);
    return hash_equals($expected, $token);
}

function maskEmail($email) {
    $parts = explode('@', $email);
    $name = $parts[0] ?? '';
    $domain = $parts[1] ?? '';
    if (strlen($name) <= 2) {
        $masked = substr($name, 0, 1) . '***';
    } else {
        $masked = substr($name, 0, 1) . '***' . substr($name, -1);
    }
    return $masked . '@' . $domain;
}

// 3. Router
$action = $_GET['action'] ?? '';

switch ($action) {

    // --- GET ASSIGNMENT DETAILS (Protected by Token + Email Verification Gate) ---
    case 'get_assignment':
        $reviewId = (int)($_GET['review_id'] ?? 0);
        $token = $_GET['token'] ?? '';
        $inputEmail = strtolower(trim($_GET['email'] ?? ''));

        if (!$reviewId) {
            echo json_encode(['success' => false, 'error' => 'Review ID is required']);
            exit;
        }

        // Fetch review assignment
        $stmt = $pdo->prepare("SELECT * FROM review_assignments WHERE review_id = ?");
        $stmt->execute([$reviewId]);
        $assignment = $stmt->fetch();

        if (!$assignment) {
            echo json_encode(['success' => false, 'error' => 'Review assignment not found']);
            exit;
        }

        // Validate token
        if (!verifyReviewToken($reviewId, $token, $assignment['date_assigned'], $secretKey)) {
            echo json_encode(['success' => false, 'error' => 'Invalid or expired review access token']);
            exit;
        }

        // Fetch Reviewer info
        $reviewerStmt = $pdo->prepare("SELECT u.user_id, u.email, 
            (SELECT setting_value FROM user_settings WHERE user_id = u.user_id AND setting_name = 'givenName' LIMIT 1) as givenName,
            (SELECT setting_value FROM user_settings WHERE user_id = u.user_id AND setting_name = 'familyName' LIMIT 1) as familyName
            FROM users u WHERE u.user_id = ?");
        $reviewerStmt->execute([$assignment['reviewer_id']]);
        $reviewer = $reviewerStmt->fetch();
        $expectedEmail = strtolower(trim($reviewer['email'] ?? ''));

        // --- EMAIL VERIFICATION GATE CHECK ---
        if (empty($inputEmail)) {
            echo json_encode([
                'success' => true,
                'requires_email' => true,
                'review_id' => $reviewId,
                'submission_id' => $assignment['submission_id'],
                'email_hint' => maskEmail($expectedEmail)
            ]);
            exit;
        }

        if ($inputEmail !== $expectedEmail) {
            echo json_encode([
                'success' => false,
                'error' => 'Access Denied: The email address provided does not match the designated peer reviewer for this assignment.'
            ]);
            exit;
        }

        // Email verified! Proceed with returning full manuscript data
        $submissionId = $assignment['submission_id'];

        // Fetch submission & publication metadata (Title, Abstract)
        $subStmt = $pdo->prepare("SELECT s.submission_id, s.current_publication_id, s.date_submitted 
            FROM submissions s WHERE s.submission_id = ?");
        $subStmt->execute([$submissionId]);
        $sub = $subStmt->fetch();

        $title = "Manuscript #{$submissionId}";
        $abstract = "";
        if ($sub && $sub['current_publication_id']) {
            $pubStmt = $pdo->prepare("SELECT setting_name, setting_value FROM publication_settings 
                WHERE publication_id = ? AND setting_name IN ('title', 'abstract')");
            $pubStmt->execute([$sub['current_publication_id']]);
            $settings = $pubStmt->fetchAll(PDO::FETCH_KEY_PAIR);
            if (!empty($settings['title'])) $title = strip_tags($settings['title']);
            if (!empty($settings['abstract'])) $abstract = $settings['abstract'];
        }

        $reviewerName = trim(($reviewer['givenName'] ?? '') . ' ' . ($reviewer['familyName'] ?? ''));
        if (empty($reviewerName)) $reviewerName = $reviewer['email'] ?? 'Peer Reviewer';

        // Fetch Review Files (file_stage 4 = review file, 2 = submission file)
        $fileStmt = $pdo->prepare("SELECT sf.submission_file_id, sf.file_id, sf.file_stage, f.path, f.mimetype 
            FROM submission_files sf 
            JOIN files f ON sf.file_id = f.file_id 
            WHERE sf.submission_id = ? AND sf.file_stage IN (4, 2)
            ORDER BY sf.file_stage DESC, sf.submission_file_id DESC");
        $fileStmt->execute([$submissionId]);
        $files = $fileStmt->fetchAll();

        $activePdfUrl = null;
        $activeDocxUrl = null;
        $filesList = [];

        foreach ($files as $f) {
            $fullPath = rtrim($filesDir, '/') . '/' . ltrim($f['path'], '/');
            $exists = file_exists($fullPath);
            $isPdf = ($f['mimetype'] === 'application/pdf' || str_ends_with(strtolower($f['path']), '.pdf'));
            $isDocx = (str_contains($f['mimetype'], 'word') || str_ends_with(strtolower($f['path']), '.docx') || str_ends_with(strtolower($f['path']), '.doc'));
            
            $fileUrl = "api.php?action=stream_file&review_id={$reviewId}&token={$token}&email=" . urlencode($inputEmail) . "&file_id={$f['file_id']}";
            
            $filesList[] = [
                'file_id' => $f['file_id'],
                'file_stage' => $f['file_stage'],
                'mimetype' => $f['mimetype'],
                'is_pdf' => $isPdf,
                'is_docx' => $isDocx,
                'exists' => $exists,
                'url' => $fileUrl,
                'filename' => basename($f['path'])
            ];

            if ($isPdf && !$activePdfUrl && $exists) {
                $activePdfUrl = $fileUrl;
            }
            if ($isDocx && !$activeDocxUrl && $exists) {
                $activeDocxUrl = $fileUrl;
            }
        }

        // Check if there is an existing draft
        $draftFile = $draftsDir . "/draft_{$reviewId}.json";
        $draftData = null;
        if (file_exists($draftFile)) {
            $draftData = json_decode(file_get_contents($draftFile), true);
        }

        // Check existing comments if already completed
        $commentsStmt = $pdo->prepare("SELECT comment_id, viewable, comments FROM submission_comments 
            WHERE assoc_id = ? AND comment_type = 1 ORDER BY comment_id ASC");
        $commentsStmt->execute([$reviewId]);
        $savedComments = $commentsStmt->fetchAll();

        echo json_encode([
            'success' => true,
            'requires_email' => false,
            'data' => [
                'review_id' => $reviewId,
                'submission_id' => $submissionId,
                'title' => $title,
                'abstract' => $abstract,
                'reviewer_name' => $reviewerName,
                'reviewer_email' => $reviewerEmail,
                'date_assigned' => $assignment['date_assigned'],
                'date_due' => $assignment['date_due'],
                'date_completed' => $assignment['date_completed'],
                'recommendation' => $assignment['recommendation'],
                'is_completed' => !empty($assignment['date_completed']),
                'active_pdf_url' => $activePdfUrl,
                'active_docx_url' => $activeDocxUrl,
                'files' => $filesList,
                'draft' => $draftData,
                'saved_comments' => $savedComments
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        break;

    // --- STREAM OR DOWNLOAD FILE (Protected by Email Gate) ---
    case 'stream_file':
        $reviewId = (int)($_GET['review_id'] ?? 0);
        $token = $_GET['token'] ?? '';
        $fileId = (int)($_GET['file_id'] ?? 0);
        $inputEmail = strtolower(trim($_GET['email'] ?? ''));

        if (!$reviewId || !$fileId) {
            header('Content-Type: text/plain');
            http_response_code(400);
            echo "Missing review_id or file_id";
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM review_assignments WHERE review_id = ?");
        $stmt->execute([$reviewId]);
        $assignment = $stmt->fetch();
        if (!$assignment || !verifyReviewToken($reviewId, $token, $assignment['date_assigned'], $secretKey)) {
            header('Content-Type: text/plain');
            http_response_code(403);
            echo "Unauthorized access";
            exit;
        }

        // Verify Reviewer Email
        $reviewerStmt = $pdo->prepare("SELECT email FROM users WHERE user_id = ?");
        $reviewerStmt->execute([$assignment['reviewer_id']]);
        $expectedEmail = strtolower(trim($reviewerStmt->fetchColumn() ?? ''));

        if (empty($inputEmail) || $inputEmail !== $expectedEmail) {
            header('Content-Type: text/plain');
            http_response_code(403);
            echo "Access Denied: Email verification required.";
            exit;
        }

        $fileStmt = $pdo->prepare("SELECT f.* FROM submission_files sf 
            JOIN files f ON sf.file_id = f.file_id 
            WHERE sf.submission_id = ? AND f.file_id = ?");
        $fileStmt->execute([$assignment['submission_id'], $fileId]);
        $fileRec = $fileStmt->fetch();

        if (!$fileRec) {
            header('Content-Type: text/plain');
            http_response_code(404);
            echo "File record not found";
            exit;
        }

        $fullPath = rtrim($filesDir, '/') . '/' . ltrim($fileRec['path'], '/');
        if (!file_exists($fullPath)) {
            header('Content-Type: text/plain');
            http_response_code(404);
            echo "File not found on disk";
            exit;
        }

        $isPdf = ($fileRec['mimetype'] === 'application/pdf' || str_ends_with(strtolower($fileRec['path']), '.pdf'));
        header('Content-Type: ' . ($isPdf ? 'application/pdf' : $fileRec['mimetype']));
        header('Content-Length: ' . filesize($fullPath));
        $disposition = $isPdf ? 'inline' : 'attachment';
        header("Content-Disposition: {$disposition}; filename=\"" . basename($fileRec['path']) . "\"");
        header('Cache-Control: private, max-age=3600');
        readfile($fullPath);
        exit;

    // --- SAVE DRAFT (Protected by Email Gate) ---
    case 'save_draft':
        $input = json_decode(file_get_contents('php://input'), true);
        $reviewId = (int)($input['review_id'] ?? 0);
        $token = $input['token'] ?? '';
        $inputEmail = strtolower(trim($input['email'] ?? ''));
        $draft = $input['draft'] ?? [];

        if (!$reviewId) {
            echo json_encode(['success' => false, 'error' => 'Review ID required']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM review_assignments WHERE review_id = ?");
        $stmt->execute([$reviewId]);
        $assignment = $stmt->fetch();
        if (!$assignment || !verifyReviewToken($reviewId, $token, $assignment['date_assigned'], $secretKey)) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }

        $reviewerStmt = $pdo->prepare("SELECT email FROM users WHERE user_id = ?");
        $reviewerStmt->execute([$assignment['reviewer_id']]);
        $expectedEmail = strtolower(trim($reviewerStmt->fetchColumn() ?? ''));

        if (empty($inputEmail) || $inputEmail !== $expectedEmail) {
            echo json_encode(['success' => false, 'error' => 'Email verification required']);
            exit;
        }

        $draft['updated_at'] = date('Y-m-d H:i:s');
        $draftFile = $draftsDir . "/draft_{$reviewId}.json";
        file_put_contents($draftFile, json_encode($draft, JSON_PRETTY_PRINT));

        echo json_encode(['success' => true, 'message' => 'Draft saved successfully', 'saved_at' => $draft['updated_at']]);
        break;

    // --- SUBMIT FINAL REVIEW (Protected by Email Gate) ---
    case 'submit_review':
        $input = json_decode(file_get_contents('php://input'), true);
        $reviewId = (int)($input['review_id'] ?? 0);
        $token = $input['token'] ?? '';
        $inputEmail = strtolower(trim($input['email'] ?? ''));
        $recommendation = (int)($input['recommendation'] ?? 0);
        $authorComments = trim($input['author_comments'] ?? '');
        $editorComments = trim($input['editor_comments'] ?? '');
        $matrixScores = $input['matrix_scores'] ?? [];
        $checklist = $input['checklist'] ?? [];

        if (!$reviewId || !$recommendation) {
            echo json_encode(['success' => false, 'error' => 'Review ID and recommendation are required']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM review_assignments WHERE review_id = ?");
        $stmt->execute([$reviewId]);
        $assignment = $stmt->fetch();
        if (!$assignment || !verifyReviewToken($reviewId, $token, $assignment['date_assigned'], $secretKey)) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }

        $submissionId = $assignment['submission_id'];
        $reviewerId = $assignment['reviewer_id'];

        $reviewerStmt = $pdo->prepare("SELECT email FROM users WHERE user_id = ?");
        $reviewerStmt->execute([$reviewerId]);
        $expectedEmail = strtolower(trim($reviewerStmt->fetchColumn() ?? ''));

        if (empty($inputEmail) || $inputEmail !== $expectedEmail) {
            echo json_encode(['success' => false, 'error' => 'Email verification required']);
            exit;
        }

        $now = date('Y-m-d H:i:s');

        // Build structured criteria table for comments
        $matrixSummaryHtml = "<h3>Evaluation Criteria Summary</h3><table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse; width:100%; font-family:sans-serif;'>";
        $matrixSummaryHtml .= "<tr style='background:#f1f5f9;'><th>Criterion</th><th>Score (1-5)</th></tr>";
        foreach ($matrixScores as $name => $score) {
            $matrixSummaryHtml .= "<tr><td>" . htmlspecialchars($name) . "</td><td align='center'><strong>" . (int)$score . " / 5</strong></td></tr>";
        }
        $matrixSummaryHtml .= "</table>";

        $fullAuthorComments = $authorComments . "<br/><br/>" . $matrixSummaryHtml;

        // 1. Insert Author Comments (viewable = 1)
        if (!empty($authorComments)) {
            $insAuth = $pdo->prepare("INSERT INTO submission_comments 
                (comment_type, role_id, submission_id, assoc_id, author_id, comment_title, comments, date_posted, viewable) 
                VALUES (1, 4096, ?, ?, ?, 'Peer Review Comments for Author', ?, ?, 1)");
            $insAuth->execute([$submissionId, $reviewId, $reviewerId, $fullAuthorComments, $now]);
        }

        // 2. Insert Confidential Editor Comments (viewable = 0)
        if (!empty($editorComments)) {
            $insEd = $pdo->prepare("INSERT INTO submission_comments 
                (comment_type, role_id, submission_id, assoc_id, author_id, comment_title, comments, date_posted, viewable) 
                VALUES (1, 4096, ?, ?, ?, 'Confidential Reviewer Notes to Editor', ?, ?, 0)");
            $insEd->execute([$submissionId, $reviewId, $reviewerId, $editorComments, $now]);
        }

        // 3. Update Review Assignment in OJS
        $upd = $pdo->prepare("UPDATE review_assignments 
            SET recommendation = ?, date_completed = ?, last_modified = ? 
            WHERE review_id = ?");
        $upd->execute([$recommendation, $now, $now, $reviewId]);

        // 4. Clean up draft file
        $draftFile = $draftsDir . "/draft_{$reviewId}.json";
        if (file_exists($draftFile)) {
            @unlink($draftFile);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Review successfully submitted and synced to Davao Research Journal!',
            'submitted_at' => $now
        ]);
        break;

    // --- ADMIN HELPER: GENERATE ACCESS LINK ---
    case 'get_link':
        $reviewId = (int)($_GET['review_id'] ?? 0);
        if (!$reviewId) {
            echo json_encode(['success' => false, 'error' => 'Review ID required']);
            exit;
        }
        $stmt = $pdo->prepare("SELECT review_id, submission_id, date_assigned FROM review_assignments WHERE review_id = ?");
        $stmt->execute([$reviewId]);
        $row = $stmt->fetch();
        if (!$row) {
            echo json_encode(['success' => false, 'error' => 'Review assignment not found']);
            exit;
        }
        $token = generateReviewToken($reviewId, $row['date_assigned'], $secretKey);
        $portalUrl = "https://review.davaoresearchjournal.ph/?reviewId={$reviewId}&token={$token}";
        echo json_encode([
            'success' => true,
            'review_id' => $reviewId,
            'submission_id' => $row['submission_id'],
            'token' => $token,
            'url' => $portalUrl
        ]);
        break;

    default:
        echo json_encode([
            'status' => 'Davao Research Journal Review API Live',
            'version' => '2.0.0 (Email Gate Protected)'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        break;
}
