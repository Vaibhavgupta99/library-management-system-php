<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

$books_result = mysqli_query($conn, "SELECT DISTINCT name FROM books_tb WHERE status='available' ORDER BY name");
$members_result = mysqli_query($conn, "SELECT membership_id, CONCAT(first_name,' ',last_name) as full_name FROM members_tb WHERE status='active' ORDER BY first_name");

$pre_serial = isset($_SESSION['issue_serial']) ? $_SESSION['issue_serial'] : '';
$pre_book = '';
$pre_author = '';
if($pre_serial != ''){
    $pre_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM books_tb WHERE serial_no='".$pre_serial."'"));
    if($pre_row){
        $pre_book = $pre_row['name'];
        $pre_author = $pre_row['author'];
    }
    unset($_SESSION['issue_serial']);
}

$all_books = mysqli_query($conn, "SELECT serial_no, name, author FROM books_tb WHERE status='available' ORDER BY name");
$books_json = [];
while($b = mysqli_fetch_assoc($all_books)){
    $books_json[] = $b;
}
?>
<style>
.issue-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 36px;
    max-width: 650px;
    margin: 0 auto;
}
.issue-card h4 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
    text-align: center;
    margin-bottom: 24px;
}
.issue-card h4 i { color: #0984e3; }
</style>

<div class="container py-4">
    <div class="issue-card">
        <h4><i class="fas fa-book-reader me-2"></i>Book Issue</h4>

        <?php if(isset($_SESSION['err'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #e94560;">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>

        <form action="conn.php" method="post">
            <input type="hidden" name="issue_book" value="1">

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-book me-2" style="color:#e94560;"></i>Book Name <span class="text-danger">*</span></label>
                <select class="form-select" name="book_name" id="bookSelect" required>
                    <option value="">-- Select Book --</option>
                    <?php
                    $shown = [];
                    foreach($books_json as $bk){
                        if(!in_array($bk['name'], $shown)){
                            $shown[] = $bk['name'];
                            $sel = ($bk['name'] == $pre_book) ? 'selected' : '';
                            echo '<option value="'.$bk['name'].'" '.$sel.'>'.$bk['name'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-user-edit me-2" style="color:#e94560;"></i>Author</label>
                <input type="text" class="form-control" name="author" id="authorField" value="<?php echo $pre_author; ?>" readonly style="background:#f8f9fa;">
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-barcode me-2" style="color:#e94560;"></i>Serial No <span class="text-danger">*</span></label>
                <select class="form-select" name="serial_no" id="serialSelect" required>
                    <option value="">-- Select Serial --</option>
                    <?php if($pre_serial != ''){ ?>
                    <option value="<?php echo $pre_serial; ?>" selected><?php echo $pre_serial; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-id-card me-2" style="color:#e94560;"></i>Membership ID <span class="text-danger">*</span></label>
                <select class="form-select" name="membership_id" required>
                    <option value="">-- Select Member --</option>
                    <?php
                    mysqli_data_seek($members_result, 0);
                    while($mem = mysqli_fetch_assoc($members_result)){
                        echo '<option value="'.$mem['membership_id'].'">'.$mem['membership_id'].' — '.$mem['full_name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-500"><i class="fas fa-calendar me-2" style="color:#e94560;"></i>Issue Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="issue_date" id="issueDate" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500"><i class="fas fa-calendar-check me-2" style="color:#e94560;"></i>Return Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="return_date" id="returnDate" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-500"><i class="fas fa-sticky-note me-2" style="color:#e94560;"></i>Remarks</label>
                <textarea class="form-control" name="remarks" rows="2" placeholder="Optional remarks..."></textarea>
            </div>

            <div class="d-flex gap-2">
                <a href="index.php?transactions" class="btn btn-outline-accent flex-fill"><i class="fas fa-times me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-accent flex-fill"><i class="fas fa-check me-2"></i>Issue Book</button>
            </div>
        </form>
    </div>
</div>

<script>
var booksData = <?php echo json_encode($books_json); ?>;
var bookSelect = document.getElementById('bookSelect');
var authorField = document.getElementById('authorField');
var serialSelect = document.getElementById('serialSelect');
var issueDate = document.getElementById('issueDate');
var returnDate = document.getElementById('returnDate');

var today = new Date().toISOString().split('T')[0];
issueDate.value = today;
issueDate.min = today;

var retDate = new Date();
retDate.setDate(retDate.getDate() + 15);
returnDate.value = retDate.toISOString().split('T')[0];
returnDate.max = retDate.toISOString().split('T')[0];
returnDate.min = today;

issueDate.addEventListener('change', function(){
    var d = new Date(this.value);
    d.setDate(d.getDate() + 15);
    returnDate.max = d.toISOString().split('T')[0];
    returnDate.value = d.toISOString().split('T')[0];
});

bookSelect.addEventListener('change', function(){
    var selectedBook = this.value;
    authorField.value = '';
    serialSelect.innerHTML = '<option value="">-- Select Serial --</option>';

    if(selectedBook == '') return;

    var authors = [];
    booksData.forEach(function(b){
        if(b.name === selectedBook){
            if(authors.indexOf(b.author) === -1) authors.push(b.author);
            var opt = document.createElement('option');
            opt.value = b.serial_no;
            opt.textContent = b.serial_no;
            serialSelect.appendChild(opt);
        }
    });
    authorField.value = authors.join(', ');
});

<?php if($pre_book != ''){ ?>
bookSelect.dispatchEvent(new Event('change'));
setTimeout(function(){
    serialSelect.value = '<?php echo $pre_serial; ?>';
}, 100);
<?php } ?>
</script>
