<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inspira</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f9fb;
            color: #222;
        }
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 220px;
            background: #fff;
            border-right: 1px solid #eaeaea;
            padding: 32px 0 0 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            height: 100vh;
        }
        .sidebar .logo {
            display: flex;
            align-items: center;
            padding: 0 32px 32px 32px;
        }
        .sidebar .logo img {
            width: 38px; height: 38px; margin-right: 12px;
        }
        .sidebar .logo span {
            font-size: 22px;
            font-weight: 800;
            color: #7b2ff2;
            letter-spacing: 1px;
        }
        .sidebar nav {
            flex: 1;
        }
        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar nav ul li {
            margin-bottom: 6px;
        }
        .sidebar nav ul li a {
            display: flex;
            align-items: center;
            padding: 10px 32px;
            color: #222;
            text-decoration: none;
            font-size: 15px;
            border-left: 3px solid transparent;
            transition: background 0.15s, border-color 0.15s;
        }
        .sidebar nav ul li a.active, .sidebar nav ul li a:hover {
            background: #f2f6ff;
            border-left: 3px solid #7b2ff2;
            color: #7b2ff2;
        }
        .sidebar nav ul li a i {
            margin-right: 14px;
            font-size: 17px;
        }
        .sidebar .sidebar-footer {
            padding: 24px 32px 0 32px;
            font-size: 13px;
            color: #aaa;
        }
        .main {
            margin-left: 220px;
            min-height: 100vh;
            background: #f7f9fb;
        }
        .topbar {
            height: 64px;
            background: #fff;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 36px 0 36px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .topbar .search {
            flex: 1;
            display: flex;
            align-items: center;
        }
        .topbar .search input {
            width: 320px;
            padding: 10px 16px 10px 36px;
            border-radius: 6px;
            border: 1px solid #eaeaea;
            background: #f7f9fb;
            font-size: 15px;
            outline: none;
            margin-right: 24px;
        }
        .topbar .search i {
            position: absolute;
            margin-left: 12px;
            color: #bbb;
            font-size: 16px;
        }
        .topbar .actions {
            display: flex;
            align-items: center;
        }
        .topbar .actions i {
            font-size: 18px;
            color: #888;
            margin-right: 22px;
            cursor: pointer;
        }
        .topbar .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eaeaea;
        }
        .dashboard-content {
            padding: 36px 36px 0 36px;
        }
        .dashboard-content h2 {
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 8px 0;
        }
        .dashboard-content .welcome {
            color: #888;
            font-size: 16px;
            margin-bottom: 28px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 22px;
            margin-bottom: 32px;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px 0 rgba(123,47,242,0.04);
            padding: 22px 24px 18px 24px;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .card .card-title {
            font-size: 15px;
            color: #888;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .card .card-value {
            font-size: 22px;
            font-weight: 800;
            color: #222;
            margin-bottom: 6px;
        }
        .card .card-desc {
            font-size: 13px;
            color: #aaa;
        }
        .card .progress-bar {
            height: 6px;
            border-radius: 3px;
            background: #f2f2f2;
            margin-top: 8px;
            margin-bottom: 4px;
            overflow: hidden;
        }
        .card .progress {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, #22c1c3 0%, #7b2ff2 100%);
        }
        .card .progress.progress-yellow {
            background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
        }
        .card .progress.progress-pink {
            background: linear-gradient(90deg, #f953c6 0%, #b91d73 100%);
        }
        .card .progress.progress-green {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
        }
        .card .mini-chart {
            width: 100%;
            height: 36px;
            margin-top: 8px;
            background: #f7f9fb;
            border-radius: 6px;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
        }
        .card .mini-chart-bar {
            width: 12%;
            background: #7b2ff2;
            margin-right: 2px;
            border-radius: 2px 2px 0 0;
        }
        .card .mini-chart-bar.yellow { background: #ffd200; }
        .card .mini-chart-bar.green { background: #43e97b; }
        .card .mini-chart-bar.pink { background: #f953c6; }
        .card .mini-chart-bar.blue { background: #22c1c3; }
        .summary-card {
            grid-column: span 2;
            min-width: 0;
        }
        .summary-chart {
            width: 100%;
            height: 180px;
        }
        .upcoming-tasks {
            margin-top: 18px;
        }
        .upcoming-tasks ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .upcoming-tasks li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        .upcoming-tasks .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #7b2ff2;
            margin-right: 12px;
            margin-top: 7px;
        }
        .upcoming-tasks .task-title {
            font-size: 15px;
            font-weight: 600;
            color: #222;
        }
        .upcoming-tasks .task-desc {
            font-size: 13px;
            color: #888;
        }
        @media (max-width: 1100px) {
            .sidebar { width: 60px; }
            .main { margin-left: 60px; }
            .sidebar .logo span { display: none; }
            .sidebar nav ul li a span { display: none; }
            .sidebar nav ul li a { justify-content: center; }
            .sidebar .sidebar-footer { display: none; }
        }
        @media (max-width: 700px) {
            .main { margin-left: 0; }
            .sidebar { display: none; }
            .dashboard-content { padding: 16px; }
            .topbar { padding: 0 12px; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="images/Logo.png" alt="Inspira Logo">
            <span>Inspira</span>
        </div>
        <nav>
            <ul>
                <li><a href="#" class="active"><i class="fas fa-th-large"></i> <span>Dashboard</span></a></li>
                <li><a href="#"><i class="far fa-calendar-alt"></i> <span>Calendar</span></a></li>
                <li><a href="#"><i class="fas fa-users"></i> <span>Users</span></a></li>
                <li><a href="#"><i class="fas fa-envelope"></i> <span>Messages</span></a></li>
                <li><a href="#"><i class="fas fa-mail-bulk"></i> <span>Email</span></a></li>
                <li><a href="#"><i class="fas fa-puzzle-piece"></i> <span>Components</span></a></li>
                <li><a href="#"><i class="fas fa-plug"></i> <span>Plugins</span></a></li>
                <li><a href="#"><i class="fas fa-wpforms"></i> <span>Form</span></a></li>
                <li><a href="#"><i class="fas fa-table"></i> <span>Tables</span></a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> <span>Charts</span></a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            &copy; 2024 Inspira CMS
        </div>
    </div>
    <div class="main">
        <div class="topbar">
            <div class="search" style="position:relative;">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search components...">
            </div>
            <div class="actions" style="position:relative;">
                <i class="far fa-bell"></i>
                <i class="far fa-user-circle" id="avatarDropdown" style="font-size:32px;cursor:pointer;"></i>
                <div id="dropdownMenu" style="display:none;position:absolute;right:0;top:48px;background:#fff;border:1px solid #eaeaea;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);min-width:140px;z-index:100;">
                    <a href="logout.php" style="display:block;padding:12px 18px;color:#222;text-decoration:none;font-size:15px;">Logout <i class="fas fa-sign-out-alt" style="float:right;"></i></a>
                </div>
            </div>
        </div>
        <div class="dashboard-content">
            <h2>Dashboard</h2>
            <div class="welcome">Welcome aboard, Jacqueline Reid</div>
            <div class="cards">
                <div class="card">
                    <div class="card-title">Your status</div>
                    <div class="card-value">Pro user</div>
                    <div class="card-desc">&nbsp;</div>
                </div>
                <div class="card">
                    <div class="card-title">Your balance</div>
                    <div class="card-value">$3,500</div>
                    <div class="card-desc">Next payment <b>15 Nov</b></div>
                </div>
                <div class="card">
                    <div class="card-title">Total Sale & Referral</div>
                    <div class="card-value">39,500 <span style="font-size:13px;color:#aaa;">usd</span></div>
                    <div class="card-desc">&nbsp;</div>
                </div>
                <div class="card">
                    <div class="card-title">Your top countries</div>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <img src="https://i.ibb.co/6bQ7QpT/world-map.png" alt="Map" style="height:38px;">
                        <div style="font-size:13px;">
                            <b>USA</b> $1,250<br>
                            <b>UK</b> $650<br>
                            <b>India</b> $200
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-title">Profile complete: <span style="color:#1e90ff;font-weight:700;">65%</span></div>
                    <div class="progress-bar"><div class="progress" style="width:65%"></div></div>
                </div>
                <div class="card">
                    <div class="card-title">Payment process: <span style="color:#f7971e;font-weight:700;">25%</span></div>
                    <div class="progress-bar"><div class="progress progress-yellow" style="width:25%"></div></div>
                </div>
                <div class="card">
                    <div class="card-title">30% Conversion</div>
                    <div class="mini-chart">
                        <div class="mini-chart-bar" style="height:60%"></div>
                        <div class="mini-chart-bar yellow" style="height:40%"></div>
                        <div class="mini-chart-bar green" style="height:70%"></div>
                        <div class="mini-chart-bar pink" style="height:30%"></div>
                        <div class="mini-chart-bar blue" style="height:50%"></div>
                        <div class="mini-chart-bar" style="height:60%"></div>
                        <div class="mini-chart-bar yellow" style="height:40%"></div>
                        <div class="mini-chart-bar green" style="height:70%"></div>
                    </div>
                    <div class="card-desc">+3.5%</div>
                </div>
                <div class="card">
                    <div class="card-title">25% Search engine</div>
                    <div class="mini-chart">
                        <div class="mini-chart-bar blue" style="height:30%"></div>
                        <div class="mini-chart-bar pink" style="height:60%"></div>
                        <div class="mini-chart-bar yellow" style="height:40%"></div>
                        <div class="mini-chart-bar green" style="height:70%"></div>
                        <div class="mini-chart-bar" style="height:50%"></div>
                        <div class="mini-chart-bar blue" style="height:30%"></div>
                        <div class="mini-chart-bar pink" style="height:60%"></div>
                        <div class="mini-chart-bar yellow" style="height:40%"></div>
                    </div>
                    <div class="card-desc">-2.0%</div>
                </div>
                <div class="card">
                    <div class="card-title">45% Directly</div>
                    <div class="mini-chart">
                        <div class="mini-chart-bar green" style="height:45%"></div>
                        <div class="mini-chart-bar pink" style="height:55%"></div>
                        <div class="mini-chart-bar yellow" style="height:35%"></div>
                        <div class="mini-chart-bar blue" style="height:65%"></div>
                        <div class="mini-chart-bar" style="height:50%"></div>
                        <div class="mini-chart-bar green" style="height:45%"></div>
                        <div class="mini-chart-bar pink" style="height:55%"></div>
                        <div class="mini-chart-bar yellow" style="height:35%"></div>
                    </div>
                    <div class="card-desc">+4.5%</div>
                </div>
                <div class="card">
                    <div class="card-title">Weekly top sell</div>
                    <div class="card-value" style="color:#43e97b;">+2.50%</div>
                </div>
                <div class="card">
                    <div class="card-title">Task statistics</div>
                    <div class="card-value">52 <span style="font-size:13px;color:#aaa;">Tasks</span></div>
                    <div class="card-desc" style="color:#f953c6;font-weight:700;">+15 Added</div>
                </div>
                <div class="card">
                    <div class="card-title">This week</div>
                    <div class="card-desc">Task completion</div>
                    <div class="progress-bar"><div class="progress progress-pink" style="width:65%"></div></div>
                </div>
                <div class="card">
                    <div class="card-title">Weekly</div>
                    <div class="card-value">35%</div>
                </div>
                <div class="card">
                    <div class="card-title">Monthly</div>
                    <div class="card-value">25%</div>
                </div>
                <div class="card summary-card">
                    <div class="card-title">Summary</div>
                    <canvas class="summary-chart" id="summaryChart"></canvas>
                </div>
                <div class="card summary-card">
                    <div class="card-title">Upcoming tasks <span style="font-size:13px;color:#aaa;">Active: 9</span></div>
                    <div class="upcoming-tasks">
                        <ul>
                            <li><span class="dot"></span><div><div class="task-title">Database management</div><div class="task-desc">Managing data in all software or...</div></div></li>
                            <li><span class="dot"></span><div><div class="task-title">Open source project public release</div><div class="task-desc">New out-of-the-box dashboards and...</div></div></li>
                            <li><span class="dot"></span><div><div class="task-title">eBay Dashboard</div><div class="task-desc">This makes me believe there are good...</div></div></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Simple summary chart
        var ctx = document.getElementById('summaryChart').getContext('2d');
        var summaryChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Mentions',
                        data: [40, 55, 60, 70, 60, 80, 90, 100, 110, 95, 105, 120],
                        borderColor: '#7b2ff2',
                        backgroundColor: 'rgba(123,47,242,0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    },
                    {
                        label: 'Tasks',
                        data: [30, 40, 50, 60, 70, 80, 85, 90, 100, 110, 115, 130],
                        borderColor: '#22c1c3',
                        backgroundColor: 'rgba(34,193,195,0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
        // Avatar dropdown
        const avatar = document.getElementById('avatarDropdown');
        const dropdown = document.getElementById('dropdownMenu');
        avatar.addEventListener('click', function(e) {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', function(e) {
            if (!avatar.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>
</body>
</html>
