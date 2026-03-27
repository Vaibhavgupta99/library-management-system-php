<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
if(!isset($_SESSION['admin']) && !isset($_SESSION['user'])){
    $_SESSION['err'] = "Please login first";
    header('location:index.php');
    exit;
}

if(isset($_GET['master_books'])){
    include_once './master_books.php';
} elseif(isset($_GET['master_movies'])){
    include_once './master_movies.php';
} elseif(isset($_GET['master_memberships'])){
    include_once './master_memberships.php';
} elseif(isset($_GET['active_issues'])){
    include_once './active_issues.php';
} elseif(isset($_GET['overdue_returns'])){
    include_once './overdue_returns.php';
} elseif(isset($_GET['issue_requests'])){
    include_once './issue_requests.php';
} else {
?>
<style>
.report-card {
    background: #fff;
    border-radius: 14px;
    padding: 24px 28px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.25s ease;
    text-decoration: none;
    color: #2d3436;
    display: flex;
    align-items: center;
    gap: 16px;
    border-left: 4px solid transparent;
    position: relative;
    overflow: hidden;
}
.report-card::after {
    content: '\f054';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    right: 20px;
    color: #b2bec3;
    transition: all 0.2s ease;
    font-size: 0.85rem;
}
.report-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    color: #2d3436;
}
.report-card:hover::after { color: #e94560; right: 16px; }
.report-card .report-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.report-card .report-info h6 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    margin: 0 0 2px;
    font-size: 0.95rem;
}
.report-card .report-info p {
    margin: 0;
    font-size: 0.8rem;
    color: #636e72;
}
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
            <a href="index.php?reports" class="nav-link-custom active"><i class="fas fa-chart-bar me-1"></i>Reports</a>
            <?php if(isset($_SESSION['admin'])){ ?>
            <a href="index.php?maintenance" class="nav-link-custom"><i class="fas fa-cogs me-1"></i>Maintenance</a>
            <?php } ?>
            <span class="text-white opacity-50 d-none d-lg-inline mx-1">|</span>
            <span class="text-white opacity-75 d-none d-lg-inline" style="font-size:0.85rem;">
                <i class="fas fa-user-circle me-1"></i><?php echo isset($_SESSION['admin']) ? $_SESSION['admin'] : $_SESSION['user']; ?>
            </span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm" style="border-radius:50px; padding:6px 18px;" onclick="return confirm('Are you sure you want to logout?')">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>
<div style="margin-top:80px;"></div>

<div class="container py-4" style="max-width:800px;">
    <h3 class="page_title mb-1" style="color:#1a1a2e;"><i class="fas fa-chart-bar me-2" style="color:#e94560;"></i>Reports</h3>
    <p class="text-muted mb-4">View and analyze library data</p>

    <div class="d-flex flex-column gap-3">
        <a href="index.php?reports&master_books" class="report-card" style="border-left-color:#6c5ce7;">
            <div class="report-icon" style="background:rgba(108,92,231,0.12); color:#6c5ce7;">
                <i class="fas fa-book"></i>
            </div>
            <div class="report-info">
                <h6>Master List of Books</h6>
                <p>Complete inventory of all books in the library</p>
            </div>
        </a>
        <a href="index.php?reports&master_movies" class="report-card" style="border-left-color:#00b894;">
            <div class="report-icon" style="background:rgba(0,184,148,0.12); color:#00b894;">
                <i class="fas fa-film"></i>
            </div>
            <div class="report-info">
                <h6>Master List of Movies</h6>
                <p>Complete inventory of all movies in the library</p>
            </div>
        </a>
        <a href="index.php?reports&master_memberships" class="report-card" style="border-left-color:#0984e3;">
            <div class="report-icon" style="background:rgba(9,132,227,0.12); color:#0984e3;">
                <i class="fas fa-users"></i>
            </div>
            <div class="report-info">
                <h6>Master List of Memberships</h6>
                <p>All registered members and their status</p>
            </div>
        </a>
        <a href="index.php?reports&active_issues" class="report-card" style="border-left-color:#fdcb6e;">
            <div class="report-icon" style="background:rgba(253,203,110,0.15); color:#f39c12;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="report-info">
                <h6>Active Issues</h6>
                <p>Currently issued books and movies</p>
            </div>
        </a>
        <a href="index.php?reports&overdue_returns" class="report-card" style="border-left-color:#e94560;">
            <div class="report-icon" style="background:rgba(233,69,96,0.12); color:#e94560;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="report-info">
                <h6>Overdue Returns</h6>
                <p>Books past their return date with fine details</p>
            </div>
        </a>
        <a href="index.php?reports&issue_requests" class="report-card" style="border-left-color:#00cec9;">
            <div class="report-icon" style="background:rgba(0,206,201,0.12); color:#00cec9;">
                <i class="fas fa-history"></i>
            </div>
            <div class="report-info">
                <h6>Issue Requests</h6>
                <p>History of all issue and return transactions</p>
            </div>
        </a>
    </div>
</div>

<?php } ?>
