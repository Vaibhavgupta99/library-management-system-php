<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

$issued_books = mysqli_query($conn, "SELECT DISTINCT b.name FROM books_tb b INNER JOIN issues_tb i ON b.serial_no = i.serial_no WHERE i.status='active' ORDER BY b.name");

$all_issued = mysqli_query($conn, "SELECT i.*, b.name as book_name, b.author FROM issues_tb i INNER JOIN books_tb b ON i.serial_no = b.serial_no WHERE i.status='active'");
$issued_json = [];
while($ij = mysqli_fetch_assoc($all_issued)){
    $issued_json[] = $ij;
}
?>
<style>
.return-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 36px;
    max-width: 650px;
    margin: 0 auto;
}
.return-card h4 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
    text-align: center;
    margin-bottom: 24px;
}
.return-card h4 i { color: #00b894; }
</style>

<div class="container py-4">
    <div class="return-card">
        <h4><i class="fas fa-undo-alt me-2"></i>Return Book</h4>

        <?php if(isset($_SESSION['err'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #e94560;">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>

        <form action="conn.php" method="post">
            <input type="hidden" name="return_book" value="1">

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-book me-2" style="color:#e94560;"></i>Book Name <span class="text-danger">*</span></label>
                <select class="form-select" name="book_name" id="returnBookSelect" required>
                    <option value="">-- Select Issued Book --</option>
                    <?php
                    $shown = [];
                    foreach($issued_json as $ib){
                        if(!in_array($ib['book_name'], $shown)){
                            $shown[] = $ib['book_name'];
                            echo '<option value="'.$ib['book_name'].'">'.$ib['book_name'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-user-edit me-2" style="color:#e94560;"></i>Author</label>
                <textarea class="form-control" name="author" id="returnAuthor" rows="1" readonly style="background:#f8f9fa;"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-barcode me-2" style="color:#e94560;"></i>Serial No <span class="text-danger">*</span></label>
                <select class="form-select" name="serial_no" id="returnSerialSelect" required>
                    <option value="">-- Select Serial --</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-calendar me-2" style="color:#e94560;"></i>Issue Date</label>
                <input type="text" class="form-control" name="issue_date_display" id="returnIssueDate" readonly style="background:#f8f9fa;">
                <input type="hidden" name="issue_date" id="returnIssueDateHidden">
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-calendar-check me-2" style="color:#e94560;"></i>Return Date</label>
                <input type="text" class="form-control" id="returnDateDisplay" readonly style="background:#f8f9fa;">
                <input type="hidden" name="expected_return_date" id="returnExpectedHidden">
            </div>

            <div class="mb-3">
                <label class="form-label fw-500"><i class="fas fa-calendar-day me-2" style="color:#e94560;"></i>Actual Return Date</label>
                <input type="date" class="form-control" name="actual_return_date" id="actualReturnDate">
            </div>

            <div class="mb-4">
                <label class="form-label fw-500"><i class="fas fa-sticky-note me-2" style="color:#e94560;"></i>Remarks</label>
                <textarea class="form-control" name="remarks" rows="2" placeholder="Optional remarks..."></textarea>
            </div>

            <div class="d-flex gap-2">
                <a href="index.php?transactions" class="btn btn-outline-accent flex-fill"><i class="fas fa-times me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-accent flex-fill"><i class="fas fa-check me-2"></i>Confirm Return</button>
            </div>
        </form>
    </div>
</div>

<script>
var issuedData = <?php echo json_encode($issued_json); ?>;
var returnBookSelect = document.getElementById('returnBookSelect');
var returnAuthor = document.getElementById('returnAuthor');
var returnSerialSelect = document.getElementById('returnSerialSelect');
var returnIssueDate = document.getElementById('returnIssueDate');
var returnIssueDateHidden = document.getElementById('returnIssueDateHidden');
var returnDateDisplay = document.getElementById('returnDateDisplay');
var returnExpectedHidden = document.getElementById('returnExpectedHidden');
var actualReturnDate = document.getElementById('actualReturnDate');

var today = new Date().toISOString().split('T')[0];
actualReturnDate.value = today;

function formatDate(dateStr){
    if(!dateStr) return '';
    var d = new Date(dateStr);
    var dd = String(d.getDate()).padStart(2,'0');
    var mm = String(d.getMonth()+1).padStart(2,'0');
    var yy = d.getFullYear();
    return dd+'/'+mm+'/'+yy;
}

returnBookSelect.addEventListener('change', function(){
    var selectedBook = this.value;
    returnAuthor.value = '';
    returnSerialSelect.innerHTML = '<option value="">-- Select Serial --</option>';
    returnIssueDate.value = '';
    returnIssueDateHidden.value = '';
    returnDateDisplay.value = '';
    returnExpectedHidden.value = '';

    if(selectedBook == '') return;

    var authors = [];
    issuedData.forEach(function(item){
        if(item.book_name === selectedBook){
            if(authors.indexOf(item.author) === -1) authors.push(item.author);
            var opt = document.createElement('option');
            opt.value = item.serial_no;
            opt.textContent = item.serial_no;
            opt.dataset.issue = item.issue_date;
            opt.dataset.ret = item.return_date;
            returnSerialSelect.appendChild(opt);
        }
    });
    returnAuthor.value = authors.join(', ');
});

returnSerialSelect.addEventListener('change', function(){
    var selected = this.options[this.selectedIndex];
    if(selected.value == ''){
        returnIssueDate.value = '';
        returnIssueDateHidden.value = '';
        returnDateDisplay.value = '';
        returnExpectedHidden.value = '';
        return;
    }
    returnIssueDate.value = formatDate(selected.dataset.issue);
    returnIssueDateHidden.value = selected.dataset.issue;
    returnDateDisplay.value = formatDate(selected.dataset.ret);
    returnExpectedHidden.value = selected.dataset.ret;
});
</script>
