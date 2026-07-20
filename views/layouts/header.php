<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
      <div class="container">
        <a class="navbar-brand" href="<?php echo URL_ROOT; ?>"><?php echo SITE_NAME; ?></a>
        <?php if(isset($_SESSION['user_id'])) : ?>
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/dashboard">Dashboard</a>
              </li>
              <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] != 3) : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/book">Books</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/transaction">Circulation</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/reservation">Reservations</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/member">Members</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/author">Authors</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/publisher">Publishers</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/category">Categories</a>
              </li>
              <?php endif; ?>
              <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] != 3) : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/user">Users</a>
              </li>
              <?php endif; ?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/auth/logout">Logout</a>
              </li>
            </ul>
        <?php else : ?>
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URL_ROOT; ?>/auth/login">Login</a>
              </li>
            </ul>
        <?php endif; ?>
      </div>
    </nav>
    <div class="container">
