<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$page_title = "Join BIT Library";
require_once '../includes/header.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    if (is_admin()) {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card book-card border-0 shadow-lg p-4 p-md-5">
                <!-- Progress Bar -->
                <div class="progress-stepper mb-5">
                    <div class="step-item active" data-step="1">1</div>
                    <div class="step-item" data-step="2">2</div>
                    <div class="step-item" data-step="3">3</div>
                    <div class="step-item" data-step="4">4</div>
                    <div class="step-item" data-step="5">5</div>
                </div>

                <div id="registrationForm">
                    <!-- Step 1: Join / Signup -->
                    <div class="registration-step active" id="step1">
                        <div class="text-center mb-4">
                            <h2 class="hero-title" style="font-size: 2rem;">Join <span class="text-teal">Us</span></h2>
                            <p class="text-gray">Enter your email to start the registration</p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-gray small text-uppercase fw-bold">Email Address</label>
                            <input type="email" id="email" class="form-control bg-dark border-0 text-white p-3" placeholder="yourname@example.com">
                        </div>
                        <button class="btn btn-register btn-lg w-100 py-3" onclick="sendOTP()">
                            Send OTP <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </div>

                    <!-- Step 2: OTP Verification -->
                    <div class="registration-step" id="step2">
                        <div class="text-center mb-4">
                            <h2 class="hero-title" style="font-size: 2rem;">Verify <span class="text-teal">Email</span></h2>
                            <p class="text-gray">Enter the 6-digit code sent to your email</p>
                        </div>
                        <div class="mb-4 text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <input type="text" id="otp" class="form-control bg-dark border-0 text-white p-3 text-center fw-bold fs-4" maxlength="6" style="letter-spacing: 10px;">
                            </div>
                        </div>
                        <button class="btn btn-register btn-lg w-100 py-3" onclick="verifyOTP()">
                            Verify OTP <i class="fas fa-check-circle ms-2"></i>
                        </button>
                        <div class="text-center mt-3">
                            <button class="btn btn-link text-teal text-decoration-none small" onclick="sendOTP()">Resend OTP</button>
                        </div>
                    </div>

                    <!-- Step 3: Upload Fee Slip -->
                    <div class="registration-step" id="step3">
                        <div class="text-center mb-4">
                            <h2 class="hero-title" style="font-size: 2rem;">Payment <span class="text-teal">Proof</span></h2>
                            <p class="text-gray">Upload your library fee payment slip</p>
                        </div>
                        <div class="upload-box mb-4" onclick="document.getElementById('fee_slip').click()">
                            <input type="file" id="fee_slip" hidden accept="image/*" onchange="previewFee(this)">
                            <div id="feePreviewContainer">
                                <i class="fas fa-file-invoice-dollar fa-3x text-gray mb-3"></i>
                                <p class="mb-0 text-gray" id="feeText">Click to upload JPG/PNG</p>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <button class="btn btn-login flex-grow-1" onclick="prevStep()">Back</button>
                            <button class="btn btn-register flex-grow-1" onclick="uploadFee()">Continue</button>
                        </div>
                    </div>

                    <!-- Step 4: Personal Details -->
                    <div class="registration-step" id="step4">
                        <div class="text-center mb-4">
                            <h2 class="hero-title" style="font-size: 2rem;">Personal <span class="text-teal">Details</span></h2>
                            <p class="text-gray">Tell us more about yourself</p>
                        </div>
                        
                        <div class="text-center mb-4">
                            <div class="avatar-upload-container mx-auto" style="position: relative; width: 100px; height: 100px;">
                                <img id="profilePreview" src="../assets/images/default-avatar.png" class="rounded-circle border border-3 border-teal" style="width: 100%; height: 100%; object-fit: cover;">
                                <label for="profile_image" class="btn btn-sm btn-register rounded-circle position-absolute bottom-0 end-0 p-1" style="width: 30px; height: 30px;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="profile_image" hidden accept="image/*" onchange="previewProfile(this)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-gray small text-uppercase fw-bold">Full Name</label>
                                <input type="text" id="full_name" class="form-control bg-dark border-0 text-white p-2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-gray small text-uppercase fw-bold">Age / Year</label>
                                <select id="year" class="form-select bg-dark border-0 text-white p-2">
                                    <option value="first">First Year</option>
                                    <option value="second">Second Year</option>
                                    <option value="third">Third Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-gray small text-uppercase fw-bold">Faculty</label>
                                <select id="faculty" class="form-select bg-dark border-0 text-white p-2">
                                    <option value="computer">Computer</option>
                                    <option value="electrical">Electrical</option>
                                    <option value="mechanical">Mechanical</option>
                                    <option value="civil">Civil</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-gray small text-uppercase fw-bold">Contact Number</label>
                                <input type="text" id="contact" class="form-control bg-dark border-0 text-white p-2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-gray small text-uppercase fw-bold">Parent Name</label>
                                <input type="text" id="parent_name" class="form-control bg-dark border-0 text-white p-2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-gray small text-uppercase fw-bold">Parent Contact</label>
                                <input type="text" id="parent_contact" class="form-control bg-dark border-0 text-white p-2">
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3 mt-3">
                            <button class="btn btn-login flex-grow-1" onclick="prevStep()">Back</button>
                            <button class="btn btn-register flex-grow-1" onclick="saveDetails()">Save & Continue</button>
                        </div>
                    </div>

                    <!-- Step 5: Credentials Setup -->
                    <div class="registration-step" id="step5">
                        <div class="text-center mb-4">
                            <h2 class="hero-title" style="font-size: 2rem;">Security <span class="text-teal">Setup</span></h2>
                            <p class="text-gray">Create your login credentials</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-gray small text-uppercase fw-bold">Username</label>
                            <input type="text" id="username" class="form-control bg-dark border-0 text-white p-3" placeholder="Choose a unique username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-gray small text-uppercase fw-bold">Password</label>
                            <div class="position-relative">
                                <input type="password" id="password" class="form-control bg-dark border-0 text-white p-3 pe-5" placeholder="Min. 8 characters, 1 uppercase, 1 symbol">
                                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3 text-gray" id="togglePassIcon" onclick="togglePasswordVisibility('password', 'togglePassIcon')" style="cursor: pointer;"></i>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-gray small text-uppercase fw-bold">Confirm Password</label>
                            <div class="position-relative">
                                <input type="password" id="confirm_password" class="form-control bg-dark border-0 text-white p-3 pe-5">
                                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3 text-gray" id="toggleConfirmPassIcon" onclick="togglePasswordVisibility('confirm_password', 'toggleConfirmPassIcon')" style="cursor: pointer;"></i>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <button class="btn btn-login flex-grow-1" onclick="prevStep()">Back</button>
                            <button class="btn btn-register flex-grow-1" onclick="finishRegistration()">Complete Registration</button>
                        </div>
                    </div>
                </div>

                <p class="text-center text-gray mt-5 mb-0">
                    Already have an account? 
                    <a href="login.php" class="text-teal fw-bold text-decoration-none">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
let currentStep = 1;

function goToStep(step) {
    document.querySelectorAll('.registration-step').forEach(el => el.classList.remove('active'));
    document.getElementById('step' + step).classList.add('active');
    
    document.querySelectorAll('.step-item').forEach(el => {
        const stepNum = parseInt(el.dataset.step);
        if (stepNum < step) el.classList.add('completed');
        else el.classList.remove('completed');
        
        if (stepNum === step) el.classList.add('active');
        else el.classList.remove('active');
    });
    
    currentStep = step;
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function prevStep() {
    if (currentStep > 1) goToStep(currentStep - 1);
}

function sendOTP() {
    const email = document.getElementById('email').value;
    if (!email) {
        Swal.fire('Error', 'Please enter your email.', 'error');
        return;
    }

    Swal.fire({
        title: 'Sending OTP...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const formData = new FormData();
    formData.append('action', 'send_otp');
    formData.append('email', email);

    fetch('../ajax/registration_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.status === 'success') {
            Swal.fire('Success', data.message + (data.otp ? ' (Demo OTP: ' + data.otp + ')' : ''), 'success')
            .then(() => goToStep(2));
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}

function verifyOTP() {
    const otp = document.getElementById('otp').value;
    if (!otp) {
        Swal.fire('Error', 'Please enter the OTP.', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'verify_otp');
    formData.append('otp', otp);

    fetch('../ajax/registration_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            goToStep(3);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}

function previewFee(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('feePreviewContainer').innerHTML = `<img src="${e.target.result}" style="max-height: 150px; width: auto; margin-bottom: 10px;"> <p class="text-teal mb-0">File selected</p>`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function uploadFee() {
    const fileInput = document.getElementById('fee_slip');
    if (!fileInput.files.length) {
        Swal.fire('Error', 'Please upload your fee slip.', 'error');
        return;
    }

    Swal.fire({title: 'Uploading...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

    const formData = new FormData();
    formData.append('action', 'upload_fee');
    formData.append('fee_slip', fileInput.files[0]);

    fetch('../ajax/registration_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.status === 'success') {
            goToStep(4);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}

function previewProfile(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function saveDetails() {
    const fields = ['full_name', 'year', 'faculty', 'contact', 'parent_name', 'parent_contact'];
    const formData = new FormData();
    formData.append('action', 'save_details');
    
    let valid = true;
    fields.forEach(f => {
        const val = document.getElementById(f).value;
        if (!val) valid = false;
        formData.append(f.replace('full_', ''), val);
    });
    
    if (!valid) {
        Swal.fire('Error', 'All fields are required.', 'error');
        return;
    }
    
    const profileImg = document.getElementById('profile_image');
    if (profileImg.files.length) {
        formData.append('profile_image', profileImg.files[0]);
    }

    Swal.fire({title: 'Saving...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

    fetch('../ajax/registration_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.status === 'success') {
            goToStep(5);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}

function finishRegistration() {
    const username = document.getElementById('username').value;
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (!username || !pass || !confirm) {
        Swal.fire('Error', 'All fields are required.', 'error');
        return;
    }
    
    if (pass !== confirm) {
        Swal.fire('Error', 'Passwords do not match.', 'error');
        return;
    }

    // Client-side validation for password strength
    const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    if (!strongPassword.test(pass)) {
        Swal.fire('Weak Password', 'Password must be 8+ characters, including uppercase, number, and symbol.', 'warning');
        return;
    }

    Swal.fire({title: 'Completing Registration...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

    const formData = new FormData();
    formData.append('action', 'finish_registration');
    formData.append('username', username);
    formData.append('password', pass);
    formData.append('confirm_password', confirm);

    fetch('../ajax/registration_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.status === 'success') {
            Swal.fire('Success!', 'Registration successful. Welcome to BIT Library!', 'success')
            .then(() => {
                window.location.href = 'login.php';
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}

function togglePasswordVisibility(triggerId, iconId) {
    const input = document.getElementById(triggerId);
    const icon = document.getElementById(iconId);
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>
