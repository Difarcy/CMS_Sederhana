<?php
// Get current page for active menu
$current_page = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link">
        <img src="/assets/img/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Inspira</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/admin" class="nav-link <?php echo $current_page === 'admin' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/posts" class="nav-link <?php echo $current_page === 'posts' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Posts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/categories" class="nav-link <?php echo $current_page === 'categories' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/tags" class="nav-link <?php echo $current_page === 'tags' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Tags</p>
                    </a>
                </li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a href="/users" class="nav-link <?php echo $current_page === 'users' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>