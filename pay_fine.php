<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

$fine_book = isset($_SESSION['fine_book']) ? $_SESSION['fine_book'] : '';
$fine_author = isset($_SESSION['fine_author']) ? $_SESSION['fine_author'] : '';
$fine_serial = isset($_SESSION['fine_serial']) ? $_SESSION['fine_serial'] : '';
$fine_issue_date = isset($_SESSION['fine_issue_date']) ? $_SESSION['fine_issue_date'] : '';
$fine_return_date = isset($_SESSION['fine_return_date']) ? $_SESSION['fine_return_date'] : '';
$fine_actual_return = isset($_SESSION['fine_actual_return']) ? $_SESSION['fine_actual_return'] : '';
$fine_amount = isset($_SESSION['fine_amount']) ? $_SESSION['fine_amount'] : 0;
$fine_membership = isset($_SESSION['fine_membership']) ? $_SESSION['fine_membership'] : '';
$fine_remarks = isset($_SESSION['fine_remarks']) ? $_SESSION['fine_remarks'] : '';
$fine_issue_id = isset($_SESSION['fine_issue_id']) ? $_SESSION['fine_issue_id'] : '';

function fmtDate($d){ if(!$d) return ''; return date('d/m/Y', strtotime($d)); }
?>
<style>
.fine-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 36px;
    max-width: 650px;
    margin: 0 auto;
}
.fine-card h4 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
    text-align: center;
    margin-bottom: 24px;
}
.fine-card h4 i { color: #e94560; }
.fine-amount {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin-bottom: 20px;
}
.fine-amount .amount {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 2rem;
    color: #e94560;
}
.fine-amount .label {
    color: rgba(255,255,255,0.7);
    font-size: 0.85rem;
}
.readonly-field {
    background: #f8f9fa;
    border: 1.5px solid #dee2e6;
    border-radius: 8px;
    padding: 10px 14px;
    color: #2d3436;
    font-size: 0.95rem;
}
</style>

<div class="container py-4">
    <div class="fine-card">
        <h4><i class="fas fa-rupee-sign me-2"></i>Pay Fine</h4>

        <?php if(isset($_SESSION['err'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #e94560;">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>

        <?php if($fine_book != ''){ ?>

        <div class="fine-amount">
            <div class="label">Fine Calculated</div>
            <div class="amount">₹<?php echo number_format($fine_amount, 2); ?></div>
        </div>

        <form action="conn.php" method="post">
            <input type="hidden" name="pay_fine" value="1">
            <input type="hidden" name="issue_id" value="<?php echo $fine_issue_id; ?>">
            <input type="hidden" name="serial_no" value="<?php echo $fine_serial; ?>">
            <input type="hidden" name="membership_id" value="<?php echo $fine_membership; ?>">
            <input type="hidden" name="fine_amount" value="<?php echo $fine_amount; ?>">

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-500">Book Name</label>
                    <div class="readonly-field"><?php echo $fine_book; ?></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Author</label>
                    <div class="readonly-field"><?php echo $fine_author; ?></div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-500">Serial No</label>
                    <div class="readonly-field"><code style="color:#6c5ce7; font-weight:600;"><?php echo $fine_serial; ?></code></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Membership ID</label>
                    <div class="readonly-field"><?php echo $fine_membership; ?></div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label fw-500">Issue Date</label>
                    <div class="readonly-field"><?php echo fmtDate($fine_issue_date); ?></div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label fw-500">Return Date</label>
                    <div class="readonly-field"><?php echo fmtDate($fine_return_date); ?></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500">Actual Return</label>
                    <div class="readonly-field"><?php echo fmtDate($fine_actual_return); ?></div>
                </div>
            </div>

            <?php if($fine_amount > 0){ ?>
            <div class="mb-3 p-3" style="background:#fff3f3; border-radius:10px; border:1px solid rgba(233,69,96,0.2);">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="fine_paid_check" id="finePaidCheck" style="width:20px; height:20px;">
                    <label class="form-check-label ms-2 fw-500" for="finePaidCheck" style="padding-top:2px;">
                        <i class="fas fa-check-circle me-1" style="color:#e94560;"></i>Mark Fine as Paid (₹<?php echo number_format($fine_amount, 2); ?>)
                    </label>
                </div>
            </div>
            <?php } ?>

            <div class="mb-4">
                <label class="form-label fw-500">Remarks</label>
                <textarea class="form-control" name="remarks" rows="2" placeholder="Optional remarks..."><?php echo $fine_remarks; ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <a href="index.php?transactions" class="btn btn-outline-accent flex-fill" onclick="return confirm('Are you sure? Fine data will be lost.')"><i class="fas fa-times me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-accent flex-fill"><i class="fas fa-check me-2"></i>Confirm Payment</button>
            </div>
        </form>

        <?php } else { ?>

        <div class="text-center py-4">
            <i class="fas fa-info-circle" style="font-size:2.5rem; color:#b2bec3; margin-bottom:16px;"></i>
            <p class="text-muted">No pending fine to process. Please return a book first.</p>
            <a href="index.php?transactions&return_book" class="btn btn-accent mt-2"><i class="fas fa-undo-alt me-2"></i>Return a Book</a>
        </div>

        <?php } ?>
    </div>
</div>
