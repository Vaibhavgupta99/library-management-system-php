<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
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
    gap: 16px;
    flex-wrap: wrap;
}
.radio-pill {
    position: relative;
}
.radio-pill input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.radio-pill label {
    display: inline-block;
    padding: 10px 24px;
    border-radius: 50px;
    border: 2px solid #dee2e6;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    color: #636e72;
}
.radio-pill input[type="radio"]:checked + label {
    background: #e94560;
    color: #fff;
    border-color: #e94560;
    box-shadow: 0 4px 12px rgba(233,69,96,0.3);
}
.radio-pill label:hover {
    border-color: #e94560;
    color: #e94560;
}
</style>

<div class="container py-4">
    <div class="form-card">
        <h4><i class="fas fa-user-plus me-2"></i>Add Membership</h4>

        <?php if(isset($_SESSION['err'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #e94560;">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>

        <form action="conn.php" method="post">
            <input type="hidden" name="add_membership" value="1">

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-500"><i class="fas fa-user me-2" style="color:#e94560;"></i>First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_name" required placeholder="Enter first name">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500"><i class="fas fa-user me-2" style="color:#e94560;"></i>Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="last_name" required placeholder="Enter last name">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-phone me-2" style="color:#e94560;"></i>Contact Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="contact_name" required placeholder="Contact person name">
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-map-marker-alt me-2" style="color:#e94560;"></i>Contact Address <span class="text-danger">*</span></label>
                <textarea class="form-control" name="contact_address" rows="2" required placeholder="Full address"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-id-card me-2" style="color:#e94560;"></i>Aadhar Card No <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="aadhar_no" required placeholder="12-digit Aadhar number" maxlength="12">
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-500"><i class="fas fa-calendar me-2" style="color:#e94560;"></i>Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="start_date" id="memStartDate" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500"><i class="fas fa-calendar-check me-2" style="color:#e94560;"></i>End Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="end_date" id="memEndDate" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-500"><i class="fas fa-clock me-2" style="color:#e94560;"></i>Membership Type <span class="text-danger">*</span></label>
                <div class="radio-group">
                    <div class="radio-pill">
                        <input type="radio" name="membership_type" value="6months" id="mem6" checked>
                        <label for="mem6"><i class="fas fa-calendar-alt me-1"></i>Six Months</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="membership_type" value="1year" id="mem1">
                        <label for="mem1"><i class="fas fa-calendar-alt me-1"></i>One Year</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="membership_type" value="2years" id="mem2">
                        <label for="mem2"><i class="fas fa-calendar-alt me-1"></i>Two Years</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="index.php?maintenance" class="btn btn-outline-accent flex-fill"><i class="fas fa-times me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-accent flex-fill"><i class="fas fa-check me-2"></i>Confirm</button>
            </div>
        </form>
    </div>
</div>

<script>
var startDate = document.getElementById('memStartDate');
var endDate = document.getElementById('memEndDate');
var today = new Date().toISOString().split('T')[0];
startDate.value = today;
startDate.min = today;

var radios = document.querySelectorAll('input[name="membership_type"]');

function updateEndDate(){
    var start = new Date(startDate.value);
    var selected = document.querySelector('input[name="membership_type"]:checked').value;
    if(selected == '6months') start.setMonth(start.getMonth() + 6);
    else if(selected == '1year') start.setFullYear(start.getFullYear() + 1);
    else start.setFullYear(start.getFullYear() + 2);
    endDate.value = start.toISOString().split('T')[0];
}

updateEndDate();
startDate.addEventListener('change', updateEndDate);
radios.forEach(function(r){ r.addEventListener('change', updateEndDate); });
</script>
