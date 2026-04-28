<?php
/**
 * Clinician Dashboard - Referral Management System (RMS)
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/config/db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/session.php');

requireLogin();
requireRole(['clinician']);

$currentUser = getCurrentUser();
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/header.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/navbar.php'); ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Clinician Dashboard</h1>
                    <p class="text-muted">Welcome back, <?php echo htmlspecialchars($currentUser['name']); ?></p>
                </div>
                <div>
                    <a href="/FinalProject_1/modules/referrals/create_referral.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Referral
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                My Referrals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $referralCount = getRows("SELECT COUNT(*) as count FROM referrals WHERE referring_clinician_id = {$currentUser['id']}")['count'] ?? 0;
                                echo $referralCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $completedCount = getRows("SELECT COUNT(*) as count FROM referrals WHERE referring_clinician_id = {$currentUser['id']} AND referral_status = 'completed'")['count'] ?? 0;
                                echo $completedCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $pendingCount = getRows("SELECT COUNT(*) as count FROM referrals WHERE referring_clinician_id = {$currentUser['id']} AND referral_status = 'pending'")['count'] ?? 0;
                                echo $pendingCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Referrals</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Referral Code</th>
                                    <th>Patient</th>
                                    <th>Receiving Facility</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $referrals = getRows("
                                    SELECT r.*, p.patient_name, f.name as facility_name
                                    FROM referrals r
                                    JOIN patients p ON r.patient_id = p.id
                                    JOIN facilities f ON r.receiving_facility_id = f.id
                                    WHERE r.referring_clinician_id = {$currentUser['id']}
                                    ORDER BY r.created_at DESC
                                    LIMIT 10
                                ");

                                foreach ($referrals as $referral) {
                                    $statusClass = match($referral['referral_status']) {
                                        'pending' => 'badge-warning',
                                        'accepted' => 'badge-info',
                                        'rejected' => 'badge-danger',
                                        'in_progress' => 'badge-primary',
                                        'completed' => 'badge-success',
                                        default => 'badge-secondary'
                                    };
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($referral['referral_code']); ?></td>
                                        <td><?php echo htmlspecialchars($referral['patient_name']); ?></td>
                                        <td><?php echo htmlspecialchars($referral['facility_name']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $statusClass; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $referral['referral_status'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($referral['created_at'])); ?></td>
                                        <td>
                                            <a href="/FinalProject_1/modules/referrals/view_referral.php?id=<?php echo $referral['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/footer.php'); ?>