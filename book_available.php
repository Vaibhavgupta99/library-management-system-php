<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

$books_result = mysqli_query($conn, "SELECT DISTINCT name FROM books_tb ORDER BY name");
$authors_result = mysqli_query($conn, "SELECT DISTINCT author FROM books_tb ORDER BY author");
?>
<style>
.avail-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 36px;
    max-width: 600px;
    margin: 0 auto;
}
.avail-card .card-header-custom {
    text-align: center;
    margin-bottom: 28px;
}
.avail-card .card-header-custom i {
    font-size: 2.2rem;
    color: #6c5ce7;
    margin-bottom: 10px;
    display: block;
}
.avail-card .card-header-custom h4 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
    margin: 0;
}
</style>

<div class="container py-4">
    <div class="avail-card">
        <div class="card-header-custom">
            <i class="fas fa-search"></i>
            <h4>Book Availability</h4>
            <p class="text-muted mt-1" style="font-size:0.9rem;">Search by book name, author, or both</p>
        </div>

        <?php if(isset($_SESSION['err'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #e94560;">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>

        <form action="conn.php" method="post">
            <input type="hidden" name="check_availability" value="1">
            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-book me-2" style="color:#e94560;"></i>Book Name</label>
                <select class="form-select" name="book_name">
                    <option value="">-- Select Book --</option>
                    <?php while($book = mysqli_fetch_assoc($books_result)){ ?>
                    <option value="<?php echo $book['name']; ?>"><?php echo $book['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-500"><i class="fas fa-user-edit me-2" style="color:#e94560;"></i>Author Name</label>
                <select class="form-select" name="author_name">
                    <option value="">-- Select Author --</option>
                    <?php while($author = mysqli_fetch_assoc($authors_result)){ ?>
                    <option value="<?php echo $author['author']; ?>"><?php echo $author['author']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php?transactions" class="btn btn-outline-accent flex-fill"><i class="fas fa-arrow-left me-2"></i>Back</a>
                <button type="submit" class="btn btn-accent flex-fill"><i class="fas fa-search me-2"></i>Search</button>
            </div>
        </form>
    </div>
</div>
