<?php
// BASE_URL helper
$BASE_URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
$BASE_URL = rtrim(str_replace('\\', '/', $BASE_URL), '/');
// Get current page for active menu
$current_page = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(135deg, #0050f0 0%, #22c1c3 100%);">
    <!-- Brand Logo -->
    <a href="<?php echo $BASE_URL; ?>/admin" class="brand-link" style="display:block; padding-left:0;">
        <img src="<?php echo $BASE_URL; ?>/assets/img/logo.png" alt="Logo" class="brand-image img-circle" style="height:40px;width:40px;object-fit:contain;background:transparent;padding:0;display:block;margin-bottom:2px;margin-left:20px;">
        <span class="brand-text font-weight-bold" style="color:#fff; letter-spacing:2px; font-size:1.3rem; display:block; text-align:left; margin-left:20px;">Inspira</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo $BASE_URL; ?>/admin" class="nav-link <?php echo $current_page === 'admin' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $BASE_URL; ?>/posts" class="nav-link <?php echo $current_page === 'posts' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Posts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $BASE_URL; ?>/categories" class="nav-link <?php echo $current_page === 'categories' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $BASE_URL; ?>/pages" class="nav-link <?php echo $current_page === 'pages' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Pages</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $BASE_URL; ?>/media" class="nav-link <?php echo $current_page === 'media' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>Media</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $BASE_URL; ?>/comments" class="nav-link <?php echo $current_page === 'comments' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Comments</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $BASE_URL; ?>/tags" class="nav-link <?php echo $current_page === 'tags' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Tags</p>
                    </a>
                </li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?php echo $BASE_URL; ?>/users" class="nav-link <?php echo $current_page === 'users' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item mt-3">
                    <a href="<?php echo $BASE_URL; ?>/settings" class="nav-link <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>