<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

$user_data = null;
if(isset($_SESSION['lookup_user'])){
    $uname = $_SESSION['lookup_user'];
    $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users_tb WHERE username='".$uname."'"));
    unset($_SESSION['lookup_user']);
}
?>
<style>
.form-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 36px;
    max-width: 650px;
    margin: 0 auto;
}
.form-card h4 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
    text-align: center;
    margin-bottom: 24px;
}
.form-card h4 i { color: #e94560; }
.radio-group { display: flex; gap: 16px; flex-wrap: wrap; }
.radio-pill { position: relative; }
.radio-pill input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
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
.radio-pill label:hover { border-color: #e94560; color: #e94560; }
.check-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: #f8f9fa;
    border-radius: 10px;
}
.check-toggle .form-check-input {
    width: 20px;
    height: 20px;
    margin: 0;
}
.check-toggle .form-check-label { font-weight: 500; margin: 0; }
</style>

<div class="container py-4">
    <div class="form-card">
        <h4><i class="fas fa-users-cog me-2"></i>User Management</h4>

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

        <form action="conn.php" method="post">
            <input type="hidden" name="manage_user" value="1">

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-user-tag me-2" style="color:#e94560;"></i>User Type</label>
                <div class="radio-group">
                    <div class="radio-pill">
                        <input type="radio" name="user_type" value="new" id="userNew" <?php echo !$user_data ? 'checked' : ''; ?>>
                        <label for="userNew"><i class="fas fa-user-plus me-1"></i>New User</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="user_type" value="existing" id="userExisting" <?php echo $user_data ? 'checked' : ''; ?>>
                        <label for="userExisting"><i class="fas fa-user-edit me-1"></i>Existing User</label>
                    </div>
                </div>
            </div>

            <div id="lookupSection" class="mb-3" style="display:<?php echo $user_data ? 'block' : 'none'; ?>;">
                <label class="form-label fw-500"><i class="fas fa-search me-2" style="color:#e94560;"></i>Lookup Username</label>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" name="lookup_username" id="lookupUsername" placeholder="Enter username to find" value="<?php echo $user_data ? $user_data['username'] : ''; ?>">
                    <button type="submit" name="lookup_user_btn" value="1" class="btn btn-accent" style="white-space:nowrap;"><i class="fas fa-search"></i></button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-user me-2" style="color:#e94560;"></i>Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="<?php echo $user_data ? $user_data['name'] : ''; ?>" required placeholder="Full name">
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-at me-2" style="color:#e94560;"></i>Username <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="username" value="<?php echo $user_data ? $user_data['username'] : ''; ?>" required placeholder="Login username" <?php echo $user_data ? 'readonly style="background:#f8f9fa;"' : ''; ?>>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-lock me-2" style="color:#e94560;"></i>Password <?php echo !$user_data ? '<span class="text-danger">*</span>' : '<span class="text-muted">(leave blank to keep current)</span>'; ?></label>
                <input type="password" class="form-control" name="password" <?php echo !$user_data ? 'required' : ''; ?> placeholder="<?php echo $user_data ? 'Leave blank to keep current' : 'Set password'; ?>">
            </div>

            <div class="d-flex gap-3 mb-4">
                <div class="check-toggle flex-fill">
                    <input class="form-check-input" type="checkbox" name="status_active" id="statusActive" <?php echo (!$user_data || $user_data['status']=='active') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="statusActive"><i class="fas fa-check-circle me-1" style="color:#00b894;"></i>Active</label>
                </div>
                <div class="check-toggle flex-fill">
                    <input class="form-check-input" type="checkbox" name="is_admin" id="isAdmin" <?php echo ($user_data && $user_data['role']=='admin') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="isAdmin"><i class="fas fa-shield-halved me-1" style="color:#6c5ce7;"></i>Admin</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="index.php?maintenance" class="btn btn-outline-accent flex-fill"><i class="fas fa-times me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-accent flex-fill"><i class="fas fa-check me-2"></i><?php echo $user_data ? 'Update User' : 'Create User'; ?></button>
            </div>
        </form>
    </div>
</div>

<script>
var newRadio = document.getElementById('userNew');
var existingRadio = document.getElementById('userExisting');
var lookupSection = document.getElementById('lookupSection');

newRadio.addEventListener('change', function(){
    if(this.checked) lookupSection.style.display = 'none';
});
existingRadio.addEventListener('change', function(){
    if(this.checked) lookupSection.style.display = 'block';
});
</script>
