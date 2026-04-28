<?php
/**
 * Admin Dashboard - Referral Management System (RMS)
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/config/db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/session.php');

requireLogin();
requireRole(['admin']);

$currentUser = getCurrentUser();
$facilityId = $currentUser['facility_id'];
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/header.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/navbar.php'); ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Hospital Administrator Dashboard</h1>
                    <p class="text-muted">Manage referrals for your facility</p>
                </div>
                <div>
                    <a href="/FinalProject_1/modules/referrals/view_referrals.php" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>View All Referrals
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Incoming Referrals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $incomingCount = getRows("SELECT COUNT(*) as count FROM referrals WHERE receiving_facility_id = $facilityId")['count'] ?? 0;
                                echo $incomingCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-inbox fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Accepted
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $acceptedCount = getRows("SELECT COUNT(*) as count FROM referrals WHERE receiving_facility_id = $facilityId AND referral_status = 'accepted'")['count'] ?? 0;
                                echo $acceptedCount;
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

        <div class="col-md-3 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                In Progress
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $progressCount = getRows("SELECT COUNT(*) as count FROM referrals WHERE receiving_facility_id = $facilityId AND referral_status = 'in_progress'")['count'] ?? 0;
                                echo $progressCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Review
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $pendingCount = getRows("SELECT COUNT(*) as count FROM referrals WHERE receiving_facility_id = $facilityId AND referral_status = 'pending'")['count'] ?? 0;
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
                    <h6 class="m-0 font-weight-bold text-primary">Recent Referrals to Review</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Referral Code</th>
                                    <th>Patient</th>
                                    <th>Referring Facility</th>
                                    <th>Urgency</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $referrals = getRows("
                                    SELECT r.*, p.patient_name, f.name as referring_facility
                                    FROM referrals r
                                    JOIN patients p ON r.patient_id = p.id
                                    JOIN facilities f ON r.referring_facility_id = f.id
                                    WHERE r.receiving_facility_id = $facilityId
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

                                    $urgencyClass = match($referral['urgency_level']) {
                                        'emergency' => 'badge-danger',
                                        'urgent' => 'badge-warning',
                                        'routine' => 'badge-info',
                                        default => 'badge-secondary'
                                    };
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($referral['referral_code']); ?></td>
                                        <td><?php echo htmlspecialchars($referral['patient_name']); ?></td>
                                        <td><?php echo htmlspecialchars($referral['referring_facility']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $urgencyClass; ?>">
                                                <?php echo ucfirst($referral['urgency_level']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $statusClass; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $referral['referral_status'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($referral['created_at'])); ?></td>
                                        <td>
                                            <a href="/FinalProject_1/modules/referrals/view_referral.php?id=<?php echo $referral['id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($referral['referral_status'] === 'pending'): ?>
                                                <a href="/FinalProject_1/modules/referrals/update_status.php?id=<?php echo $referral['id']; ?>&action=accept" class="btn btn-sm btn-success me-1">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="/FinalProject_1/modules/referrals/update_status.php?id=<?php echo $referral['id']; ?>&action=reject" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
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