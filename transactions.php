<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
if(!isset($_SESSION['admin']) && !isset($_SESSION['user'])){
    $_SESSION['err'] = "Please login first";
    header('location:index.php');
    exit;
}

if(isset($_GET['book_available'])){
    include_once './book_available.php';
} elseif(isset($_GET['search_results'])){
    include_once './search_results.php';
} elseif(isset($_GET['book_issue'])){
    include_once './book_issue.php';
} elseif(isset($_GET['return_book'])){
    include_once './return_book.php';
} elseif(isset($_GET['pay_fine'])){
    include_once './pay_fine.php';
} else {
?>
<style>
.trans-card {
    background: #fff;
    border-radius: 14px;
    padding: 36px 28px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.25s ease;
    text-decoration: none;
    color: #2d3436;
    display: block;
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}
.trans-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #e94560, #ff6b81);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}
.trans-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    color: #2d3436;
    border-color: rgba(233,69,96,0.15);
}
.trans-card:hover::before { transform: scaleX(1); }
.trans-card .trans-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 1.6rem;
}
.trans-card h5 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    margin-bottom: 6px;
    font-size: 1.1rem;
}
.trans-card p {
    font-size: 0.85rem;
    color: #636e72;
    margin: 0;
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
            <a href="index.php?transactions" class="nav-link-custom active"><i class="fas fa-exchange-alt me-1"></i>Transactions</a>
            <a href="index.php?reports" class="nav-link-custom"><i class="fas fa-chart-bar me-1"></i>Reports</a>
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

<div class="container py-4" style="max-width:900px;">
    <h3 class="page_title mb-1" style="color:#1a1a2e;"><i class="fas fa-exchange-alt me-2" style="color:#e94560;"></i>Transactions</h3>
    <p class="text-muted mb-4">Manage book issues, returns, and fine payments</p>

    <div class="row g-4">
        <div class="col-md-6">
            <a href="index.php?transactions&book_available" class="trans-card">
                <div class="trans-icon" style="background:rgba(108,92,231,0.12); color:#6c5ce7;">
                    <i class="fas fa-search"></i>
                </div>
                <h5>Is Book Available?</h5>
                <p>Check availability of books in the library</p>
            </a>
        </div>
        <div class="col-md-6">
            <a href="index.php?transactions&book_issue" class="trans-card">
                <div class="trans-icon" style="background:rgba(9,132,227,0.12); color:#0984e3;">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h5>Issue a Book</h5>
                <p>Issue a book to a library member</p>
            </a>
        </div>
        <div class="col-md-6">
            <a href="index.php?transactions&return_book" class="trans-card">
                <div class="trans-icon" style="background:rgba(0,184,148,0.12); color:#00b894;">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <h5>Return a Book</h5>
                <p>Process book returns and calculate fines</p>
            </a>
        </div>
        <div class="col-md-6">
            <a href="index.php?transactions&pay_fine" class="trans-card">
                <div class="trans-icon" style="background:rgba(233,69,96,0.12); color:#e94560;">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <h5>Pay Fine</h5>
                <p>Clear pending fines for overdue returns</p>
            </a>
        </div>
    </div>
</div>

<?php } ?>
