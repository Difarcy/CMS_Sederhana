<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Inspira</title>
    <link rel="stylesheet" href="/CMS_Sederhana/assets/css/style.css">
    <link rel="icon" type="image/png" href="/CMS_Sederhana/assets/img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: var(--bg-gradient); color: #222; font-family: 'Poppins', sans-serif; }
        .dashboard-container { max-width: 800px; margin: 40px auto; background: rgba(255,255,255,0.10); border-radius: 16px; box-shadow: 0 8px 32px 0 rgba(123,47,242,0.10); padding: 32px; }
        .dashboard-title { font-size: 2.2rem; font-family: 'Lora', serif; font-weight: 700; margin-bottom: 12px; color: #222; }
        .dashboard-welcome { font-size: 1.1rem; margin-bottom: 24px; color: #222; }
        .recent-posts { margin-top: 32px; }
        .recent-posts h3 { font-size: 1.3rem; margin-bottom: 12px; color: #222; }
        .post-list { list-style: none; padding: 0; }
        .post-list li { background: rgba(255,255,255,0.15); margin-bottom: 10px; border-radius: 8px; padding: 12px 18px; color: #222; }
        .post-list li span { color: #888; font-size: 0.95em; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-title">Welcome, <?php echo htmlspecialchars($data['user']); ?>!</div>
        <div class="dashboard-welcome">This is your personal dashboard. Here you can see the latest posts and manage your account.</div>
        <div class="recent-posts">
            <h3>Recent Posts</h3>
            <ul class="post-list">
                <?php if (isset($data['recent_posts']) && is_array($data['recent_posts'])): ?>
                <?php foreach ($data['recent_posts'] as $post): ?>
                <li>
                    <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                    <span> &mdash; <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <footer style="text-align:center; background:#fff; color:#222; border-top:1px solid #eee; margin-top:32px; border-radius:0 0 16px 16px; padding:16px 0;">
        © 2025 Inspira. All rights reserved.
    </footer>
</body>
</html> 