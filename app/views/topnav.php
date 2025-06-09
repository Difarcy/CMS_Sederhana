<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background: linear-gradient(135deg, #0050f0 0%, #22c1c3 100%); box-shadow:0 2px 8px rgba(0,0,0,0.04);">
    <div class="container-fluid d-flex align-items-center">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-grip-vertical" style="color:#fff;"></i></a>
            </li>
        </ul>
        <div style="color:#fff; font-weight:700; font-size:1.1rem; letter-spacing:1px; margin-left:0;">
            Selamat Datang <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengguna'; ?>
        </div>
        <div class="flex-grow-1"></div>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" style="color:#fff;">
                    <i class="fas fa-bell" style="font-size:1.2rem;"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" style="color:#fff;">
                    <i class="fas fa-envelope" style="font-size:1.2rem;"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#fff;">
                    <i class="fas fa-user-circle" style="font-size:1.5rem;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="/profile"><i class="fas fa-user-cog"></i> Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/profile/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav> 