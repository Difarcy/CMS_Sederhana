<nav class="topnav">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-link sidebar-toggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user"></i>
                    <?php echo htmlspecialchars($user['username']); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="/profile">
                            <i class="fas fa-user-cog"></i> Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="/logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav> 