<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
if(!isset($_SESSION['admin'])){
    $_SESSION['err'] = "Access denied. Admin privileges required.";
    header('location:index.php');
    exit;
}

if(isset($_GET['add_membership'])){
    include_once './add_membership.php';
} elseif(isset($_GET['update_membership'])){
    include_once './update_membership.php';
} elseif(isset($_GET['add_book'])){
    include_once './add_book.php';
} elseif(isset($_GET['update_book'])){
    include_once './update_book.php';
} elseif(isset($_GET['user_management'])){
    include_once './user_management.php';
} else {
?>
<style>
.maint-section {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 28px;
    margin-bottom: 20px;
}
.maint-section h5 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f0f2f5;
}
.maint-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 14px 24px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.25s ease;
    font-size: 0.9rem;
}
.maint-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(26,26,46,0.3);
    color: #fff;
}
.maint-btn i { color: #e94560; font-size: 1.1rem; }
.maint-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: #1a1a2e;
    border: 2px solid #1a1a2e;
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.25s ease;
    font-size: 0.9rem;
}
.maint-btn-outline:hover {
    background: #1a1a2e;
    color: #fff;
    transform: translateY(-3px);
}
.maint-btn-outline:hover i { color: #e94560; }
</style>

<nav class="navbar navbar-expand-lg fixed-top" style="background:#1a1a2e; padding:12px 24px; box-shadow:0 2px 20px rgba(0,0,0,0.3);">
    <a class="navbar-brand fw-bold text-white" style="font-size:1.4rem; letter-spacing:1px;" href="index.php">
        <i class="fas fa-book-open me-2" style="color:#e94560;"></i>LibraryMS
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
        <i class="fas fa-bars text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="navContent">
        <div class="ms-auto d-flex align-items-center gap-2 flex-wrap mt-2 mt-lg-0">
            <a href="index.php" class="nav-link-custom"><i class="fas fa-home me-1"></i>Home</a>
            <a href="index.php?transactions" class="nav-link-custom"><i class="fas fa-exchange-alt me-1"></i>Transactions</a>
            <a href="index.php?reports" class="nav-link-custom"><i class="fas fa-chart-bar me-1"></i>Reports</a>
            <a href="index.php?maintenance" class="nav-link-custom active"><i class="fas fa-cogs me-1"></i>Maintenance</a>
            <span class="text-white opacity-50 d-none d-lg-inline mx-1">|</span>
            <span class="text-white opacity-75 d-none d-lg-inline" style="font-size:0.85rem;">
                <i class="fas fa-user-circle me-1"></i><?php echo $_SESSION['admin']; ?>
            </span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm" style="border-radius:50px; padding:6px 18px;" onclick="return confirm('Are you sure you want to logout?')">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>
<div style="margin-top:80px;"></div>

<div class="container py-4" style="max-width:900px;">
    <h3 class="page_title mb-1" style="color:#1a1a2e;"><i class="fas fa-cogs me-2" style="color:#e94560;"></i>System Maintenance</h3>
    <p class="text-muted mb-4">Manage memberships, books, movies, and users</p>

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

    <div class="maint-section">
        <h5><i class="fas fa-id-card me-2" style="color:#0984e3;"></i>Membership Management</h5>
        <div class="d-flex flex-wrap gap-3">
            <a href="index.php?maintenance&add_membership" class="maint-btn">
                <i class="fas fa-plus-circle"></i>Add Membership
            </a>
            <a href="index.php?maintenance&update_membership" class="maint-btn-outline">
                <i class="fas fa-edit"></i>Update Membership
            </a>
        </div>
    </div>

    <div class="maint-section">
        <h5><i class="fas fa-book me-2" style="color:#6c5ce7;"></i>Books &amp; Movies</h5>
        <div class="d-flex flex-wrap gap-3">
            <a href="index.php?maintenance&add_book" class="maint-btn">
                <i class="fas fa-plus-circle"></i>Add Book/Movie
            </a>
            <a href="index.php?maintenance&update_book" class="maint-btn-outline">
                <i class="fas fa-edit"></i>Update Book/Movie
            </a>
        </div>
    </div>

    <div class="maint-section">
        <h5><i class="fas fa-user-shield me-2" style="color:#e94560;"></i>User Management</h5>
        <div class="d-flex flex-wrap gap-3">
            <a href="index.php?maintenance&user_management" class="maint-btn">
                <i class="fas fa-users-cog"></i>Add / Update User
            </a>
        </div>
    </div>
</div>

<?php } ?>
