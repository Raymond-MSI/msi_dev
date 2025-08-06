<?php
$_SESSION[ "pmtools_UserName" ] = "Syamsul Arham";
$_SESSION[ "pmtools_UserEmail" ] = "syamsul@mastersystem.co.id";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?php echo TITLEOFWEBSITE; ?> | Administrator</title>
  <link href="<?php echo DOMAIN . $webParams['MEDIA_BANNER_FOLDER']; ?>favicon.ico" rel="icon" type="image/x-icon" />
  <!-- Bootstrap -->
  <!--<link href="../samples/gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">-->
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/bootstrap-4.1.1/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/nprogress/nprogress.css" rel="stylesheet">

  <!-- TinyMCE -->
  <?php if(isset($_GET["amod"]) && $_GET["amod"]=="article") { ?>
  <?php /*?><script src="https://cdn.tiny.cloud/1/ixcstg6hp48q0wbzs7j386q0z6i00wb453qr9y8etfrao7fr/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init( {
      selector: 'textarea',
      plugins: 'a11ychecker advcode casechange formatpainter linkchecker lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker image',
      toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
      toolbar_drawer: 'floating',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
    } );
  </script><?php */?>
  
  <!-- TinyMCE -->
<script type="text/javascript" src="<?php echo DOMAIN; ?>applications/templates/excelmudah02/dist/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "post_article",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
//		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",
		document_base_url : '../images/contents/',

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "../3parties/tiny_mce/lists/template_list.js",
		external_link_list_url : "../3parties/tiny_mce/lists/link_list.js",
		external_image_list_url : "../3parties/tiny_mce/lists/image_list.js",
		media_external_list_url : "../3parties/tiny_mce/lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "<?php if (isset($row_artikeledit['post_author'])) { echo htmlentities($row_artikeledit['post_author'], ENT_COMPAT, 'utf-8'); } else {
      echo $_SESSION[$_SERVER['SERVER_NAME'] . "UserLogin"]; } ?>",
//      echo $_SESSION['MM_Username']; } ?>",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->
  
  <?php } ?>
  <!-- Custom Theme Style -->
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/css/custom.min.css" rel="stylesheet">
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/css/main_style.css" rel="stylesheet">
  <link href="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/css/syaarar.css" rel="stylesheet">

  
</head>

<body class="nav-md">
  <?php if(!isset($_SESSION[$_SERVER['SERVER_NAME'] . "UserLogin"])) {  ?>
    <div class="d-flex justify-content-center loginx">
      <div class="col-md-2">
        <?php include("components/modules/mod_admin_login.php"); ?>
      </div>
    </div>
  <?PHP } else { ?>
      
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo DOMAIN; ?>/home" class="site_title"><i class="fa fa-paw"></i> <span><?php echo TITLEOFWEBSITE; ?></span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <div class="profile clearfix">
            <div class="profile_pic">
              <img src="media/images/profiles/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2><?php echo $_SESSION[$_SERVER['SERVER_NAME'] . "UserName"]; ?></h2>
              <h2><?php echo $_SESSION[$_SERVER['SERVER_NAME'] . "UserLevel"]; ?></h2>
            </div>
            <div class="clearfix"></div>
          </div>
          <!-- /menu profile quick info -->

          <br/>
          <!-- Menubae content -->
          <?php include("components/modules/mod_menu_admin.php"); ?>
          <!-- /Menubar content -->

          <!-- page content -->
          <div class="right_col" role="main">
            <div class="">
              <?php include("components/modules/mod_admin_" . $_GET["amod"] . ".php"); ?>
            </div>
          </div>
          <!-- /page content -->

          <!-- footer content -->
          <footer>
            <div class="pull-right">
              <?php echo TITLEOFWEBSITE; ?> - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
          </footer>
          <!-- /footer content -->
        </div><!-- left_col -->
      </div><!-- col-md-3 -->
    </div><!-- main_container -->
  </div><!-- container -->
  <?php } ?>

  <!-- jQuery -->
  <?php if(isset($_GET["amod"])) { ?>
  <script src="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/jquery/jquery.min.js"></script>
  <?php } else { ?>
  <script src="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/jquery-3.4.1/jquery-3.4.1.min.js"></script>
  <?php } ?>
   Bootstrap 
  <script src="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/bootstrap-4.1.1/js/bootstrap.bundle.min.js"></script>
  <!-- FastClick -->
  <script src="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/dist/nprogress/nprogress.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="<?php echo DOMAIN; ?>applications/templates/<?php echo TEMPLATE; ?>/js/custom.min.js"></script>

  
</body>
</html>