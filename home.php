<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

$total_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM books_tb WHERE type='book'"))['cnt'];
$total_movies = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM books_tb WHERE type='movie'"))['cnt'];
$total_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM members_tb WHERE status='active'"))['cnt'];
$active_issues = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM issues_tb WHERE status='active'"))['cnt'];
$overdue_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM issues_tb WHERE status='active' AND return_date < CURDATE()"))['cnt'];

if(isset($_SESSION['success'])){
    echo '<script>alert("'.$_SESSION['success'].'")</script>';
    unset($_SESSION['success']);
}
if(isset($_SESSION['err'])){
    echo '<script>alert("'.$_SESSION['err'].'")</script>';
    unset($_SESSION['err']);
}
?>
<style>
.welcome-banner {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    border-radius: 16px;
    padding: 36px 40px;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 28px;
}
.welcome-banner::after {
    content: '';
    position: absolute;
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, rgba(233,69,96,0.2) 0%, transparent 70%);
    top: -80px;
    right: -40px;
    border-radius: 50%;
}
.welcome-banner h2 { font-weight: 700; font-size: 1.8rem; margin-bottom: 6px; position: relative; z-index: 1; }
.welcome-banner p { opacity: 0.8; margin: 0; position: relative; z-index: 1; font-size: 0.95rem; }
.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
    border-left: 4px solid transparent;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}
.stat-card .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}
.stat-card .stat-number {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 1.8rem;
    line-height: 1;
    margin-bottom: 2px;
}
.stat-card .stat-label {
    color: #636e72;
    font-size: 0.85rem;
    font-weight: 500;
}
.category-section { margin-top: 12px; }
.category-section h5 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 16px;
}
.quick-action {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #fff;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    text-decoration: none;
    color: #2d3436;
    transition: all 0.2s ease;
    border-left: 4px solid #e94560;
}
.quick-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    color: #e94560;
}
.quick-action i { font-size: 1.3rem; color: #e94560; }
.quick-action span { font-weight: 500; }
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
            <?php if(isset($_SESSION['admin'])){ ?>
            <a href="index.php" class="nav-link-custom active">
                <i class="fas fa-home me-1"></i>Home
            </a>
            <a href="index.php?transactions" class="nav-link-custom">
                <i class="fas fa-exchange-alt me-1"></i>Transactions
            </a>
            <a href="index.php?reports" class="nav-link-custom">
                <i class="fas fa-chart-bar me-1"></i>Reports
            </a>
            <a href="index.php?maintenance" class="nav-link-custom">
                <i class="fas fa-cogs me-1"></i>Maintenance
            </a>
            <?php } else { ?>
            <a href="index.php" class="nav-link-custom active">
                <i class="fas fa-home me-1"></i>Home
            </a>
            <a href="index.php?transactions" class="nav-link-custom">
                <i class="fas fa-exchange-alt me-1"></i>Transactions
            </a>
            <a href="index.php?reports" class="nav-link-custom">
                <i class="fas fa-chart-bar me-1"></i>Reports
            </a>
            <?php } ?>
            <span class="text-white opacity-50 d-none d-lg-inline mx-1">|</span>
            <span class="text-white opacity-75 d-none d-lg-inline" style="font-size:0.85rem;">
                <i class="fas fa-user-circle me-1"></i>
                <?php echo isset($_SESSION['admin']) ? $_SESSION['admin'] : $_SESSION['user']; ?>
            </span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm" style="border-radius:50px; padding:6px 18px;" onclick="return confirm('Are you sure you want to logout?')">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>
<div style="margin-top:80px;"></div>

<div class="container-fluid px-4 py-3" style="max-width:1200px;">

    <div class="welcome-banner">
        <h2><i class="fas fa-hand-sparkles me-2" style="color:#e94560;"></i>Welcome back, <?php echo isset($_SESSION['admin']) ? $_SESSION['admin'] : $_SESSION['user']; ?>!</h2>
        <p><?php echo isset($_SESSION['admin']) ? 'Administrator Dashboard — Full system access' : 'User Dashboard — Browse, issue & return books'; ?></p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card" style="border-left-color:#6c5ce7;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-icon" style="background:rgba(108,92,231,0.12); color:#6c5ce7;">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
                <div class="stat-number" style="color:#6c5ce7;"><?php echo $total_books; ?></div>
                <div class="stat-label">Total Books</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="border-left-color:#00b894;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-icon" style="background:rgba(0,184,148,0.12); color:#00b894;">
                        <i class="fas fa-film"></i>
                    </div>
                </div>
                <div class="stat-number" style="color:#00b894;"><?php echo $total_movies; ?></div>
                <div class="stat-label">Total Movies</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="border-left-color:#0984e3;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-icon" style="background:rgba(9,132,227,0.12); color:#0984e3;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-number" style="color:#0984e3;"><?php echo $total_members; ?></div>
                <div class="stat-label">Active Members</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="border-left-color:<?php echo $overdue_count > 0 ? '#e94560' : '#fdcb6e'; ?>;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-icon" style="background:<?php echo $overdue_count > 0 ? 'rgba(233,69,96,0.12)' : 'rgba(253,203,110,0.15)'; ?>; color:<?php echo $overdue_count > 0 ? '#e94560' : '#f39c12'; ?>;">
                        <i class="fas fa-<?php echo $overdue_count > 0 ? 'exclamation-triangle' : 'clipboard-list'; ?>"></i>
                    </div>
                </div>
                <div class="stat-number" style="color:<?php echo $overdue_count > 0 ? '#e94560' : '#f39c12'; ?>;"><?php echo $active_issues; ?></div>
                <div class="stat-label">Active Issues <?php if($overdue_count > 0) echo '<span class="badge-expired ms-1" style="font-size:0.7rem;">'.$overdue_count.' overdue</span>'; ?></div>
            </div>
        </div>
    </div>

    <?php if(isset($_SESSION['admin'])){ ?>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <a href="index.php?transactions" class="quick-action">
                <i class="fas fa-exchange-alt"></i>
                <span>Manage Transactions</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="index.php?reports" class="quick-action">
                <i class="fas fa-chart-bar"></i>
                <span>View Reports</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="index.php?maintenance" class="quick-action">
                <i class="fas fa-cogs"></i>
                <span>System Maintenance</span>
            </a>
        </div>
    </div>
    <?php } else { ?>
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <a href="index.php?transactions" class="quick-action">
                <i class="fas fa-exchange-alt"></i>
                <span>Manage Transactions</span>
            </a>
        </div>
        <div class="col-md-6">
            <a href="index.php?reports" class="quick-action">
                <i class="fas fa-chart-bar"></i>
                <span>View Reports</span>
            </a>
        </div>
    </div>
    <?php } ?>

    <div class="category-section">
        <h5><i class="fas fa-layer-group me-2" style="color:#e94560;"></i>Book &amp; Movie Serial Code Ranges</h5>
        <div class="table-wrapper">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:25%;">Code No From</th>
                        <th style="width:25%;">Code No To</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#6c5ce7; font-weight:600;">SCB000001</code></td>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#6c5ce7; font-weight:600;">SCB999999</code></td>
                        <td><i class="fas fa-flask me-2" style="color:#6c5ce7;"></i>Science</td>
                    </tr>
                    <tr>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#0984e3; font-weight:600;">ECB000001</code></td>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#0984e3; font-weight:600;">ECB999999</code></td>
                        <td><i class="fas fa-chart-line me-2" style="color:#0984e3;"></i>Economics</td>
                    </tr>
                    <tr>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#e94560; font-weight:600;">FCB000001</code></td>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#e94560; font-weight:600;">FCB999999</code></td>
                        <td><i class="fas fa-hat-wizard me-2" style="color:#e94560;"></i>Fiction</td>
                    </tr>
                    <tr>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#00b894; font-weight:600;">CHB000001</code></td>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#00b894; font-weight:600;">CHB999999</code></td>
                        <td><i class="fas fa-child me-2" style="color:#00b894;"></i>Children</td>
                    </tr>
                    <tr>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#f39c12; font-weight:600;">PDB000001</code></td>
                        <td><code style="background:#eef1f5; padding:4px 10px; border-radius:6px; color:#f39c12; font-weight:600;">PDB999999</code></td>
                        <td><i class="fas fa-brain me-2" style="color:#f39c12;"></i>Personal Development</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-muted mt-2" style="font-size:0.8rem;"><i class="fas fa-info-circle me-1"></i>Movie serial codes follow the same pattern with <code>M</code> suffix (e.g., SCM000001 for Science Movies).</p>
    </div>

</div>
