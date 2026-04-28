<?php
/**
 * MoH Dashboard - Referral Management System (RMS)
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/config/db.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/session.php');

requireLogin();
requireRole(['moh']);

$currentUser = getCurrentUser();
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/header.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/navbar.php'); ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Ministry of Health Dashboard</h1>
                    <p class="text-muted">National referral analytics and reporting</p>
                </div>
                <div>
                    <a href="/FinalProject_1/modules/referrals/view_referrals.php" class="btn btn-primary">
                        <i class="fas fa-chart-bar me-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Referrals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $totalReferrals = getRows("SELECT COUNT(*) as count FROM referrals")['count'] ?? 0;
                                echo number_format($totalReferrals);
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

        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $completedReferrals = getRows("SELECT COUNT(*) as count FROM referrals WHERE referral_status = 'completed'")['count'] ?? 0;
                                echo number_format($completedReferrals);
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
                                Active Facilities
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $activeFacilities = getRows("SELECT COUNT(*) as count FROM facilities")['count'] ?? 0;
                                echo $activeFacilities;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital fa-2x text-gray-300"></i>
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
                                Success Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $successRate = $totalReferrals > 0 ? round(($completedReferrals / $totalReferrals) * 100, 1) : 0;
                                echo $successRate . '%';
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Referral Status Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Facility Types</h6>
                </div>
                <div class="card-body">
                    <canvas id="facilityChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Referral Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Referral Code</th>
                                    <th>Patient</th>
                                    <th>From Facility</th>
                                    <th>To Facility</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recentReferrals = getRows("
                                    SELECT r.*, p.patient_name,
                                           rf.name as referring_facility,
                                           tf.name as receiving_facility
                                    FROM referrals r
                                    JOIN patients p ON r.patient_id = p.id
                                    JOIN facilities rf ON r.referring_facility_id = rf.id
                                    JOIN facilities tf ON r.receiving_facility_id = tf.id
                                    ORDER BY r.created_at DESC
                                    LIMIT 15
                                ");

                                foreach ($recentReferrals as $referral) {
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
                                        <td><?php echo htmlspecialchars($referral['referring_facility']); ?></td>
                                        <td><?php echo htmlspecialchars($referral['receiving_facility']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $statusClass; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $referral['referral_status'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($referral['created_at'])); ?></td>
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

<!-- Chart.js for analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Referral Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusData = {
    labels: ['Pending', 'Accepted', 'Rejected', 'In Progress', 'Completed'],
    datasets: [{
        label: 'Referrals',
        data: [
            <?php echo getRows("SELECT COUNT(*) as count FROM referrals WHERE referral_status = 'pending'")['count'] ?? 0; ?>,
            <?php echo getRows("SELECT COUNT(*) as count FROM referrals WHERE referral_status = 'accepted'")['count'] ?? 0; ?>,
            <?php echo getRows("SELECT COUNT(*) as count FROM referrals WHERE referral_status = 'rejected'")['count'] ?? 0; ?>,
            <?php echo getRows("SELECT COUNT(*) as count FROM referrals WHERE referral_status = 'in_progress'")['count'] ?? 0; ?>,
            <?php echo getRows("SELECT COUNT(*) as count FROM referrals WHERE referral_status = 'completed'")['count'] ?? 0; ?>
        ],
        backgroundColor: [
            'rgba(255, 193, 7, 0.8)',
            'rgba(23, 162, 184, 0.8)',
            'rgba(220, 53, 69, 0.8)',
            'rgba(0, 123, 255, 0.8)',
            'rgba(40, 167, 69, 0.8)'
        ],
        borderWidth: 1
    }]
};

new Chart(statusCtx, {
    type: 'bar',
    data: statusData,
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Facility Types Chart
const facilityCtx = document.getElementById('facilityChart').getContext('2d');
const facilityData = {
    labels: ['Dispensary', 'Health Centre', 'District Hospital', 'Regional Hospital', 'National Hospital'],
    datasets: [{
        label: 'Facilities',
        data: [
            <?php echo getRows("SELECT COUNT(*) as count FROM facilities WHERE facility_type = 'dispensary'")['count'] ?? 0; ?>,
            <?php echo getRows("SELECT COUNT(*) as count FROM facilities WHERE facility_type = 'health_centre'")['count'] ?? 0; ?>,
            <?php echo getRows("SELECT COUNT(*) as count FROM facilities WHERE facility_type = 'district_hospital'")['count'] ?? 0; ?>,
            <?php echo getRows("SELECT COUNT(*) as count FROM facilities WHERE facility_type = 'regional_hospital'")['count'] ?? 0; ?>,
            <?php echo getRows("SELECT COUNT(*) as count FROM facilities WHERE facility_type = 'national_hospital'")['count'] ?? 0; ?>
        ],
        backgroundColor: [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 205, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)'
        ]
    }]
};

new Chart(facilityCtx, {
    type: 'doughnut',
    data: facilityData,
    options: {
        responsive: true
    }
});
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/FinalProject_1/includes/footer.php'); ?>