<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>

<!-- <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->

<div class="col-lg-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <?php spinner(); ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <script> var dtset = []; </script>
                <?php
                    for($i=0; $i<2; $i++) {
                        if($i==0) {
                            $components = "components/modules/";
                        } elseif($i==1) {
                            $components = "components/classes/";
                        } else {
                            $components = "components/js/";
                        }
                        $dir    = $components;
                        $listfiles1 = scandir($dir);
                        foreach ($listfiles1 as $file) { 
                            // echo substr($files3, 0, 3);
                            if((substr($file, 0, 3) == 'mod') || (substr($file, 0, 4) == 'func' && $file != 'func_property.php') || (substr($file, 0, 4) == 'java')) {
                                include("components/classes/func_property.php");
                                ?>
                                <script>
                                    var var1 = "<?php echo $file; ?>";
                                    var var2 = "<?php echo $properties['version']; ?>";
                                    var var3 = "<?php echo $properties['released']; ?>";
                                    var var4 = "<?php echo $properties['author']; ?>";
                                    var var5 = "<?php echo $properties['ctime']; ?>";
                                    var var6 = "<?php echo $properties['mtime']; ?>";
                                    var var7 = "<?php echo $properties['atime']; ?>";
                                    var var8 = "<?php echo $properties['dev']; ?>";
                                    var var9 = "<?php echo $properties['rdev']; ?>";
                                    var var10 = "<?php echo $properties['ino']; ?>";
                                    var var11 = "<?php echo $properties['mode']; ?>";
                                    var var12 = "<?php echo $properties['nlink']; ?>";
                                    var var13 = "<?php echo $properties['uid']; ?>";
                                    var var14 = "<?php echo $properties['gid']; ?>";
                                    var var15 = "<?php echo $properties['size']; ?>";
                                    var var16 = "<?php echo $properties['blksize']; ?>";
                                    var var17 = "<?php echo $properties['blocks']; ?>";

                                    dtset.push([var1, var2, var3, var4, var5, var6, var7, var8, var9, var10, var11, var12, var13, var14, var15, var16, var17]);
                                </script>
                                <?php
                            }
                        }
                    }
                ?>


                <script>
                    var dataSet = dtset;
 
                    $(document).ready(function() {
                        $('#example').DataTable( {
                            data: dataSet,
                            columns: [
                                { title: "file" },
                                { title: "version"},
                                { title: "released"},
                                { title: "author"},
                                { title: "created" },
                                { title: "modified" },
                                { title: "atime." },
                                { title: "dev" },
                                { title: "rdev" },
                                { title: "ino" },
                                { title: "mode" },
                                { title: "nlink" },
                                { title: "uid" },
                                { title: "gid" },
                                { title: "size" },
                                { title: "blksize" },
                                { title: "blocks" }
                            ],
                            "columnDefs": [
                                {
                                    "targets": [ 6,7,8,9,10,11,12,13,14,15,16 ],
                                    "visible": false
                                },
                            ]
                        } );
                    } );
                </script>

                <table id="example" class="display" width="100%"></table>

            </div>
        </div>
    </div>
</div>


<?php
}
?>




<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
