<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'library_db');

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $pass = md5($_POST['pass']);
    $sql = "SELECT * FROM users_tb WHERE username='".$username."' AND pass='".$pass."' AND status='active'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if($row){
        if($row['role'] == 'admin'){
            $_SESSION['admin'] = $row['name'];
            $_SESSION['uid'] = $row['id'];
        } else {
            $_SESSION['user'] = $row['name'];
            $_SESSION['uid'] = $row['id'];
        }
    } else {
        $_SESSION['err'] = "Invalid credentials or account inactive";
    }
    header('location:index.php');
    exit;
}

elseif(isset($_POST['check_availability'])){
    $book_name = isset($_POST['book_name']) ? $_POST['book_name'] : '';
    $author_name = isset($_POST['author_name']) ? $_POST['author_name'] : '';
    if($book_name == '' && $author_name == ''){
        $_SESSION['err'] = "Please select a book name or author to search.";
        header('location:index.php?transactions&book_available');
        exit;
    }
    $_SESSION['search_book'] = $book_name;
    $_SESSION['search_author'] = $author_name;
    header('location:index.php?transactions&search_results');
    exit;
}

elseif(isset($_POST['proceed_issue'])){
    $serial = isset($_POST['selected_serial']) ? $_POST['selected_serial'] : '';
    if($serial == ''){
        $_SESSION['err'] = "Please select a book to issue.";
        header('location:index.php?transactions&search_results');
        exit;
    }
    $_SESSION['issue_serial'] = $serial;
    header('location:index.php?transactions&book_issue');
    exit;
}

elseif(isset($_POST['issue_book'])){
    $book_name = $_POST['book_name'];
    $serial_no = $_POST['serial_no'];
    $membership_id = $_POST['membership_id'];
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
    $author = isset($_POST['author']) ? $_POST['author'] : '';

    if($book_name == '' || $serial_no == '' || $membership_id == ''){
        $_SESSION['err'] = "Book Name, Serial No, and Membership ID are required.";
        header('location:index.php?transactions&book_issue');
        exit;
    }

    $today = date('Y-m-d');
    if($issue_date < $today){
        $_SESSION['err'] = "Issue date cannot be in the past.";
        header('location:index.php?transactions&book_issue');
        exit;
    }

    $max_return = date('Y-m-d', strtotime($issue_date . ' +15 days'));
    if($return_date > $max_return){
        $_SESSION['err'] = "Return date cannot exceed 15 days from issue date.";
        header('location:index.php?transactions&book_issue');
        exit;
    }

    $mem_check = mysqli_query($conn, "SELECT * FROM members_tb WHERE membership_id='".$membership_id."' AND status='active'");
    if(mysqli_num_rows($mem_check) == 0){
        $_SESSION['err'] = "Invalid or inactive membership.";
        header('location:index.php?transactions&book_issue');
        exit;
    }

    $book_check = mysqli_query($conn, "SELECT * FROM books_tb WHERE serial_no='".$serial_no."' AND status='available'");
    if(mysqli_num_rows($book_check) == 0){
        $_SESSION['err'] = "Book is not available for issue.";
        header('location:index.php?transactions&book_issue');
        exit;
    }

    $book_row = mysqli_fetch_assoc($book_check);

    $sql = "INSERT INTO issues_tb (serial_no, book_name, author, membership_id, issue_date, return_date, remarks, status)
            VALUES ('".$serial_no."', '".$book_row['name']."', '".$book_row['author']."', '".$membership_id."', '".$issue_date."', '".$return_date."', '".$remarks."', 'active')";
    mysqli_query($conn, $sql);

    mysqli_query($conn, "UPDATE books_tb SET status='issued' WHERE serial_no='".$serial_no."'");

    unset($_SESSION['search_book']);
    unset($_SESSION['search_author']);
    unset($_SESSION['issue_serial']);
    $_SESSION['success'] = "Book issued successfully!";
    header('location:confirmation.php');
    exit;
}

elseif(isset($_POST['return_book'])){
    $book_name = $_POST['book_name'];
    $serial_no = $_POST['serial_no'];
    $actual_return_date = $_POST['actual_return_date'];
    $expected_return_date = $_POST['expected_return_date'];
    $issue_date = $_POST['issue_date'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    if($book_name == '' || $serial_no == ''){
        $_SESSION['err'] = "Book Name and Serial No are required.";
        header('location:index.php?transactions&return_book');
        exit;
    }

    if($actual_return_date == ''){
        $actual_return_date = date('Y-m-d');
    }

    $issue_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM issues_tb WHERE serial_no='".$serial_no."' AND status='active' LIMIT 1"));
    if(!$issue_row){
        $_SESSION['err'] = "No active issue found for this serial number.";
        header('location:index.php?transactions&return_book');
        exit;
    }

    $days_overdue = (strtotime($actual_return_date) - strtotime($issue_row['return_date'])) / (60*60*24);
    $fine = ($days_overdue > 0) ? $days_overdue * 5 : 0;

    if($fine > 0){
        $_SESSION['fine_book'] = $issue_row['book_name'];
        $_SESSION['fine_author'] = $issue_row['author'];
        $_SESSION['fine_serial'] = $serial_no;
        $_SESSION['fine_issue_date'] = $issue_row['issue_date'];
        $_SESSION['fine_return_date'] = $issue_row['return_date'];
        $_SESSION['fine_actual_return'] = $actual_return_date;
        $_SESSION['fine_amount'] = $fine;
        $_SESSION['fine_membership'] = $issue_row['membership_id'];
        $_SESSION['fine_remarks'] = $remarks;
        $_SESSION['fine_issue_id'] = $issue_row['id'];
        header('location:index.php?transactions&pay_fine');
        exit;
    }

    mysqli_query($conn, "UPDATE issues_tb SET status='returned', actual_return_date='".$actual_return_date."', remarks='".$remarks."', fine_calculated=0, fine_paid=1 WHERE id=".$issue_row['id']);
    mysqli_query($conn, "UPDATE books_tb SET status='available' WHERE serial_no='".$serial_no."'");

    $_SESSION['success'] = "Book returned successfully! No fine applicable.";
    header('location:confirmation.php');
    exit;
}

elseif(isset($_POST['pay_fine'])){
    $issue_id = $_POST['issue_id'];
    $serial_no = $_POST['serial_no'];
    $membership_id = $_POST['membership_id'];
    $fine_amount = $_POST['fine_amount'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
    $fine_paid_check = isset($_POST['fine_paid_check']) ? 1 : 0;

    if($fine_amount > 0 && $fine_paid_check == 0){
        $_SESSION['err'] = "Please mark fine as paid before confirming.";
        header('location:index.php?transactions&pay_fine');
        exit;
    }

    $actual_return = isset($_SESSION['fine_actual_return']) ? $_SESSION['fine_actual_return'] : date('Y-m-d');

    mysqli_query($conn, "UPDATE issues_tb SET status='returned', actual_return_date='".$actual_return."', remarks='".$remarks."', fine_calculated=".$fine_amount.", fine_paid=1 WHERE id=".$issue_id);
    mysqli_query($conn, "UPDATE books_tb SET status='available' WHERE serial_no='".$serial_no."'");
    mysqli_query($conn, "UPDATE members_tb SET fine_pending = fine_pending + ".$fine_amount." WHERE membership_id='".$membership_id."'");

    unset($_SESSION['fine_book']);
    unset($_SESSION['fine_author']);
    unset($_SESSION['fine_serial']);
    unset($_SESSION['fine_issue_date']);
    unset($_SESSION['fine_return_date']);
    unset($_SESSION['fine_actual_return']);
    unset($_SESSION['fine_amount']);
    unset($_SESSION['fine_membership']);
    unset($_SESSION['fine_remarks']);
    unset($_SESSION['fine_issue_id']);

    $_SESSION['success'] = "Book returned and fine of ₹".number_format($fine_amount, 2)." paid successfully!";
    header('location:confirmation.php');
    exit;
}

elseif(isset($_POST['add_membership'])){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_name = $_POST['contact_name'];
    $contact_address = $_POST['contact_address'];
    $aadhar_no = $_POST['aadhar_no'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $membership_type = $_POST['membership_type'];

    if($first_name == '' || $last_name == '' || $contact_name == '' || $contact_address == '' || $aadhar_no == '' || $start_date == '' || $end_date == ''){
        $_SESSION['err'] = "All fields are mandatory.";
        header('location:index.php?maintenance&add_membership');
        exit;
    }

    $cnt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM members_tb"))['cnt'];
    $membership_id = 'MEM' . str_pad($cnt + 1, 6, '0', STR_PAD_LEFT);

    $sql = "INSERT INTO members_tb (membership_id, first_name, last_name, contact_name, contact_address, aadhar_no, start_date, end_date, membership_type, status)
            VALUES ('".$membership_id."', '".$first_name."', '".$last_name."', '".$contact_name."', '".$contact_address."', '".$aadhar_no."', '".$start_date."', '".$end_date."', '".$membership_type."', 'active')";
    mysqli_query($conn, $sql);

    $_SESSION['success'] = "Membership ".$membership_id." added successfully!";
    header('location:confirmation.php');
    exit;
}

elseif(isset($_POST['lookup_membership'])){
    $mid = $_POST['membership_id'];
    if($mid == ''){
        $_SESSION['err'] = "Please enter a membership number.";
        header('location:index.php?maintenance&update_membership');
        exit;
    }
    $check = mysqli_query($conn, "SELECT * FROM members_tb WHERE membership_id='".$mid."'");
    if(mysqli_num_rows($check) == 0){
        $_SESSION['err'] = "Membership '".$mid."' not found in the system.";
        header('location:index.php?maintenance&update_membership');
        exit;
    }
    $_SESSION['lookup_membership'] = $mid;
    header('location:index.php?maintenance&update_membership&looked=1');
    exit;
}

elseif(isset($_POST['update_membership'])){
    $membership_id = $_POST['membership_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $action_type = isset($_POST['action_type']) ? $_POST['action_type'] : '';

    if($membership_id == ''){
        $_SESSION['err'] = "Membership number is required.";
        header('location:index.php?maintenance&update_membership');
        exit;
    }

    if($action_type == 'remove'){
        mysqli_query($conn, "UPDATE members_tb SET status='inactive' WHERE membership_id='".$membership_id."'");
        $_SESSION['success'] = "Membership ".$membership_id." has been deactivated.";
        header('location:index.php?maintenance&update_membership');
        exit;
    }

    if($action_type == '6months' || $action_type == '1year' || $action_type == '2years'){
        $current = mysqli_fetch_assoc(mysqli_query($conn, "SELECT end_date FROM members_tb WHERE membership_id='".$membership_id."'"));
        $base_date = $current['end_date'];
        if($action_type == '6months') $new_end = date('Y-m-d', strtotime($base_date . ' +6 months'));
        elseif($action_type == '1year') $new_end = date('Y-m-d', strtotime($base_date . ' +1 year'));
        else $new_end = date('Y-m-d', strtotime($base_date . ' +2 years'));

        mysqli_query($conn, "UPDATE members_tb SET start_date='".$start_date."', end_date='".$new_end."', membership_type='".$action_type."', status='active' WHERE membership_id='".$membership_id."'");
        $_SESSION['success'] = "Membership ".$membership_id." extended successfully. New end date: ".date('d/m/Y', strtotime($new_end));
    } else {
        mysqli_query($conn, "UPDATE members_tb SET start_date='".$start_date."', end_date='".$end_date."' WHERE membership_id='".$membership_id."'");
        $_SESSION['success'] = "Membership ".$membership_id." updated successfully.";
    }
    header('location:index.php?maintenance&update_membership');
    exit;
}

elseif(isset($_POST['add_book'])){
    $type = $_POST['type'];
    $name = $_POST['name'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $procurement_date = $_POST['procurement_date'];
    $quantity = intval($_POST['quantity']);
    $cost = floatval($_POST['cost']);

    if($name == '' || $author == '' || $category == '' || $procurement_date == ''){
        $_SESSION['err'] = "All fields are mandatory.";
        header('location:index.php?maintenance&add_book');
        exit;
    }

    $cat_map = ['Science'=>'SC','Economics'=>'EC','Fiction'=>'FC','Children'=>'CH','Personal Development'=>'PD'];
    $prefix = $cat_map[$category] . strtoupper($type[0]);

    for($q = 0; $q < $quantity; $q++){
        $cnt_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM books_tb WHERE serial_no LIKE '".$prefix."%'"));
        $next = $cnt_result['cnt'] + 1;
        $serial_no = $prefix . str_pad($next, 6, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO books_tb (serial_no, name, author, category, type, status, cost, procurement_date, quantity)
                VALUES ('".$serial_no."', '".$name."', '".$author."', '".$category."', '".$type."', 'available', ".$cost.", '".$procurement_date."', 1)";
        mysqli_query($conn, $sql);
    }

    $_SESSION['success'] = $quantity." item(s) added successfully! Serial: ".$serial_no;
    header('location:confirmation.php');
    exit;
}

elseif(isset($_POST['lookup_book'])){
    $serial = $_POST['serial_no'];
    if($serial == ''){
        $_SESSION['err'] = "Please enter a serial number.";
        header('location:index.php?maintenance&update_book');
        exit;
    }
    $check = mysqli_query($conn, "SELECT * FROM books_tb WHERE serial_no='".$serial."'");
    if(mysqli_num_rows($check) == 0){
        $_SESSION['err'] = "Serial number '".$serial."' not found.";
        header('location:index.php?maintenance&update_book');
        exit;
    }
    $_SESSION['lookup_book_serial'] = $serial;
    header('location:index.php?maintenance&update_book');
    exit;
}

elseif(isset($_POST['update_book'])){
    $serial_no = $_POST['serial_no'];
    $type = $_POST['type'];
    $name = $_POST['name'];
    $author = $_POST['author'];
    $status = $_POST['status'];
    $procurement_date = $_POST['procurement_date'];
    $cost = floatval($_POST['cost']);
    $quantity = intval($_POST['quantity']);

    if($name == '' || $author == ''){
        $_SESSION['err'] = "Name and Author are required.";
        $_SESSION['lookup_book_serial'] = $serial_no;
        header('location:index.php?maintenance&update_book');
        exit;
    }

    $sql = "UPDATE books_tb SET type='".$type."', name='".$name."', author='".$author."', status='".$status."', procurement_date='".$procurement_date."', cost=".$cost.", quantity=".$quantity." WHERE serial_no='".$serial_no."'";
    mysqli_query($conn, $sql);

    $_SESSION['success'] = "Item ".$serial_no." updated successfully.";
    $_SESSION['lookup_book_serial'] = $serial_no;
    header('location:index.php?maintenance&update_book');
    exit;
}

elseif(isset($_GET['delete_book'])){
    $serial = $_GET['delete_book'];
    $check = mysqli_query($conn, "SELECT * FROM books_tb WHERE serial_no='".$serial."'");
    if(mysqli_num_rows($check) > 0){
        $book = mysqli_fetch_assoc($check);
        if($book['status'] == 'issued'){
            $_SESSION['err'] = "Cannot delete an issued item. Return it first.";
            $_SESSION['lookup_book_serial'] = $serial;
            header('location:index.php?maintenance&update_book');
            exit;
        }
        mysqli_query($conn, "DELETE FROM books_tb WHERE serial_no='".$serial."'");
        $_SESSION['success'] = "Item ".$serial." deleted successfully.";
    } else {
        $_SESSION['err'] = "Item not found.";
    }
    header('location:index.php?maintenance&update_book');
    exit;
}

elseif(isset($_POST['manage_user'])){
    if(isset($_POST['lookup_user_btn'])){
        $lookup = $_POST['lookup_username'];
        if($lookup == ''){
            $_SESSION['err'] = "Please enter a username to look up.";
            header('location:index.php?maintenance&user_management');
            exit;
        }
        $check = mysqli_query($conn, "SELECT * FROM users_tb WHERE username='".$lookup."'");
        if(mysqli_num_rows($check) == 0){
            $_SESSION['err'] = "Username '".$lookup."' not found.";
            header('location:index.php?maintenance&user_management');
            exit;
        }
        $_SESSION['lookup_user'] = $lookup;
        header('location:index.php?maintenance&user_management');
        exit;
    }

    $user_type = $_POST['user_type'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $status_active = isset($_POST['status_active']) ? 'active' : 'inactive';
    $is_admin = isset($_POST['is_admin']) ? 'admin' : 'user';

    if($name == ''){
        $_SESSION['err'] = "Name is mandatory.";
        header('location:index.php?maintenance&user_management');
        exit;
    }

    if($user_type == 'new'){
        if($username == '' || $password == ''){
            $_SESSION['err'] = "Username and password are required for new users.";
            header('location:index.php?maintenance&user_management');
            exit;
        }
        $exists = mysqli_query($conn, "SELECT * FROM users_tb WHERE username='".$username."'");
        if(mysqli_num_rows($exists) > 0){
            $_SESSION['err'] = "Username '".$username."' already exists.";
            header('location:index.php?maintenance&user_management');
            exit;
        }
        $sql = "INSERT INTO users_tb (name, username, pass, role, status) VALUES ('".$name."', '".$username."', '".md5($password)."', '".$is_admin."', '".$status_active."')";
        mysqli_query($conn, $sql);
        $_SESSION['success'] = "User '".$username."' created successfully.";
    } else {
        if($password != ''){
            $sql = "UPDATE users_tb SET name='".$name."', pass='".md5($password)."', role='".$is_admin."', status='".$status_active."' WHERE username='".$username."'";
        } else {
            $sql = "UPDATE users_tb SET name='".$name."', role='".$is_admin."', status='".$status_active."' WHERE username='".$username."'";
        }
        mysqli_query($conn, $sql);
        $_SESSION['success'] = "User '".$username."' updated successfully.";
    }
    header('location:index.php?maintenance&user_management');
    exit;
}

else {
    header('location:index.php');
    exit;
}
?>
