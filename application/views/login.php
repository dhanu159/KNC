<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Sign In - KNC Business Management System </title>

  <link rel="stylesheet" href="<?= base_url('resources/tempcss/login.css') ?>">

</head>

<body>
  <div class="wrapper">
    <div class="container">
      <h1>Welcome</h1>

      <form class="form">
        <input type="text" placeholder="Username">
        <input type="password" placeholder="Password">
        <button type="submit" id="login-button">Login</button>
      </form>
    </div>

    <ul class="bg-bubbles">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
  </div>
  <!-- partial -->
  <script src="<?php echo base_url('resources/tempjs/jquery.min.js') ?>"></script>

  <script src="<?= base_url('resources/tempjs/login.js') ?>"></script>

</body>

</html>