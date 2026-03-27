<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
$result = mysqli_query($conn, "SELECT * FROM issues_tb ORDER BY issue_date DESC");
$count = mysqli_num_rows($result);

function fmtDateIR($d){ if(!$d) return '—'; return date('d/m/Y', strtotime($d)); }
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
    background: rgba(0,206,201,0.12);
    color: #00cec9;
    padding: 6px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}
.badge-returned {
    background: #00b894;
    color: #fff;
    border-radius: 50px;
    padding: 4px 12px;
    font-weight: 500;
    font-size: 0.8rem;
}
.badge-pending {
    background: #fdcb6e;
    color: #2d3436;
    border-radius: 50px;
    padding: 4px 12px;
    font-weight: 500;
    font-size: 0.8rem;
}
</style>

<div class="container-fluid py-4 px-4" style="max-width:1200px;">
    <div class="report-header">
        <h4><i class="fas fa-history me-2" style="color:#00cec9;"></i>Issue Requests</h4>
        <span class="report-count"><?php echo $count; ?> record(s)</span>
    </div>

    <?php if($count > 0){ ?>
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Membership Id</th>
                        <th>Name of Book/Movie</th>
                        <th>Serial No</th>
                        <th>Requested Date</th>
                        <th>Fulfilled Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row = mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><code style="background:#eef1f5; padding:3px 10px; border-radius:6px; font-weight:600; color:#0984e3;"><?php echo $row['membership_id']; ?></code></td>
                        <td style="font-weight:500;"><?php echo $row['book_name']; ?></td>
                        <td><code style="background:#eef1f5; padding:3px 10px; border-radius:6px; font-weight:600; color:#6c5ce7;"><?php echo $row['serial_no']; ?></code></td>
                        <td><?php echo fmtDateIR($row['issue_date']); ?></td>
                        <td><?php echo ($row['status'] == 'returned') ? fmtDateIR($row['actual_return_date']) : '—'; ?></td>
                        <td>
                            <?php if($row['status'] == 'returned'){ ?>
                            <span class="badge-returned"><i class="fas fa-check me-1"></i>Returned</span>
                            <?php } else { ?>
                            <span class="badge-pending"><i class="fas fa-clock me-1"></i>Active</span>
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
        <i class="fas fa-inbox" style="font-size:3rem; color:#b2bec3; margin-bottom:16px;"></i>
        <p class="text-muted">No issue requests found.</p>
    </div>
    <?php } ?>

    <div class="mt-3">
        <a href="index.php?reports" class="btn btn-outline-accent"><i class="fas fa-arrow-left me-2"></i>Back to Reports</a>
        <a href="index.php" class="btn btn-outline-accent ms-2"><i class="fas fa-home me-2"></i>Home</a>
    </div>
</div>
