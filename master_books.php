<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
$result = mysqli_query($conn, "SELECT * FROM books_tb WHERE type='book' ORDER BY serial_no");
$count = mysqli_num_rows($result);

function fmtDateB($d){ if(!$d) return '—'; return date('d/m/Y', strtotime($d)); }
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
    background: rgba(108,92,231,0.1);
    color: #6c5ce7;
    padding: 6px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}
</style>

<div class="container-fluid py-4 px-4" style="max-width:1200px;">
    <div class="report-header">
        <h4><i class="fas fa-book me-2" style="color:#6c5ce7;"></i>Master List of Books</h4>
        <span class="report-count"><?php echo $count; ?> record(s)</span>
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
                        <th>Author Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Cost (₹)</th>
                        <th>Procurement Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row = mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><code style="background:#eef1f5; padding:3px 10px; border-radius:6px; font-weight:600; color:#6c5ce7;"><?php echo $row['serial_no']; ?></code></td>
                        <td style="font-weight:500;"><?php echo $row['name']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td>
                            <?php if($row['status'] == 'available'){ ?>
                            <span class="badge-available">Available</span>
                            <?php } else { ?>
                            <span class="badge-issued">Issued</span>
                            <?php } ?>
                        </td>
                        <td>₹<?php echo number_format($row['cost'], 2); ?></td>
                        <td><?php echo fmtDateB($row['procurement_date']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } else { ?>
    <div class="card-custom p-5 text-center">
        <i class="fas fa-inbox" style="font-size:3rem; color:#b2bec3; margin-bottom:16px;"></i>
        <p class="text-muted">No books found in the library.</p>
    </div>
    <?php } ?>

    <div class="mt-3">
        <a href="index.php?reports" class="btn btn-outline-accent"><i class="fas fa-arrow-left me-2"></i>Back to Reports</a>
        <a href="index.php" class="btn btn-outline-accent ms-2"><i class="fas fa-home me-2"></i>Home</a>
    </div>
</div>
