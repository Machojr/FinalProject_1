<?php
/**
 * Login Page - Referral Management System (RMS)
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/config/db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/session.php');

// Redirect if already logged in
if (isLoggedIn()) {
    redirectToDashboard();
}

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $loginError = 'Please enter both email and password.';
    } else {
        // Check credentials
        $user = getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_active']) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['facility_id'] = $user['facility_id'];
                $_SESSION['last_activity'] = time();

                // Redirect to appropriate dashboard
                redirectToDashboard();
            } else {
                $loginError = 'Your account is inactive. Please contact administrator.';
            }
        } else {
            $loginError = 'Invalid email or password.';
        }
    }
}

function getUserByEmail($email) {
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare(executeQuery("SELECT * FROM users WHERE email = '$email'"), $email);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function redirectToDashboard() {
    if (!isset($_SESSION['role'])) return;

    switch ($_SESSION['role']) {
        case 'clinician':
            header("Location: /FinalProject_1/modules/dashboard/clinician_dashboard.php");
            break;
        case 'admin':
            header("Location: /FinalProject_1/modules/dashboard/admin_dashboard.php");
            break;
        case 'moh':
            header("Location: /FinalProject_1/modules/dashboard/moh_dashboard.php");
            break;
        default:
            header("Location: /FinalProject_1/index.php");
    }
    exit();
}
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/header.php'); ?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0 rounded-3" style="backdrop-filter: blur(10px); background: rgba(255,255,255,0.95);">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-hospital-user fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold text-dark">Welcome to RMS</h2>
                        <p class="text-muted">Referral Management System</p>
                    </div>

                    <?php if ($loginError): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($loginError); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email"
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>

                    <div class="text-center">
                        <p class="text-muted mb-2">Test Credentials</p>
                        <div class="row g-2">
                            <div class="col-4">
                                <small class="text-muted d-block">Clinician</small>
                                <small class="fw-semibold">clinician@rms.go.tz</small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Admin</small>
                                <small class="fw-semibold">admin@rms.go.tz</small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">MoH</small>
                                <small class="fw-semibold">moh@rms.go.tz</small>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">Password: [role]123 (e.g., clinician123)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/footer.php'); ?>