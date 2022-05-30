<?php

echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
<a class="navbar-brand" href="./home.php">Home</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
  <ul class="navbar-nav mr-auto">
    <li  class="nav-item active">
    <a class="nav-link" href="/user.php">Thông tin người dùng<span class="sr-only">(current)</span></a>
  </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Chức năng
      </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="/user.php?require=demo">demo</a>
        <a class="dropdown-item" href="/transaction.php">Chuyển tiền</a>
        <a class="dropdown-item" href="/recharge.php">Nạp tiền</a>
        <a class="dropdown-item" href="/history.php">Lịch sử giao dịch</a>
        <a >
    </li>
    <li class="nav-item">
    <a class="nav-link" href="./logout.php"> Logout </a>
  </li>
  </ul>
</div>
</nav>';
