<?php
require_once 'db.php';

$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['error' => 'خطأ في رفع الملف.']);
        exit;
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $fileType = mime_content_type($_FILES['image']['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['error' => 'نوع الملف غير مسموح.']);
        exit;
    }

    if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
        echo json_encode(['error' => 'حجم الملف يتجاوز 5 ميغابايت.']);
        exit;
    }

    $safeName = uniqid('img_', true) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $targetPath = $uploadDir . $safeName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        echo json_encode(['error' => 'فشل في حفظ الملف.']);
        exit;
    }

    $escapedPath = escapeshellarg(realpath($targetPath));
    $command = "python3 ../python/tagger.py $escapedPath";
    $output = shell_exec($command);
    $results = json_decode($output, true);

    if (!$results || !$results['success']) {
        echo json_encode(['error' => 'فشل في تحليل الصورة.', 'details' => $results['error'] ?? '']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO uploads (filename, tags, uploaded_at) VALUES (?, ?, NOW())");
    $stmt->execute([$safeName, json_encode($results['results'])]);

    echo json_encode([
        'success' => true,
        'filename' => $safeName,
        'tags' => $results['results']
    ]);
} else {
    echo json_encode(['error' => 'طريقة الطلب غير مسموحة.']);
}
?>