<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>
    <?php echo TITLEOFWEBSITE; ?>
  </title>
  <link href="<?php echo DOMAIN . $webParams['MEDIA_BANNER_FOLDER']; ?>favicon.ico" rel="icon" type="image/x-icon" />
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/bootstrap-4.1.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/css/syaarar.css" rel="stylesheet" type="text/css">
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/css/main_style.css" rel="stylesheet" type="text/css">
  <meta name="google-site-verification" content="Pv_BVShNZgLck0df3YQZBa-6kuucls3BKxVfXP3YfRY" />

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-2561861-3">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-2561861-3');
</script>
</head>

<body>
  <div class="container">
    <div class="row header">
      <div class="col-lg-12" style="text-align:right">
        <?php LoadModule("position02"); ?>
      </div>
    </div>
    <?php if(isset($_GET["mod"]) && $_GET["mod"]=="test") { ?>
    <?php include("components/modules/mod_test.php"); } else { ?>
    <div class="row">
      <div class="col-lg-8 jumbo01">
        <?php LoadModule("position03"); ?>
      </div>
      <div class="col-lg-4 jumbo02">
          <?php LoadModule("position04"); ?>
      </div>
    </div>
    <div class="row">
      <nav class="navbar navbar-expand-lg col-md-2 p-0 d-block">
        <div class="collapse navbar-collapse">
          <div class="col-lg-12 left-side">
            <?php LoadModule("position05"); ?>
          </div>
        </div>
      </nav>
      <div class="col-lg-6 midle-side">
        <?php if(isset($_GET["search"]) || isset($_GET["mod"])) { ?>
        <?php LoadModule("position07"); ?>
        <?php } else { ?>
        <?php LoadModule("position06"); ?>
        <?php } ?>
      </div>
      <div class="col-lg-4 right-side">
        <?php LoadModule("position08"); ?>
      </div>
    </div>
    <?php } ?>
  </div>
    <div class="row">
      <div class="col-lg-12 footer">
        <?php LoadModule("position12"); ?>
        <?php /*?>Excel Mudah • Copyright © 2019–
        <?php echo date("Y"); ?> • All rights reserved<br/> Home | Getting Started | Formula | Tutorial | Tips & Triks | Q & A<?php */?>
      </div>
    </div>
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <?php /*?>
  <script src="applications/templates/<?php echo TEMPLATE; ?>/dist/bootstrap-4.1.1/js/bootstrap.js"></script>
  <?php */?>
</body>
</html>