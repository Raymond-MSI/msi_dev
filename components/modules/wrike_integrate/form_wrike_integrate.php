
<?php
$_SESSION['Microservices_UserEmail'] = 'syamsul@mastersystem.co.id';
include_once("components/modules/wrike_integrate/func_wrike_integrate.php");
$modulename = 'WRIKE_INTEGRATE';
$DBWR = get_conn($modulename);
    if($_GET['act']=='edit') {
        global $DBWR;
        $condition = "log_id=" . $_GET['log_id'];
        $data = $DBWR->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }

    // function get_CURL($url)
    // {
    //     $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
    //     $curl = curl_init();

    //     curl_setopt($curl, CURLOPT_URL, $url);
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //     $result = curl_exec($curl);
    //     curl_close($curl);

    //     return json_decode($result, true);
    // };

    // //GET Customer Name
    // $result = get_CURL('https://www.wrike.com/api/v4/comments');
    // $result1 = $result['data'];
    
    global $DBWR;
    // $db_name = "wrike_integrate";
    // $tblname = "wrike_get_comment";
    // $data = $DBWR->get_data($tblname);
    // $row = $data[0];
    // $res = $data[1];
    // $totalRows = $data[2];

    contactSave();
    commentLog();
    getComment();




    
    
    

    // foreach($result as $value):
    //     $i=0;
    //     $comment_id = $value['data'][$i]['id'];
    //     $created_date = $value['data'][$i]['createdDate'];
    //     $author_id = $value['data'][$i]['authorId'];
    //     $text_comment = $value['data'][$i]['text'];
    //     $folder_task_id = $value['data'][$i]['taskId'];
    //     $res = commentLog($comment_id, $created_date, $author_id, $text_comment, $folder_task_id);
    //     $i++;
    // endforeach;
    

    //$res = commentLog($comment_id, $created_date, $author_id, $text_comment, $folder_task_id);

    // if($res){
    //     echo "Berhasil";
    // }else{
    //     echo "Gagal";
    //}
?>




<!-- <form method="post" action="index.php?mod=">
    <div class="row">
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Log Id</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="log_id" name="log_id"
                        value="<?php if($_GET['act']=='edit') { echo $ddata['log_id']; } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Created Date</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="created_date" name="created_date"
                        value="<?php if($_GET['act']=='edit') { echo $ddata['created_date']; } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Author Id</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="author_id" name="author_id"
                        value="<?php if($_GET['act']=='edit') { echo $ddata['author_id']; } ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Text Comment</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="text_comment" name="text_comment"
                        value="<?php if($_GET['act']=='edit') { echo $ddata['text_comment']; } ?>">
                </div>
            </div>
        </div>
    </div>
    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
    <?php if(isset($_GET['act']) && $_GET['act']=='edit') { ?>
    <input type="submit" class="btn btn-primary" name="save" value="Save">
    <?php } elseif(isset($_GET['act']) && $_GET['act']=='add') { ?>
    <input type="submit" class="btn btn-primary" name="add" value="Save">
    <?php } ?>
</form> -->
