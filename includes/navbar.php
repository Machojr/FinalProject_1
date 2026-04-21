<?php
/**
 * Navigation Bar Template
 * Referral Management System (RMS)
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/config/db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/session.php');

$currentUser = getCurrentUser();
?>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1e3a8a 0%, #0ea5e9 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-5" href="/FinalProject_1/modules/dashboard/">
            <i class="fas fa-hospital-user me-2"></i>RMS
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isLoggedIn()): ?>
                    <?php if (hasRole('clinician')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/FinalProject_1/modules/dashboard/clinician_dashboard.php">
                                <i class="fas fa-chart-line me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FinalProject_1/modules/referrals/create_referral.php">
                                <i class="fas fa-plus-circle me-1"></i> Create Referral
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FinalProject_1/modules/referrals/view_referrals.php">
                                <i class="fas fa-list me-1"></i> My Referrals
                            </a>
                        </li>
                    <?php elseif (hasRole('admin')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/FinalProject_1/modules/dashboard/admin_dashboard.php">
                                <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FinalProject_1/modules/referrals/view_referrals.php">
                                <i class="fas fa-inbox me-1"></i> Referrals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FinalProject_1/modules/facilities/manage_facilities.php">
                                <i class="fas fa-hospital me-1"></i> Facilities
                            </a>
                        </li>
                    <?php elseif (hasRole('moh')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/FinalProject_1/modules/dashboard/moh_dashboard.php">
                                <i class="fas fa-chart-bar me-1"></i> Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FinalProject_1/modules/referrals/view_referrals.php">
                                <i class="fas fa-file-alt me-1"></i> Reports
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars(substr($currentUser['name'], 0, 12)); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-1"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/FinalProject_1/modules/auth/logout.php">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/FinalProject_1/modules/auth/login.php">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
