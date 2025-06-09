<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/CMS_Sederhana/assets/css/style.css">
    <link rel="icon" type="image/png" href="/CMS_Sederhana/assets/img/logo.png">
    <style>
        body { background: var(--bg-gradient,#7b2ff2); color: #222; font-family: 'Poppins', sans-serif; }
        .content-header h1 { color: #222; font-family: 'Lora', serif; font-weight: 700; }
        .small-box { border-radius: 16px; }
        .small-box .inner h3, .small-box .inner p { color: #fff; }
        .small-box.bg-info { background: linear-gradient(135deg, #22c1c3 0%, #7b2ff2 100%); }
        .card { border-radius: 16px; color: #222; }
        .card-title { color: #222; font-family: 'Lora', serif; font-weight: 600; }
        .btn { border-radius: 8px; font-family: 'Poppins', sans-serif; }
        table th, table td { color: #222; }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include '../app/views/sidebar.php'; ?>
    <?php include '../app/views/topnav.php'; ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">User Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <!-- Statistik Post User -->
                <div class="row">
                    <div class="col-lg-4 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo isset($data['recent_posts']) ? count($data['recent_posts']) : 0; ?></h3>
                                <p>My Posts</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <a href="/posts" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Recent Posts User -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent My Posts</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($data['recent_posts']) && is_array($data['recent_posts'])): ?>
                                            <?php foreach ($data['recent_posts'] as $post): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
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
                <!-- Quick Actions User -->
                <div class="row">
                    <div class="col-md-3">
                        <a href="/posts/create" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> New Post
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html> 