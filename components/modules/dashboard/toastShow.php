<?php
$mdlname = "MSIGUIDE";
$DBDOC = get_conn($mdlname);

$mysql = 'SELECT `post_title`, `post_content`, `post_name`, `post_modified_gmt` FROM `wp_posts` WHERE `post_type`="post" AND `post_status`="publish" AND `post_modified_gmt`>"' . date("Y-m-d", strtotime("-557 day")) . '" ORDER BY `post_modified_gmt` DESC LIMIT 0,1';
// $mysql = 'SELECT `post_title`, `post_content`, `post_name`, `post_modified_gmt` FROM `wp_posts` WHERE `post_type`="post" AND `post_status`="publish" ORDER BY `post_modified_gmt` DESC LIMIT 0,1';
$sb = $DBDOC->get_sql($mysql); 
if($sb[2]>0)
{
    $title = explode(":", $sb[0]['post_title']);
    ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fa-solid fa-circle-info"></i>&nbsp;
                <strong class="me-auto"><?php echo $title[0]; ?></strong>
                <!-- <small>11 mins ago</small> -->
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php
                    $contents1 = explode("/p>", $sb[0]['post_content']); 
                    $contents2 = strip_tags_content($contents1[0]); 
                    $contents = explode("<", $contents2);
                    ?>
                    <p><a href="msiguide/<?php echo $sb[0]['post_name']; ?>" target="_new" class="text-decoration-none text-muted"><b><?php echo $title[1] . '</b> '; ?></a><br/>
                    <?php echo $contents[0]; ?></p>
                    <?php
                ?>
            </div>
        </div>
    </div>

    <?php
}
?>


<script>
const toastElement = document.getElementById('liveToast');
const toast = bootstrap.Toast.getOrCreateInstance(toastElement);
toast.show();
</script>