<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body { background: var(--bg-gradient,#7b2ff2); color: #222; font-family: 'Poppins', sans-serif; }
        .content-header h1 { color: #222; font-family: 'Lora', serif; font-weight: 700; }
        .small-box { border-radius: 16px; }
        .small-box .inner h3, .small-box .inner p { color: #fff; }
        .small-box.bg-info { background: linear-gradient(135deg, #22c1c3 0%, #7b2ff2 100%); }
        .small-box.bg-success { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .small-box.bg-warning { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: #222; }
        .small-box.bg-danger { background: linear-gradient(135deg, #f953c6 0%, #b91d73 100%); }
        .card { border-radius: 16px; color: #222; }
        .card-title { color: #222; font-family: 'Lora', serif; font-weight: 600; }
        .btn { border-radius: 8px; font-family: 'Poppins', sans-serif; }
        .main-footer { background: #fff; color: #7b2ff2; border-top: 1px solid #eee; }
        table th, table td { color: #222; }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <?php include '../app/views/sidebar.php'; ?>

    <!-- Main Sidebar Container -->
    <?php include '../app/views/topnav.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Admin Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo $data['post_count']; ?></h3>
                                <p>Total Posts</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <a href="/posts" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $data['category_count']; ?></h3>
                                <p>Categories</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <a href="/categories" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $data['tag_count']; ?></h3>
                                <p>Tags</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <a href="/tags" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?php echo $data['user_count']; ?></h3>
                                <p>Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="/users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Posts</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Author</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($data['recent_posts']) && is_array($data['recent_posts'])): ?>
                                            <?php foreach ($data['recent_posts'] as $post): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                                <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                                                <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo $post['status'] === 'published' ? 'success' : 'warning'; ?>">
                                                        <?php echo ucfirst($post['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                                                <td>
                                                    <a href="/post/edit/<?php echo $post['id']; ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="/post/delete/<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Quick Actions</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <a href="/post/new" class="btn btn-primary btn-block">
                                            <i class="fas fa-plus"></i> New Post
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="/category/new" class="btn btn-success btn-block">
                                            <i class="fas fa-folder-plus"></i> New Category
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="/tag/new" class="btn btn-warning btn-block">
                                            <i class="fas fa-tag"></i> New Tag
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="/user/new" class="btn btn-info btn-block">
                                            <i class="fas fa-user-plus"></i> New User
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer" style="text-align:center; background:#fff; color:#222; border-top:1px solid #eee;">
        © 2025 Inspira. All rights reserved.
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>