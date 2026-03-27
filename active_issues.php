<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
$result = mysqli_query($conn, "SELECT * FROM issues_tb WHERE status='active' ORDER BY issue_date DESC");
$count = mysqli_num_rows($result);

function fmtDateAI($d){ if(!$d) return '—'; return date('d/m/Y', strtotime($d)); }
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
    background: rgba(253,203,110,0.2);
    color: #f39c12;
    padding: 6px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}
</style>

<div class="container-fluid py-4 px-4" style="max-width:1200px;">
    <div class="report-header">
        <h4><i class="fas fa-clipboard-list me-2" style="color:#f39c12;"></i>Active Issues</h4>
        <span class="report-count"><?php echo $count; ?> active issue(s)</span>
    </div>

    <?php if($count > 0){ ?>
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Serial No</th>
                        <th>Name of Book/Movie</th>
                        <th>Membership Id</th>
                        <th>Date of Issue</th>
                        <th>Date of Return</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row = mysqli_fetch_assoc($result)){ 
                        $is_overdue = (strtotime($row['return_date']) < strtotime(date('Y-m-d')));
                    ?>
                    <tr style="<?php echo $is_overdue ? 'background:rgba(233,69,96,0.05);' : ''; ?>">
                        <td><?php echo $i++; ?></td>
                        <td><code style="background:#eef1f5; padding:3px 10px; border-radius:6px; font-weight:600; color:#6c5ce7;"><?php echo $row['serial_no']; ?></code></td>
                        <td style="font-weight:500;"><?php echo $row['book_name']; ?></td>
                        <td><code style="background:#eef1f5; padding:3px 10px; border-radius:6px; font-weight:600; color:#0984e3;"><?php echo $row['membership_id']; ?></code></td>
                        <td><?php echo fmtDateAI($row['issue_date']); ?></td>
                        <td>
                            <?php echo fmtDateAI($row['return_date']); ?>
                            <?php if($is_overdue){ ?>
                            <span class="badge-expired ms-1" style="font-size:0.7rem;">Overdue</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } else { ?>
    <div class="card-custom p-5 text-center">
        <i class="fas fa-check-circle" style="font-size:3rem; color:#00b894; margin-bottom:16px;"></i>
        <p class="text-muted">No active issues at the moment.</p>
    </div>
    <?php } ?>

    <div class="mt-3">
        <a href="index.php?reports" class="btn btn-outline-accent"><i class="fas fa-arrow-left me-2"></i>Back to Reports</a>
        <a href="index.php" class="btn btn-outline-accent ms-2"><i class="fas fa-home me-2"></i>Home</a>
    </div>
</div>
