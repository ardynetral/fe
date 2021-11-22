<!DOCTYPE html>

<html lang="en">

  <?= $this->include('\Modules\Blog\Views\layout\header'); ?>


<body class="landing-page sidebar-collapse">
  <!-- Navbar -->
  <?= $this->include('\Modules\Blog\Views\layout\navbar'); ?>
  
  <!-- End Navbar -->
<!--   <div class="page-header" data-parallax="true" style="background-image: url('../assets/img/daniel-olahh.jpg');"> -->
<!--     <div class="filter"></div>
    <div class="container">
      <div class="motto text-center">
        <h1>Example page</h1>
        <h3>Start designing your landing page here.</h3>
        <br />
        <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" class="btn btn-outline-neutral btn-round"><i class="fa fa-play"></i>Watch video</a>
        <button type="button" class="btn btn-outline-neutral btn-round">Download</button>
      </div>
    </div>
  </div> -->
  <div class="site-content main">
    <div class="container">
      <div class="row">
        <div class="col-xl-9">
          <?= $this->renderSection('content') ?>
        </div>
        <div class="col-xl-3">
          <?= $this->include('\Modules\Blog\Views\layout\sidebar'); ?>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER & JS -->
  <?= $this->include('\Modules\Blog\Views\layout\footer'); ?>

</body>

</html>
