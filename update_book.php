<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

$book_data = null;
if(isset($_SESSION['lookup_book_serial'])){
    $ser = $_SESSION['lookup_book_serial'];
    $book_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM books_tb WHERE serial_no='".$ser."'"));
    unset($_SESSION['lookup_book_serial']);
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
.form-card h4 i { color: #6c5ce7; }
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
.lookup-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 24px;
}
</style>

<div class="container py-4">
    <div class="form-card">
        <h4><i class="fas fa-edit me-2"></i>Update Book / Movie</h4>

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
                <input type="hidden" name="lookup_book" value="1">
                <div class="flex-fill">
                    <label class="form-label fw-500 mb-1"><i class="fas fa-search me-1" style="color:#e94560;"></i>Serial Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="serial_no" placeholder="e.g. SCB000001" value="<?php echo $book_data ? $book_data['serial_no'] : ''; ?>" required>
                </div>
                <button type="submit" class="btn btn-accent" style="height:44px;"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <?php if($book_data){ ?>
        <form action="conn.php" method="post">
            <input type="hidden" name="update_book" value="1">
            <input type="hidden" name="serial_no" value="<?php echo $book_data['serial_no']; ?>">

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-tags me-2" style="color:#e94560;"></i>Type</label>
                <div class="radio-group">
                    <div class="radio-pill">
                        <input type="radio" name="type" value="book" id="updTypeBook" <?php echo $book_data['type']=='book'?'checked':''; ?>>
                        <label for="updTypeBook"><i class="fas fa-book me-1"></i>Book</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="type" value="movie" id="updTypeMovie" <?php echo $book_data['type']=='movie'?'checked':''; ?>>
                        <label for="updTypeMovie"><i class="fas fa-film me-1"></i>Movie</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-heading me-2" style="color:#e94560;"></i>Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="<?php echo $book_data['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-user-edit me-2" style="color:#e94560;"></i>Author <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="author" value="<?php echo $book_data['author']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-toggle-on me-2" style="color:#e94560;"></i>Status <span class="text-danger">*</span></label>
                <select class="form-select" name="status" required>
                    <option value="available" <?php echo $book_data['status']=='available'?'selected':''; ?>>Available</option>
                    <option value="issued" <?php echo $book_data['status']=='issued'?'selected':''; ?>>Issued</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-500"><i class="fas fa-calendar me-2" style="color:#e94560;"></i>Procurement Date</label>
                    <input type="date" class="form-control" name="procurement_date" value="<?php echo $book_data['procurement_date']; ?>">
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label fw-500"><i class="fas fa-rupee-sign me-2" style="color:#e94560;"></i>Cost (₹)</label>
                    <input type="number" class="form-control" name="cost" value="<?php echo $book_data['cost']; ?>" step="0.01" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-500"><i class="fas fa-sort-numeric-up me-2" style="color:#e94560;"></i>Qty</label>
                    <input type="number" class="form-control" name="quantity" value="<?php echo $book_data['quantity']; ?>" min="1">
                </div>
            </div>

            <div class="d-flex gap-2 mb-2">
                <a href="index.php?maintenance" class="btn btn-outline-accent flex-fill"><i class="fas fa-times me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-accent flex-fill"><i class="fas fa-check me-2"></i>Update</button>
            </div>
            <div class="text-center">
                <a href="conn.php?delete_book=<?php echo $book_data['serial_no']; ?>" class="text-danger" style="font-size:0.85rem; text-decoration:none;" onclick="return confirm('Are you sure you want to delete this item? This action cannot be undone.')">
                    <i class="fas fa-trash-alt me-1"></i>Delete this item
                </a>
            </div>
        </form>
        <?php } ?>
    </div>
</div>
