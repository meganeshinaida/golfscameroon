<?php
// Common helper functions: escaping, CSRF, and redirects

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function csrf_token() {
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function verify_csrf($token) {
    if (!isset($_SESSION['_csrf_token']) || !$token) return false;
    return hash_equals($_SESSION['_csrf_token'], $token);
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Return project base URL (auto-detects if app is in a subfolder)
function base_url($path = '') {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Get base directory - remove /public/index.php or script filename
    if (preg_match('#/public/index\.php$#', $script)) {
        // We're being rewritten from public/index.php
        $base = preg_replace('#/public/index\.php$#', '', $script);
    } else {
        // Standard setup
        $base = rtrim(dirname($script), '/\\');
        if (preg_match('#/public$#', $base)) {
            $base = preg_replace('#/public$#', '', $base);
        }
    }
    
    $base = $base === '/' ? '' : $base;
    $path = ltrim($path, '/');
    return $base . ($path !== '' ? '/' . $path : '');
}

function asset_url($path = '') {
    return base_url(ltrim($path, '/'));
}

function upload_image($file, $targetDir, $maxSize = 2097152) {
    // $file is from $_FILES['field']
    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success'=>false, 'error'=>'No file uploaded or upload error'];
    }

    if ($file['size'] > $maxSize) return ['success'=>false, 'error'=>'File too large'];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];
    if (!isset($allowed[$mime])) return ['success'=>false, 'error'=>'Invalid file type'];

    $ext = $allowed[$mime];
    $base = uniqid('img_');
    $filename = $base . '.' . $ext;
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $dest = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) return ['success'=>false, 'error'=>'Failed to move uploaded file'];

    // create thumbnail
    $thumbDir = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'thumbs';
    if (!is_dir($thumbDir)) mkdir($thumbDir, 0755, true);
    $thumbPath = $thumbDir . DIRECTORY_SEPARATOR . $base . '.webp';
    $ok = create_thumbnail($dest, $thumbPath, 400, 300);
    if (!$ok) {
        // continue without thumbnail
    }

    return ['success'=>true, 'filename'=>$filename];
}

function create_thumbnail($srcPath, $destPath, $maxW = 400, $maxH = 300) {
    // Check if GD functions are available
    if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
        return false; // GD not enabled, skip thumbnail
    }
    
    if (!file_exists($srcPath)) return false;
    $info = getimagesize($srcPath);
    if (!$info) return false;
    list($w, $h) = $info;
    $mime = $info['mime'];

    // Check if required image functions exist
    $funcMap = [
        'image/jpeg' => 'imagecreatefromjpeg',
        'image/png' => 'imagecreatefrompng',
        'image/webp' => 'imagecreatefromwebp'
    ];
    
    if (!isset($funcMap[$mime]) || !function_exists($funcMap[$mime])) {
        return false; // Unsupported image type or function not available
    }

    switch ($mime) {
        case 'image/jpeg': $srcImg = imagecreatefromjpeg($srcPath); break;
        case 'image/png': $srcImg = imagecreatefrompng($srcPath); break;
        case 'image/webp': $srcImg = imagecreatefromwebp($srcPath); break;
        default: return false;
    }

    if (!$srcImg) return false; // Failed to create image resource

    $ratio = min($maxW / $w, $maxH / $h);
    $nw = (int)($w * $ratio);
    $nh = (int)($h * $ratio);
    $dst = imagecreatetruecolor($nw, $nh);
    
    if (!$dst) {
        imagedestroy($srcImg);
        return false;
    }
    
    // preserve PNG transparency
    if ($mime === 'image/png') {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    }
    imagecopyresampled($dst, $srcImg, 0,0,0,0, $nw, $nh, $w, $h);

    // always save thumbnails as webp for better compression
    $ok = imagewebp($dst, $destPath, 80);
    imagedestroy($srcImg);
    imagedestroy($dst);
    return $ok;
}

// Get global website setting from database
function get_setting($key, $default = '') {
    try {
        require_once __DIR__ . '/database.php';
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare('SELECT value FROM settings WHERE `key_name` = :key');
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

?>
