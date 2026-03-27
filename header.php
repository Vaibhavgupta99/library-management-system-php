<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>LibraryMS</title>
  <style>
    * { font-family: 'Inter', sans-serif; }
    h1,h2,h3,h4,h5,h6,.navbar-brand,.page_title { font-family: 'Poppins', sans-serif; }
    :root {
      --primary: #1a1a2e;
      --accent: #e94560;
      --secondary: #16213e;
      --light: #f5f7fa;
      --text-dark: #2d3436;
      --text-light: #f5f7fa;
    }
    body { background: var(--light); color: var(--text-dark); }
    .btn-accent {
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 10px 28px;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    .btn-accent:hover {
      background: #d63851;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(233,69,96,0.4);
    }
    .btn-outline-accent {
      background: transparent;
      color: var(--accent);
      border: 2px solid var(--accent);
      border-radius: 50px;
      padding: 8px 24px;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    .btn-outline-accent:hover {
      background: var(--accent);
      color: #fff;
      transform: translateY(-2px);
    }
    .card-custom {
      background: #fff;
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: all 0.2s ease;
    }
    .card-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    .table-wrapper {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      overflow: hidden;
    }
    .table-wrapper table { margin-bottom: 0; }
    .table-wrapper thead th {
      background: var(--primary);
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
      border: none;
      padding: 14px 16px;
    }
    .table-wrapper tbody tr { transition: background 0.15s ease; }
    .table-wrapper tbody tr:nth-child(even) { background: #f8f9fa; }
    .table-wrapper tbody tr:hover { background: #eef1f5; }
    .table-wrapper tbody td { padding: 12px 16px; vertical-align: middle; }
    .badge-available { background: #00b894; color: #fff; border-radius: 50px; padding: 5px 14px; font-weight: 500; }
    .badge-issued { background: #fdcb6e; color: #2d3436; border-radius: 50px; padding: 5px 14px; font-weight: 500; }
    .badge-active { background: #00cec9; color: #fff; border-radius: 50px; padding: 5px 14px; font-weight: 500; }
    .badge-inactive { background: #b2bec3; color: #fff; border-radius: 50px; padding: 5px 14px; font-weight: 500; }
    .badge-expired { background: #e94560; color: #fff; border-radius: 50px; padding: 5px 14px; font-weight: 500; }
    .form-control:focus, .form-select:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 0.2rem rgba(233,69,96,0.15);
    }
    .form-control, .form-select {
      border-radius: 8px;
      padding: 10px 14px;
      border: 1.5px solid #dee2e6;
    }
    .nav-link-custom {
      color: rgba(255,255,255,0.75);
      font-weight: 500;
      padding: 6px 16px;
      border-radius: 8px;
      transition: all 0.2s ease;
      text-decoration: none;
    }
    .nav-link-custom:hover, .nav-link-custom.active {
      color: #fff;
      background: rgba(233,69,96,0.15);
    }
  </style>
</head>
<body>
