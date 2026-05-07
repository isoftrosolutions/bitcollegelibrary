<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$page_title = "Forgot Password";
require_once '../includes/header.php';
?>

<div class="row justify-content-center pt-5">
    <div class="col-lg-5 col-md-8">
        <div class="card book-card border-0 shadow-lg p-4">

            <!-- Step 1: Email -->
            <div class="forgot-step active" id="step1">
                <div class="text-center mb-4">
                    <div class="mb-4">
                        <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="BIT Logo" style="width: 80px;">
                    </div>
                    <h1 class="hero-title" style="font-size: 2rem;">Forgot <span class="text-teal">Password?</span></h1>
                    <p class="text-gray">Enter your registered email and we'll send you a reset code.</p>
                </div>
                <div id="step1-alert"></div>
                <div class="mb-4">
                    <label class="form-label text-gray">Email Address</label>
                    <input type="email" id="email" class="form-control bg-dark border-0 text-white p-3" placeholder="yourname@example.com">
                </div>
                <button class="btn btn-register btn-lg w-100 py-3" onclick="sendOTP()">
                    Send Reset Code <i class="fas fa-paper-plane ms-2"></i>
                </button>
                <p class="text-center text-gray mt-4 mb-0">
                    Remember it? <a href="login.php" class="text-teal fw-bold text-decoration-none">Sign in</a>
                </p>
            </div>

            <!-- Step 2: OTP -->
            <div class="forgot-step" id="step2">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-envelope-open-text fa-3x text-teal"></i>
                    </div>
                    <h1 class="hero-title" style="font-size: 2rem;">Check Your <span class="text-teal">Email</span></h1>
                    <p class="text-gray">Enter the 6-digit code sent to <span id="display-email" class="text-white fw-bold"></span></p>
                </div>
                <div id="step2-alert"></div>
                <div class="mb-4">
                    <input type="text" id="otp" class="form-control bg-dark border-0 text-white p-3 text-center fw-bold fs-4" maxlength="6" placeholder="––––––" style="letter-spacing: 10px;">
                </div>
                <button class="btn btn-register btn-lg w-100 py-3" onclick="verifyOTP()">
                    Verify Code <i class="fas fa-check-circle ms-2"></i>
                </button>
                <div class="text-center mt-3">
                    <button class="btn btn-link text-teal text-decoration-none small" onclick="sendOTP()">Resend code</button>
                </div>
            </div>

            <!-- Step 3: New Password -->
            <div class="forgot-step" id="step3">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-lock-open fa-3x text-teal"></i>
                    </div>
                    <h1 class="hero-title" style="font-size: 2rem;">New <span class="text-teal">Password</span></h1>
                    <p class="text-gray">Choose a strong password for your account.</p>
                </div>
                <div id="step3-alert"></div>
                <div class="mb-4">
                    <label class="form-label text-gray">New Password</label>
                    <div class="position-relative">
                        <input type="password" id="new-password" class="form-control bg-dark border-0 text-white p-3 pe-5" placeholder="Min. 6 characters">
                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-gray pe-3" onclick="togglePassword('new-password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label text-gray">Confirm Password</label>
                    <div class="position-relative">
                        <input type="password" id="confirm-password" class="form-control bg-dark border-0 text-white p-3 pe-5" placeholder="Repeat password">
                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-gray pe-3" onclick="togglePassword('confirm-password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button class="btn btn-register btn-lg w-100 py-3" onclick="resetPassword()">
                    Reset Password <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>

            <!-- Step 4: Success -->
            <div class="forgot-step text-center" id="step4">
                <div class="mb-4 mt-2">
                    <i class="fas fa-check-circle fa-4x text-teal"></i>
                </div>
                <h1 class="hero-title" style="font-size: 2rem;">Password <span class="text-teal">Reset!</span></h1>
                <p class="text-gray mb-4">Your password has been updated successfully. You can now sign in with your new password.</p>
                <a href="login.php" class="btn btn-register btn-lg w-100 py-3">
                    Go to Login <i class="fas fa-sign-in-alt ms-2"></i>
                </a>
            </div>

        </div>
    </div>
</div>

<style>
.forgot-step { display: none; }
.forgot-step.active { display: block; }
</style>

<script>
const HANDLER = '<?= BASE_URL ?>/ajax/forgot_password_handler.php';

function showAlert(stepId, type, message) {
    const el = document.getElementById(stepId + '-alert');
    if (!el) return;
    el.innerHTML = `<div class="alert alert-${type} border-0 mb-3">${message}</div>`;
}

function clearAlert(stepId) {
    const el = document.getElementById(stepId + '-alert');
    if (el) el.innerHTML = '';
}

function showStep(n) {
    document.querySelectorAll('.forgot-step').forEach(s => s.classList.remove('active'));
    document.getElementById('step' + n).classList.add('active');
}

function setLoading(btn, loading) {
    btn.disabled = loading;
    btn.dataset.original = btn.dataset.original || btn.innerHTML;
    btn.innerHTML = loading
        ? '<span class="spinner-border spinner-border-sm me-2"></span>Please wait…'
        : btn.dataset.original;
}

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

async function sendOTP() {
    clearAlert('step1');
    const email = document.getElementById('email').value.trim();
    if (!email) { showAlert('step1', 'danger', 'Please enter your email address.'); return; }

    const btn = event.currentTarget;
    setLoading(btn, true);

    const fd = new FormData();
    fd.append('action', 'send_otp');
    fd.append('email', email);

    try {
        const res  = await fetch(HANDLER, { method: 'POST', body: fd });
        const data = await res.json();

        if (data.status === 'success') {
            document.getElementById('display-email').textContent = email;
            showStep(2);
            if (data.otp) {
                showAlert('step2', 'warning', `<i class="fas fa-info-circle me-1"></i>Demo mode — OTP: <strong>${data.otp}</strong>`);
            }
        } else {
            showAlert('step1', 'danger', data.message);
        }
    } catch (e) {
        showAlert('step1', 'danger', 'Network error. Please try again.');
    } finally {
        setLoading(btn, false);
    }
}

async function verifyOTP() {
    clearAlert('step2');
    const otp = document.getElementById('otp').value.trim();
    if (!otp) { showAlert('step2', 'danger', 'Please enter the 6-digit code.'); return; }

    const btn = event.currentTarget;
    setLoading(btn, true);

    const fd = new FormData();
    fd.append('action', 'verify_otp');
    fd.append('otp', otp);

    try {
        const res  = await fetch(HANDLER, { method: 'POST', body: fd });
        const data = await res.json();

        if (data.status === 'success') {
            showStep(3);
        } else {
            showAlert('step2', 'danger', data.message);
            document.getElementById('otp').value = '';
            document.getElementById('otp').focus();
        }
    } catch (e) {
        showAlert('step2', 'danger', 'Network error. Please try again.');
    } finally {
        setLoading(btn, false);
    }
}

async function resetPassword() {
    clearAlert('step3');
    const password        = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (password.length < 6) {
        showAlert('step3', 'danger', 'Password must be at least 6 characters.');
        return;
    }
    if (password !== confirmPassword) {
        showAlert('step3', 'danger', 'Passwords do not match.');
        return;
    }

    const btn = event.currentTarget;
    setLoading(btn, true);

    const fd = new FormData();
    fd.append('action', 'reset_password');
    fd.append('password', password);
    fd.append('confirm_password', confirmPassword);

    try {
        const res  = await fetch(HANDLER, { method: 'POST', body: fd });
        const data = await res.json();

        if (data.status === 'success') {
            showStep(4);
        } else {
            showAlert('step3', 'danger', data.message);
        }
    } catch (e) {
        showAlert('step3', 'danger', 'Network error. Please try again.');
    } finally {
        setLoading(btn, false);
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>
