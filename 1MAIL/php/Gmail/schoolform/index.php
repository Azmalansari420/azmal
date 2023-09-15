<!DOCTYPE html>
<html lang="en">
<head>
  <title>Form Name</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

  <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <!-- Brand/logo -->
  <a class="navbar-brand" href="index.php">
    <img src="logo.svg" alt="logo" style="height:40px;">
  </a>
  
  <!-- Links -->
  <!-- <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="#">Link 1</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link 2</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link 3</a>
    </li>
  </ul> -->
</nav>

  <div class="container mt-5">

    <h2>Fill The form</h2>


    <form action="send.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name">
      </div>

      <div class="form-group">
        <label for="schooname">School Name</label>
        <input type="text" class="form-control" id="schooname" placeholder="Enter School Name" name="school_name">
      </div>

      <div class="form-group">
        <label for="roll">Roll No</label>
        <input type="text" class="form-control" id="roll" placeholder="d Roll No" name="roll_no">
      </div>

      <button type="submit" name="send" class="btn btn-primary">Submit</button>

    </form>

  </div>

</body>
</html>
