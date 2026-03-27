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
</style>

<div class="container py-4">
    <div class="form-card">
        <h4><i class="fas fa-plus-circle me-2"></i>Add Book / Movie</h4>

        <?php if(isset($_SESSION['err'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #e94560;">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>

        <form action="conn.php" method="post">
            <input type="hidden" name="add_book" value="1">

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-tags me-2" style="color:#e94560;"></i>Type <span class="text-danger">*</span></label>
                <div class="radio-group">
                    <div class="radio-pill">
                        <input type="radio" name="type" value="book" id="typeBook" checked>
                        <label for="typeBook"><i class="fas fa-book me-1"></i>Book</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="type" value="movie" id="typeMovie">
                        <label for="typeMovie"><i class="fas fa-film me-1"></i>Movie</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-heading me-2" style="color:#e94560;"></i>Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" required placeholder="Enter book/movie name">
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-user-edit me-2" style="color:#e94560;"></i>Author <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="author" required placeholder="Enter author/director name">
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-layer-group me-2" style="color:#e94560;"></i>Category <span class="text-danger">*</span></label>
                <select class="form-select" name="category" required>
                    <option value="">-- Select Category --</option>
                    <option value="Science">Science</option>
                    <option value="Economics">Economics</option>
                    <option value="Fiction">Fiction</option>
                    <option value="Children">Children</option>
                    <option value="Personal Development">Personal Development</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-500"><i class="fas fa-calendar me-2" style="color:#e94560;"></i>Date of Procurement <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="procurement_date" required>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label fw-500"><i class="fas fa-sort-numeric-up me-2" style="color:#e94560;"></i>Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="quantity" min="1" value="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-500"><i class="fas fa-rupee-sign me-2" style="color:#e94560;"></i>Cost (₹) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="cost" min="0" step="0.01" value="0" required>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="index.php?maintenance" class="btn btn-outline-accent flex-fill"><i class="fas fa-times me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-accent flex-fill"><i class="fas fa-check me-2"></i>Add</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('input[name="procurement_date"]').value = new Date().toISOString().split('T')[0];
</script>
