<?php
if((isset($property)) && ($property == 1)) {
  $version = '1.0';
  $author = 'Syamsul Arham';
} else {
?>
<?php function spinner() { ?>
    <script> 
        $(document).ready(function() {
            $('.spinner-border').hide();
        });
    </script>
    <div class="spinner-border spinner-border-sm" role="status">
    <span class="visually-hidden"></span>
    </div>
<?php } ?>
<?php } ?>