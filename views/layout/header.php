<?php global $page; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Aplikasi klean' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

    <div class="app-shell" id="appShell">

        <div class="sidebar">
            <a href="<?= BASE_URL ?>/" class="sidebar-brand">
                <span class="brand-mark"><i class="bi bi-box-seam"></i></span>
                Logo klean
            </a>

            <ul class="sidebar-nav">
            </ul>

        </div>

        <div class="main">
            <header class="topbar"></header>
            <div class="content">
