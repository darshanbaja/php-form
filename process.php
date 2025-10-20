<?php
// process.php
// Show formatted card-style profile from POST data (no DB).
// Basic sanitization and secure file handling (for learning/demo purposes).

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// If request is not POST, redirect back to index
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// Retrieve and sanitize inputs
$fullName = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone    = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$gender   = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$dob      = isset($_POST['dob']) ? trim($_POST['dob']) : '';
$address  = isset($_POST['address']) ? trim($_POST['address']) : '';
$department = isset($_POST['department']) ? trim($_POST['department']) : '';

// Server-side minimal validation (improves security)
$errors = [];
if ($fullName === '') $errors[] = "Full name is required.";
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required.";
// Basic phone digits check
$phoneDigits = preg_replace('/\D+/', '', $phone);
if ($phone === '' || strlen($phoneDigits) < 10) $errors[] = "Valid phone number required.";
if ($gender === '') $errors[] = "Gender required.";
if ($dob === '') $errors[] = "Date of birth required.";
if ($address === '') $errors[] = "Address required.";
if ($department === '') $errors[] = "Department required.";

// Handle file upload (optional)
$photoUrl = ''; // final URL or data-uri
$maxSize = 2 * 1024 * 1024; // 2MB
$allowedExt = ['jpg','jpeg','png','gif'];

if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['photo'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Photo upload error (code {$file['error']}).";
    } else {
        if ($file['size'] > $maxSize) {
            $errors[] = "Photo must be 2 MB or smaller.";
        } else {
            $info = @getimagesize($file['tmp_name']);
            if ($info === false) {
                $errors[] = "Uploaded file is not a valid image.";
            } else {
                // extension check
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExt)) {
                    $errors[] = "Only JPG, PNG, GIF images allowed.";
                } else {
                    // Build uploads directory
                    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
                    if (!is_dir($uploadDir)) {
                        // Try to create; on 000webhost it usually exists or you can create via file manager
                        @mkdir($uploadDir, 0755, true);
                    }
                    // Unique filename
                    $fname = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                    $dest = $uploadDir . DIRECTORY_SEPARATOR . $fname;
                    if (move_uploaded_file($file['tmp_name'], $dest)) {
                        // Use relative path for web
                        $photoUrl = 'uploads/' . $fname;
                    } else {
                        $errors[] = "Failed to save uploaded image.";
                    }
                }
            }
        }
    }
}

// If there are validation errors, show them and a back link
if (!empty($errors)) {
    ?>
    <!doctype html>
    <html lang="en">
    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width,initial-scale=1" />
      <title>Submission Errors</title>
      <link rel="stylesheet" href="style.css"/>
      <style> .err-list{padding:14px;background:#fff3f3;border:1px solid #ffd7d7;border-radius:8px;margin:18px 0;} </style>
    </head>
    <body>
      <div class="container">
        <header><h1>Validation Errors</h1></header>
        <main>
          <div class="err-list">
            <ul>
              <?php foreach ($errors as $err) : ?>
                <li><?php echo h($err); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <p><a href="index.html">← Back to the form</a></p>
        </main>
      </div>
    </body>
    </html>
    <?php
    exit;
}

// Prepare fallback image (SVG data URI) when no photo uploaded
if ($photoUrl === '') {
    // Simple SVG placeholder as data URI
    $initial = strtoupper(substr($fullName ?: 'U', 0, 1));
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="320" height="320">
      <rect width="100%" height="100%" fill="#e6f0ff"/>
      <text x="50%" y="50%" font-size="120" dy=".35em" text-anchor="middle" fill="#2b588f" font-family="Arial, Helvetica, sans-serif">' . h($initial) . '</text>
    </svg>';
    $photoUrl = 'data:image/svg+xml;utf8,' . rawurlencode($svg);
}

// Format DOB to a readable format if possible
$dobFormatted = $dob;
$dateObj = date_create($dob);
if ($dateObj !== false) {
    $dobFormatted = date_format($dateObj, 'd M, Y');
}

// Now display the card
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Application Submitted - <?php echo h($fullName); ?></title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .top-actions{display:flex;gap:12px;justify-content:space-between;align-items:center;margin-bottom:14px}
    .top-actions a{display:inline-block;padding:8px 12px;border-radius:8px;text-decoration:none;background:#f1f5ff;color:#08306b;border:1px solid #dfe9ff}
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Application Submitted</h1>
      <p>Below is the formatted profile card of the submitted application.</p>
    </header>

    <main>
      <div class="top-actions">
        <div>
          <strong>Applicant:</strong> <?php echo h($fullName); ?>
        </div>
        <div>
          <a href="index.php">Submit another application</a>
          <a href="#" onclick="window.print();return false;">Print</a>
        </div>
      </div>

      <section class="profile-card" aria-label="Applicant profile">
        <div class="profile-photo">
          <img src="<?php echo h($photoUrl); ?>" alt="Applicant photo" />
        </div>

        <div class="profile-info">
          <div class="info-row">
            <div class="info-label">Full Name</div>
            <div class="info-value"><?php echo h($fullName); ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">Email</div>
            <div class="info-value"><?php echo h($email); ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">Phone</div>
            <div class="info-value"><?php echo h($phone); ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">Gender</div>
            <div class="info-value"><?php echo h($gender); ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">Date of Birth</div>
            <div class="info-value"><?php echo h($dobFormatted); ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">Department / Course</div>
            <div class="info-value"><?php echo h($department); ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">Address</div>
            <div class="info-value"><?php echo nl2br(h($address)); ?></div>
          </div>
        </div>
      </section>

    </main>

    <footer>
      <small>Generated by Online Registration System</small>
    </footer>
  </div>
</body>
</html>
