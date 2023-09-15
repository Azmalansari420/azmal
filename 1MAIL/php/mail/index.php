<!-- <!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Send Email</title>
  </head>
  <body>
    <form class="formd" action="send.php" method="post" style="display:block; justify-content: center; align-items:center;">
      Name <input type="text" name="name" value=""> <br><br>
      Email <input type="email" name="email" value=""> <br><br>
      Mobile <input type="text" name="mobile" value=""> <br><br>
      Subject <input type="text" name="subject" value=""> <br><br>
      Message <input type="text" name="message" value=""> <br><br>
      <button type="submit" name="submit">Send</button>
    </form>
  </body>
</html> -->


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Email Mailer in php</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container">
  <h2>PHP Mailer in Php With Email App Password</h2>
  <form action="send.php" method="post">
    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name">
    </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>
     <div class="form-group">
      <label for="mobile">Mobile:</label>
      <input type="text" class="form-control" id="mobile" placeholder="Enter mobile" name="mobile">
    </div>
    <div class="form-group">
      <label for="Subject">Subject:</label>
      <input type="text" class="form-control" id="Subject" placeholder="Enter Subject" name="subject">
    </div>
   
    <div class="form-group ">
     <label for="mes">Message:</label>
        <textarea class="form-control" rows="5" name="message"></textarea> 
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

</body>
</html>
