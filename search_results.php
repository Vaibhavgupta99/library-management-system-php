<?php
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

$book_name = isset($_SESSION['search_book']) ? $_SESSION['search_book'] : '';
$author_name = isset($_SESSION['search_author']) ? $_SESSION['search_author'] : '';

$where = [];
if($book_name != '') $where[] = "name LIKE '%".$book_name."%'";
if($author_name != '') $where[] = "author LIKE '%".$author_name."%'";

$sql = "SELECT * FROM books_tb";
if(count($where) > 0) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY name";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
?>
<style>
.results-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 32px;
    max-width: 1000px;
    margin: 0 auto;
}
.results-card h4 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    color: #1a1a2e;
}
.result-count {
    background: rgba(233,69,96,0.1);
    color: #e94560;
    padding: 6px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-block;
}
</style>

<div class="container py-4">
    <div class="results-card">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <h4 class="mb-0"><i class="fas fa-list-ul me-2" style="color:#e94560;"></i>Search Results</h4>
            <span class="result-count"><?php echo $count; ?> record(s) found</span>
        </div>

        <?php if(isset($_SESSION['err'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; border-left:4px solid #e94560;">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php } ?>

        <?php if($count > 0){ ?>
        <form action="conn.php" method="post">
            <input type="hidden" name="proceed_issue" value="1">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Book Name</th>
                            <th>Author Name</th>
                            <th>Serial Number</th>
                            <th>Available</th>
                            <th>Select to Issue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)){ ?>
                        <tr>
                            <td style="font-weight:500;"><?php echo $row['name']; ?></td>
                            <td><?php echo $row['author']; ?></td>
                            <td><code style="background:#eef1f5; padding:3px 10px; border-radius:6px; font-weight:600; color:#6c5ce7;"><?php echo $row['serial_no']; ?></code></td>
                            <td>
                                <?php if($row['status'] == 'available'){ ?>
                                <span class="badge-available">Yes</span>
                                <?php } else { ?>
                                <span class="badge-expired">No</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($row['status'] == 'available'){ ?>
                                <input type="radio" name="selected_serial" value="<?php echo $row['serial_no']; ?>" class="form-check-input book-radio" style="width:20px; height:20px; cursor:pointer;">
                                <?php } else { ?>
                                <span class="text-muted">—</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 mt-3 flex-wrap">
                <a href="index.php?transactions&book_available" class="btn btn-outline-accent"><i class="fas fa-search me-2"></i>Search Again</a>
                <a href="index.php?transactions" class="btn btn-outline-accent"><i class="fas fa-times me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-accent ms-auto" id="proceedBtn" disabled><i class="fas fa-arrow-right me-2"></i>Proceed to Issue</button>
            </div>
        </form>
        <?php } else { ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox" style="font-size:3rem; color:#b2bec3; margin-bottom:16px;"></i>
            <p class="text-muted">No books found matching your search criteria.</p>
            <a href="index.php?transactions&book_available" class="btn btn-accent mt-2"><i class="fas fa-search me-2"></i>Search Again</a>
        </div>
        <?php } ?>
    </div>
</div>

<script>
var radios = document.querySelectorAll('.book-radio');
var proceedBtn = document.getElementById('proceedBtn');
if(radios.length > 0 && proceedBtn){
    radios.forEach(function(radio){
        radio.addEventListener('change', function(){
            proceedBtn.disabled = false;
            proceedBtn.style.opacity = '1';
        });
    });
}
</script>
