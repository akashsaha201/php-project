<?php
    $currentPage = '';
    if (isset($_GET['url'])) {
        $currentPage = explode('/', rtrim($_GET['url'], '/'))[0];
    }
    // Default to "home" if no URL given
    if ($currentPage === '') {
        $currentPage = 'home';
    }
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <!-- Brand / Title -->
        <a class="navbar-brand fw-bold" href="<?php echo URLROOT; ?>">E- Commerce</a>

        <!-- Toggle button for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left links -->
            <ul class="navbar-nav me-auto">
                <?php if(!isLoggedIn()) : ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'home') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>">Home</a>
                </li>
                <?php endif; ?>
                <?php if(isLoggedIn()) : ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'products') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/products"><?php echo isAdmin() ? 'Product Management' : 'Products';?></a>
                </li>
                <?php endif; ?>
                <?php if(isAdmin()) : ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'reports') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/reports">Report</a>
                </li>
                <?php endif; ?>
                <?php if(isLoggedIn() && !isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'cart') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/cart">
                            🛒 Cart (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>)
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(isLoggedIn() && !isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'orders') ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/orders">My Orders</a>
                    </li>
                <?php endif; ?>


                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'pages' && isset($_GET['url']) && strpos($_GET['url'], 'about') !== false) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/pages/about">About</a>
                </li>
            </ul>

            <!-- Right links -->
            <ul class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item d-flex align-items-center">
                        <span class="navbar-text fw-bold text-warning me-3">
                            <?php echo 'Welcome ' . $_SESSION['username']; ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/users/destroySession">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'users' && (strpos($_GET['url'] ?? '', 'authenticate') || strpos($_GET['url'] ?? '', 'showLoginForm'))) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/users/showLoginForm">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'users' && (strpos($_GET['url'] ?? '', 'create') || strpos($_GET['url'] ?? '', 'store'))) ? 'active' : ''; ?>" href="<?php echo URLROOT; ?>/users/create">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>