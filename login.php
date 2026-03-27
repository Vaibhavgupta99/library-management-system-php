<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
?>
<style>
.login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    position: relative;
    overflow: hidden;
}
.login-wrapper::before {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(233,69,96,0.15) 0%, transparent 70%);
    top: -100px;
    right: -100px;
    border-radius: 50%;
}
.login-wrapper::after {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(233,69,96,0.1) 0%, transparent 70%);
    bottom: -80px;
    left: -80px;
    border-radius: 50%;
}
.login-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    padding: 48px 40px;
    width: 100%;
    max-width: 420px;
    position: relative;
    z-index: 1;
    animation: slideUp 0.6s ease;
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.login-logo {
    text-align: center;
    margin-bottom: 32px;
}
.login-logo i {
    font-size: 2.5rem;
    color: #e94560;
    margin-bottom: 12px;
    display: block;
}
.login-logo h2 {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    color: #1a1a2e;
    font-size: 1.8rem;
    letter-spacing: 1px;
    margin: 0;
}
.login-logo p {
    color: #636e72;
    font-size: 0.9rem;
    margin-top: 4px;
}
.login-card .form-label {
    font-weight: 500;
    color: #2d3436;
    font-size: 0.9rem;
    margin-bottom: 6px;
}
.login-card .form-control {
    border-radius: 8px;
    padding: 12px 14px;
    border: 1.5px solid #dee2e6;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}
.login-card .form-control:focus {
    border-color: #e94560;
    box-shadow: 0 0 0 0.2rem rgba(233,69,96,0.15);
}
.login-btn {
    background: #e94560;
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 12px;
    font-size: 1rem;
    font-weight: 600;
    width: 100%;
    transition: all 0.2s ease;
    font-family: 'Poppins', sans-serif;
    letter-spacing: 0.5px;
}
.login-btn:hover {
    background: #d63851;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(233,69,96,0.4);
}
.input-group-text {
    background: transparent;
    border: 1.5px solid #dee2e6;
    border-left: none;
    border-radius: 0 8px 8px 0;
    cursor: pointer;
    color: #636e72;
}
.input-group .form-control {
    border-right: none;
    border-radius: 8px 0 0 8px;
}
</style>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-logo">
            <i class="fas fa-book-open"></i>
            <h2>LibraryMS</h2>
            <p>Sign in to your account</p>
        </div>

        <?php if(isset($_SESSION['err'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #e94560; font-size:0.9rem;">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>

        <form action="conn.php" method="post">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user me-2" style="color:#e94560;"></i>Username</label>
                <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-4">
                <label class="form-label"><i class="fas fa-lock me-2" style="color:#e94560;"></i>Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="pass" id="loginPass" placeholder="Enter your password" required>
                    <span class="input-group-text" id="togglePass"><i class="fas fa-eye"></i></span>
                </div>
            </div>
            <button type="submit" name="login" class="login-btn">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>

        <div class="text-center mt-4" style="font-size:0.8rem; color:#b2bec3;">
            <i class="fas fa-shield-halved me-1"></i> Secure Library Management Portal
        </div>
    </div>
</div>

<script>
document.getElementById('togglePass').addEventListener('click', function(){
    var passField = document.getElementById('loginPass');
    var icon = this.querySelector('i');
    if(passField.type === 'password'){
        passField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>
