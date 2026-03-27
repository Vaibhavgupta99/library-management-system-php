<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
$today = date('Y-m-d');
$result = mysqli_query($conn, "SELECT * FROM issues_tb WHERE status='active' AND return_date < '".$today."' ORDER BY return_date ASC");
$count = mysqli_num_rows($result);

function fmtDateOD($d){ if(!$d) return '—'; return date('d/m/Y', strtotime($d)); }
?>
<style>
.report-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}
.report-header h4 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
    margin: 0;
}
.report-count {
    background: rgba(233,69,96,0.1);
    color: #e94560;
    padding: 6px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}
.fine-badge {
    background: rgba(233,69,96,0.1);
    color: #e94560;
    padding: 4px 12px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.85rem;
}
</style>

<div class="container-fluid py-4 px-4" style="max-width:1200px;">
    <div class="report-header">
        <h4><i class="fas fa-exclamation-triangle me-2" style="color:#e94560;"></i>Overdue Returns</h4>
        <span class="report-count"><?php echo $count; ?> overdue item(s)</span>
    </div>

    <?php if($count > 0){ ?>
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Serial No</th>
                        <th>Name of Book</th>
                        <th>Membership Id</th>
                        <th>Date of Issue</th>
                        <th>Date of Return</th>
                        <th>Days Overdue</th>
                        <th>Fine (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row = mysqli_fetch_assoc($result)){ 
                        $days_overdue = floor((strtotime($today) - strtotime($row['return_date'])) / (60*60*24));
                        $fine = $days_overdue * 5;
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><code style="background:#eef1f5; padding:3px 10px; border-radius:6px; font-weight:600; color:#6c5ce7;"><?php echo $row['serial_no']; ?></code></td>
                        <td style="font-weight:500;"><?php echo $row['book_name']; ?></td>
                        <td><code style="background:#eef1f5; padding:3px 10px; border-radius:6px; font-weight:600; color:#0984e3;"><?php echo $row['membership_id']; ?></code></td>
                        <td><?php echo fmtDateOD($row['issue_date']); ?></td>
                        <td><?php echo fmtDateOD($row['return_date']); ?></td>
                        <td><span class="badge-expired"><?php echo $days_overdue; ?> day(s)</span></td>
                        <td><span class="fine-badge">₹<?php echo number_format($fine, 2); ?></span></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } else { ?>
    <div class="card-custom p-5 text-center">
        <i class="fas fa-check-circle" style="font-size:3rem; color:#00b894; margin-bottom:16px;"></i>
        <p class="text-muted">No overdue returns. All books are on time!</p>
    </div>
    <?php } ?>

    <div class="mt-3">
        <a href="index.php?reports" class="btn btn-outline-accent"><i class="fas fa-arrow-left me-2"></i>Back to Reports</a>
        <a href="index.php" class="btn btn-outline-accent ms-2"><i class="fas fa-home me-2"></i>Home</a>
    </div>
</div>
