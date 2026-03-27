<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
$result = mysqli_query($conn, "SELECT * FROM members_tb ORDER BY membership_id");
$count = mysqli_num_rows($result);

function fmtDateMem($d){ if(!$d) return '—'; return date('d/m/Y', strtotime($d)); }
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
    background: rgba(9,132,227,0.1);
    color: #0984e3;
    padding: 6px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}
</style>

<div class="container-fluid py-4 px-4" style="max-width:1200px;">
    <div class="report-header">
        <h4><i class="fas fa-users me-2" style="color:#0984e3;"></i>Master List of Memberships</h4>
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
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Contact Address</th>
                        <th>Aadhar No</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Fine Pending</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row = mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><code style="background:#eef1f5; padding:3px 10px; border-radius:6px; font-weight:600; color:#0984e3;"><?php echo $row['membership_id']; ?></code></td>
                        <td style="font-weight:500;"><?php echo $row['first_name'].' '.$row['last_name']; ?></td>
                        <td><?php echo $row['contact_name']; ?></td>
                        <td style="max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?php echo $row['contact_address']; ?>"><?php echo $row['contact_address']; ?></td>
                        <td><?php echo $row['aadhar_no']; ?></td>
                        <td><?php echo fmtDateMem($row['start_date']); ?></td>
                        <td><?php echo fmtDateMem($row['end_date']); ?></td>
                        <td>
                            <?php if($row['status'] == 'active'){ ?>
                            <span class="badge-active">Active</span>
                            <?php } else { ?>
                            <span class="badge-inactive">Inactive</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($row['fine_pending'] > 0){ ?>
                            <span style="color:#e94560; font-weight:600;">₹<?php echo number_format($row['fine_pending'], 2); ?></span>
                            <?php } else { ?>
                            <span style="color:#00b894;">₹0.00</span>
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
        <p class="text-muted">No memberships found.</p>
    </div>
    <?php } ?>

    <div class="mt-3">
        <a href="index.php?reports" class="btn btn-outline-accent"><i class="fas fa-arrow-left me-2"></i>Back to Reports</a>
        <a href="index.php" class="btn btn-outline-accent ms-2"><i class="fas fa-home me-2"></i>Home</a>
    </div>
</div>
