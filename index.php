<?php
session_start();
try {
    $conn = mysqli_connect('localhost', 'root', '', 'library_db');
} catch (mysqli_sql_exception $e) {
    $temp_conn = mysqli_connect('localhost', 'root', '');
    mysqli_query($temp_conn, "CREATE DATABASE IF NOT EXISTS library_db");
    mysqli_close($temp_conn);
    $conn = mysqli_connect('localhost', 'root', '', 'library_db');
}

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users_tb (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    username VARCHAR(30),
    pass VARCHAR(255),
    role ENUM('admin','user') DEFAULT 'user',
    status ENUM('active','inactive') DEFAULT 'active'
)");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS members_tb (
    id INT AUTO_INCREMENT PRIMARY KEY,
    membership_id VARCHAR(20) UNIQUE,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    contact_name VARCHAR(50),
    contact_address TEXT,
    aadhar_no VARCHAR(20),
    start_date DATE,
    end_date DATE,
    membership_type ENUM('6months','1year','2years') DEFAULT '6months',
    status ENUM('active','inactive') DEFAULT 'active',
    fine_pending DECIMAL(10,2) DEFAULT 0
)");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS books_tb (
    id INT AUTO_INCREMENT PRIMARY KEY,
    serial_no VARCHAR(20) UNIQUE,
    name VARCHAR(100),
    author VARCHAR(100),
    category ENUM('Science','Economics','Fiction','Children','Personal Development'),
    type ENUM('book','movie') DEFAULT 'book',
    status ENUM('available','issued') DEFAULT 'available',
    cost DECIMAL(10,2) DEFAULT 0,
    procurement_date DATE,
    quantity INT DEFAULT 1
)");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS issues_tb (
    id INT AUTO_INCREMENT PRIMARY KEY,
    serial_no VARCHAR(20),
    book_name VARCHAR(100),
    author VARCHAR(100),
    membership_id VARCHAR(20),
    issue_date DATE,
    return_date DATE,
    actual_return_date DATE,
    remarks TEXT,
    status ENUM('active','returned') DEFAULT 'active',
    fine_calculated DECIMAL(10,2) DEFAULT 0,
    fine_paid TINYINT(1) DEFAULT 0
)");

$sql = "INSERT IGNORE INTO users_tb (name,username,pass,role,status) VALUES
        ('Admin','adm','".md5('adm')."','admin','active'),
        ('User','user','".md5('user')."','user','active')";
mysqli_query($conn, $sql);

include_once './header.php';

if(isset($_SESSION['admin'])){
    if(isset($_GET['maintenance'])) include_once './maintenance.php';
    elseif(isset($_GET['reports'])) include_once './reports.php';
    elseif(isset($_GET['transactions'])) include_once './transactions.php';
    else include_once './home.php';
} elseif(isset($_SESSION['user'])){
    if(isset($_GET['transactions'])) include_once './transactions.php';
    elseif(isset($_GET['reports'])) include_once './reports.php';
    else include_once './home.php';
} else {
    include_once './login.php';
}

include_once './footer.php';
?>
