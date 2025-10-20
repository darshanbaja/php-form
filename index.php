<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Online Registration Form</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="script.js" defer></script>
</head>
<body>
  <div class="container">
    <header>
      <h1>Online Application / Registration Form</h1>
      <p>Fill all required fields and submit. The formatted profile will appear on a new page.</p>
    </header>

    <main>
      <form id="regForm" action="process.php" method="post" enctype="multipart/form-data" novalidate>
        <div class="form-row">
          <label for="fullName">Full Name <span class="req">*</span></label>
          <input type="text" id="fullName" name="fullName" placeholder="John Doe" required />
        </div>

        <div class="form-row">
          <label for="email">Email <span class="req">*</span></label>
          <input type="email" id="email" name="email" placeholder="john@example.com" required />
        </div>

        <div class="form-row">
          <label for="phone">Phone Number <span class="req">*</span></label>
          <input type="tel" id="phone" name="phone" placeholder="10-digit mobile number" required />
        </div>

        <div class="form-row">
          <label>Gender <span class="req">*</span></label>
          <div class="radio-group">
            <label><input type="radio" name="gender" value="Male" required /> Male</label>
            <label><input type="radio" name="gender" value="Female" /> Female</label>
            <label><input type="radio" name="gender" value="Other" /> Other</label>
          </div>
        </div>

        <div class="form-row">
          <label for="dob">Date of Birth <span class="req">*</span></label>
          <input type="date" id="dob" name="dob" required />
        </div>

        <div class="form-row">
          <label for="address">Address <span class="req">*</span></label>
          <textarea id="address" name="address" rows="3" placeholder="Your full address" required></textarea>
        </div>

        <div class="form-row">
          <label for="department">Department / Course <span class="req">*</span></label>
          <select id="department" name="department" required>
            <option value="">-- Select Department --</option>
            <option>Computer Science</option>
            <option>Information Technology</option>
            <option>Electronics</option>
            <option>Mechanical</option>
            <option>Civil</option>
            <option>Other</option>
          </select>
        </div>

        <div class="form-row">
          <label for="photo">Upload Photo (optional)</label>
          <input type="file" id="photo" name="photo" accept="image/*" />
          <small class="hint">Max 2 MB. JPG / PNG / GIF allowed.</small>
          <div id="preview-wrapper">
            <img id="photoPreview" alt="photo preview" style="display:none" />
          </div>
        </div>

        <div class="form-row">
          <button type="submit" id="submitBtn">Submit Application</button>
        </div>
      </form>
    </main>

    <footer>
      <small>Prepared by: Darshan — Project: Online Registration</small>
    </footer>
  </div>
</body>
</html>
