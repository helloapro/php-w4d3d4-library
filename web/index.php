 <!DOCTYPE html>
 <html>
   <head>
     <link href="/css/bootstrap.min.css" rel="stylesheet" type='text/css'>
     <link href="/css/styles.css" rel="stylesheet" type='text/css'>

     <script src="/js/jquery-3.1.1.min.js"></script>
     <script src="/js/bootstrap.min.js"></script>
     <script src="/js/myscripts.js"></script>
     <title>Library</title>
   </head>
 </html>

 <?php
     $website = require_once __DIR__.'/../app/app.php';
     $website->run();
  ?>
