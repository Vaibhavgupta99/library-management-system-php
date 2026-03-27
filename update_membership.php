<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

$mem_data = null;
if(isset($_SESSION['lookup_membership'])){
    $mid = $_SESSION['lookup_membership'];
    $mem_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM members_tb WHERE membership_id='".$mid."'"));
    unset($_SESSION['lookup_membership']);
}
?>
<style>
.form-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 36px;
    max-width: 700px;
    margin: 0 auto;
}
.form-card h4 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
    text-align: center;
    margin-bottom: 24px;
}
.form-card h4 i { color: #0984e3; }
.radio-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.radio-pill { position: relative; }
.radio-pill input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.radio-pill label {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 50px;
    border: 2px solid #dee2e6;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    color: #636e72;
}
.radio-pill input[type="radio"]:checked + label {
    background: #e94560;
    color: #fff;
    border-color: #e94560;
    box-shadow: 0 4px 12px rgba(233,69,96,0.3);
}
.radio-pill label:hover { border-color: #e94560; color: #e94560; }
.lookup-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 24px;
}
</style>

<div class="container py-4">
    <div class="form-card">
        <h4><i class="fas fa-user-edit me-2"></i>Update Membership</h4>

        <?php if(isset($_SESSION['err'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #e94560;">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>
        <?php if(isset($_SESSION['success'])){ ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #00b894;">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>

        <div class="lookup-section">
            <form action="conn.php" method="post" class="d-flex gap-2 align-items-end">
                <input type="hidden" name="lookup_membership" value="1">
                <div class="flex-fill">
                    <label class="form-label fw-500 mb-1"><i class="fas fa-search me-1" style="color:#e94560;"></i>Membership Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="membership_id" placeholder="e.g. MEM000001" value="<?php echo $mem_data ? $mem_data['membership_id'] : ''; ?>" required>
                </div>
                <button type="submit" class="btn btn-accent" style="height:44px;"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <?php if($mem_data){ ?>
        <form action="conn.php" method="post">
            <input type="hidden" name="update_membership" value="1">
            <input type="hidden" name="membership_id" value="<?php echo $mem_data['membership_id']; ?>">

            <div class="mb-3">
                <label class="form-label fw-500">Member Name</label>
                <div class="form-control" style="background:#f8f9fa;"><?php echo $mem_data['first_name'].' '.$mem_data['last_name']; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-500"><i class="fas fa-calendar me-2" style="color:#e94560;"></i>Start Date</label>
                    <input type="date" class="form-control" name="start_date" value="<?php echo $mem_data['start_date']; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500"><i class="fas fa-calendar-check me-2" style="color:#e94560;"></i>End Date</label>
                    <input type="date" class="form-control" name="end_date" id="updEndDate" value="<?php echo $mem_data['end_date']; ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-clock me-2" style="color:#e94560;"></i>Membership Extension</label>
                <div class="radio-group">
                    <div class="radio-pill">
                        <input type="radio" name="action_type" value="6months" id="ext6">
                        <label for="ext6">Six Months</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="action_type" value="1year" id="ext1">
                        <label for="ext1">One Year</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="action_type" value="2years" id="ext2">
                        <label for="ext2">Two Years</label>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-500"><i class="fas fa-ban me-2" style="color:#e94560;"></i>Remove Membership</label>
                <div class="radio-group">
                    <div class="radio-pill">
                        <input type="radio" name="action_type" value="remove" id="extRemove">
                        <label for="extRemove" style="border-color:#e94560; color:#e94560;"><i class="fas fa-trash-alt me-1"></i>Deactivate</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="index.php?maintenance" class="btn btn-outline-accent flex-fill"><i class="fas fa-times me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-accent flex-fill"><i class="fas fa-check me-2"></i>Update</button>
            </div>
        </form>
        <?php } elseif(!$mem_data && isset($_GET['looked'])){ ?>
        <div class="text-center py-3">
            <p class="text-muted">Enter a valid membership number above to load details.</p>
        </div>
        <?php } ?>
    </div>
</div>
