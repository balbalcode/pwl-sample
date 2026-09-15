<?php
// $page   comes from index.php (current resource, e.g. "products")
// $action comes from index.php (current controller action, e.g. "index", "create")
$navActive = $page ?? 'products';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Product App' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

    <div class="app-shell" id="appShell">

        <div class="sidebar-backdrop" onclick="document.getElementById('appShell').classList.remove('sidebar-open')"></div>

        <aside class="sidebar">
            <a href="<?= BASE_URL ?>/products" class="sidebar-brand">
                <span class="brand-mark"><i class="bi bi-box-seam"></i></span>
                Logo klean
            </a>

            <ul class="sidebar-nav">
                <li>
                    <a href="<?= BASE_URL ?>/products" class="nav-link <?= $navActive === 'products' ? 'active' : '' ?>">
                        <i class="bi bi-grid"></i> Menu klean
                    </a>
                </li>
            </ul>

            
        </aside>

        <div class="main">
            <header class="topbar">
                <div class="topbar-left">
                    
                </div>

                
            </header>

            <main class="content">
