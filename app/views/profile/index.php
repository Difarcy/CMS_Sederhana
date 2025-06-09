<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profil Pengguna</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card" style="max-width:400px;">
                <div class="card-body">
                    <h5 class="card-title">Username</h5>
                    <p class="card-text"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></p>
                    <a href="/profile/logout" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </section>
</div> 