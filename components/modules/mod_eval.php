<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>

<div class="row">
    <div class="col-lg-6">
        <form method="post" action="index.php?mod=eval">
            <div class="row mb-3">
                <div class="col-sm-11">
                    <input type="text" class="form-control form-control-sm" name="foldername" value="<?php 
                    if(isset($_POST['submit']) || isset($_POST['readfile'])) { 
                        echo $_POST['foldername']; 
                    } else { 
                        echo 'components/modules/'; 
                        } ?>">
                </div>
                <div class="col-sm-1">
                    <input type="submit" name="readfile" value="Read">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <textarea class="form-control form-control-sm" id="code" name="code" rows="30"><?php
                        if(isset($_POST['submit'])) {
                            echo $_POST['code'];
                        } elseif(isset($_POST['readfile'])) {
                            $myfile = fopen($_POST['foldername'], "r") or die("Unable to open file!");
                            while(!feof($myfile)) {
                                echo fgets($myfile);
                            }
                            fclose($myfile);
                        }
                        ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <input type="submit" name="submit" value="Run">
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-6">
        <?php
        if(isset($_POST['submit'])) {
            echo "<p>";
            eval('?>' . $_POST['code']);
            echo "</p>";
        }
        ?>
    </div>
</div>

<?php } ?>