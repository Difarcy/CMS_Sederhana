<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data user
$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);

// Ambil data posts
$posts = getAllPosts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e90ff;
            --secondary: #a8edea;
            --purple: #a084e8;
            --green: #4fd1c5;
            --blue: #4f8cff;
            --light: #f8fafd;
            --text: #222;
            --sidebar: #fff;
            --sidebar-border: #e6e6e6;
        }
        body {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            margin: 0;
        }
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 240px;
            background: var(--sidebar);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 0 0 0;
        }
        .sidebar-logo {
            width: 56px;
            height: 56px;
            margin-bottom: 12px;
        }
        .sidebar-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 32px;
        }
        .sidebar-menu {
            width: 100%;
            list-style: none;
            padding: 0;
        }
        .sidebar-menu li {
            width: 100%;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 32px;
            color: var(--text);
            text-decoration: none;
            font-size: 16px;
            border-left: 4px solid transparent;
            transition: background 0.2s, border-color 0.2s;
        }
        .sidebar-menu a.active, .sidebar-menu a:hover {
            background: var(--secondary);
            border-left: 4px solid var(--primary);
            color: var(--primary);
        }
        .sidebar-menu i {
            margin-right: 12px;
            font-size: 18px;
        }
        .main-content {
            flex: 1;
            background: var(--light);
            padding: 32px 40px 32px 40px;
        }
        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }
        .search-bar {
            background: #fff;
            border-radius: 24px;
            padding: 10px 24px;
            border: 1px solid #e6e6e6;
            width: 320px;
            font-size: 15px;
            outline: none;
        }
        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 28px 32px 24px 32px;
            box-shadow: 0 2px 8px 0 rgba(30,144,255,0.06);
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .stat-title {
            font-size: 15px;
            color: #888;
            margin-bottom: 8px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .stat-desc {
            font-size: 13px;
            color: #aaa;
        }
        .stat-blue { background: var(--blue); color: #fff; }
        .stat-cyan { background: #6edff6; color: #fff; }
        .stat-purple { background: var(--purple); color: #fff; }
        .stat-green { background: var(--green); color: #fff; }
        .chart-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 24px;
        }
        .chart-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px 24px 16px 24px;
            box-shadow: 0 2px 8px 0 rgba(30,144,255,0.06);
        }
        .chart-title {
            font-size: 15px;
            color: #888;
            margin-bottom: 12px;
        }
        .chart-bar {
            width: 100%;
            height: 120px;
            background: repeating-linear-gradient(90deg, #a084e8 0 8px, transparent 8px 16px);
            margin-bottom: 8px;
        }
        .chart-donut {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: conic-gradient(var(--primary) 0% 67%, #e6e6e6 67% 100%);
            margin: 0 auto 8px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--primary);
            font-size: 20px;
        }
        .chart-donut2 {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: conic-gradient(var(--purple) 0% 48%, #e6e6e6 48% 100%);
            margin: 0 auto 8px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--purple);
            font-size: 20px;
        }
        .chart-label {
            text-align: center;
            font-size: 13px;
            color: #888;
        }
        @media (max-width: 900px) {
            .dashboard-wrapper { flex-direction: column; }
            .sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--sidebar-border); flex-direction: row; justify-content: center; }
            .main-content { padding: 24px 8px; }
            .stat-grid, .chart-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="dashboard-wrapper">
    <div class="sidebar">
        <img src="https://cdn-icons-png.flaticon.com/512/2721/2721723.png" alt="Logo" class="sidebar-logo">
        <div class="sidebar-title">SmartPage</div>
        <ul class="sidebar-menu">
            <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-th-large"></i> Classic</a></li>
            <li><a href="#"><i class="fas fa-border-all"></i> Minimal</a></li>
            <li><a href="#"><i class="fas fa-copy"></i> Pages</a></li>
            <li><a href="#"><i class="fas fa-th-list"></i> Applications</a></li>
            <li><a href="#"><i class="fas fa-puzzle-piece"></i> UI Component</a></li>
            <li><a href="#"><i class="fas fa-layer-group"></i> Widgets</a></li>
            <li><a href="#"><i class="fas fa-pen-square"></i> Forms</a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i> Charts</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="dashboard-header">
            <input class="search-bar" placeholder="Search here..." />
            <div style="display:flex;align-items:center;gap:18px;">
                <span style="font-size:22px;color:#bbb;cursor:pointer;"><i class="far fa-bell"></i></span>
                <span style="font-size:22px;color:#bbb;cursor:pointer;"><i class="far fa-comment-dots"></i></span>
                <img src="https://randomuser.me/api/portraits/men/32.jpg" class="user-avatar" alt="User">
            </div>
        </div>
        <div class="stat-grid">
            <div class="stat-card stat-blue">
                <div class="stat-title">Total Income</div>
                <div class="stat-value">$ 579,000</div>
                <div class="stat-desc">Saved 25%</div>
            </div>
            <div class="stat-card stat-cyan">
                <div class="stat-title">Total Expenses</div>
                <div class="stat-value">$ 79,000</div>
                <div class="stat-desc">Saved 25%</div>
            </div>
            <div class="stat-card stat-purple">
                <div class="stat-title">Cash on Hand</div>
                <div class="stat-value">$ 92,000</div>
                <div class="stat-desc">Saved 25%</div>
            </div>
            <div class="stat-card stat-green">
                <div class="stat-title">Net Profit Margin</div>
                <div class="stat-value">$ 179,000</div>
                <div class="stat-desc">Saved 65%</div>
            </div>
        </div>
        <div class="chart-row">
            <div class="chart-card">
                <div class="chart-title">AP and AR Balance</div>
                <div class="chart-bar"></div>
                <div class="chart-label">Monthly &nbsp; | &nbsp; Last Year</div>
            </div>
            <div class="chart-card">
                <div class="chart-title">% of Income Budget</div>
                <div class="chart-donut">67%</div>
                <div class="chart-label">Budget</div>
            </div>
            <div class="chart-card">
                <div class="chart-title">% of Expenses Budget</div>
                <div class="chart-donut2">48%</div>
                <div class="chart-label">Profit</div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 