<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Post</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
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
                        <h1 class="m-0">Edit Post</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="/posts/update/<?php echo $data['post']['id']; ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($data['post']['title']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" value="<?php echo htmlspecialchars($data['post']['slug']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="10"><?php echo htmlspecialchars($data['post']['content']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="excerpt">Excerpt</label>
                                <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?php echo htmlspecialchars($data['post']['excerpt']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="featured_image">Featured Image</label>
                                <input type="file" class="form-control-file" id="featured_image" name="featured_image">
                                <input type="hidden" name="current_featured_image" value="<?php echo $data['post']['featured_image']; ?>">
                                <?php if ($data['post']['featured_image']): ?>
                                    <img src="/<?php echo $data['post']['featured_image']; ?>" alt="Featured Image" width="200" class="mt-2">
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <?php foreach ($data['categories'] as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $data['post']['category_id']) ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tags">Tags</label>
                                <select class="form-control" id="tags" name="tags[]" multiple>
                                    <?php 
                                    $post_tags = array_map(function($tag) {
                                        return $tag['id'];
                                    }, (new Database())->getPostTags($data['post']['id']));
                                    foreach ($data['tags'] as $tag): ?>
                                        <option value="<?php echo $tag['id']; ?>" <?php echo in_array($tag['id'], $post_tags) ? 'selected' : ''; ?>><?php echo $tag['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="draft" <?php echo ($data['post']['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                    <option value="published" <?php echo ($data['post']['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Post</button>
                            <a href="/posts" class="btn btn-danger">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#">Inspira</a>.</strong> All rights reserved.
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    var slug = this.value.toLowerCase()
        .replace(/[^a-z0-9-]/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
    document.getElementById('slug').value = slug;
});
</script>
</body>
</html>