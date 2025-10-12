// script.js - uses jQuery
$(function () {

  // Photo preview
  $('#photo').on('change', function (e) {
    const file = this.files[0];
    if (!file) {
      $('#photoPreview').hide().attr('src', '');
      return;
    }
    if (!file.type.startsWith('image/')) {
      alert('Please select an image file.');
      $(this).val('');
      $('#photoPreview').hide();
      return;
    }
    const reader = new FileReader();
    reader.onload = function (ev) {
      $('#photoPreview').attr('src', ev.target.result).show();
    };
    reader.readAsDataURL(file);
  });

  // Form validation on submit
  $('#regForm').on('submit', function (e) {
    // Basic client-side validation
    const name = $.trim($('#fullName').val());
    const email = $.trim($('#email').val());
    const phone = $.trim($('#phone').val());
    const dob = $('#dob').val();
    const address = $.trim($('#address').val());
    const department = $('#department').val();
    const gender = $('input[name="gender"]:checked').val();

    // Name
    if (!name) {
      alert('Please enter full name.');
      $('#fullName').focus();
      e.preventDefault(); return false;
    }

    // Email simple regex
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email || !emailRegex.test(email)) {
      alert('Please enter a valid email address.');
      $('#email').focus();
      e.preventDefault(); return false;
    }

    // Phone: digits only, length 10 (adjust if international)
    const phoneDigits = phone.replace(/\D/g, '');
    if (!phone || phoneDigits.length < 10) {
      alert('Please enter a valid 10-digit phone number.');
      $('#phone').focus();
      e.preventDefault(); return false;
    }

    // DOB
    if (!dob) {
      alert('Please select date of birth.');
      $('#dob').focus();
      e.preventDefault(); return false;
    }

    // Address
    if (!address) {
      alert('Please enter address.');
      $('#address').focus();
      e.preventDefault(); return false;
    }

    // Department
    if (!department) {
      alert('Please choose a department or course.');
      $('#department').focus();
      e.preventDefault(); return false;
    }

    // Gender
    if (!gender) {
      alert('Please select gender.');
      e.preventDefault(); return false;
    }

    // If all good, let the browser submit the form to process.php
    return true;
  });
});
