<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>
    <div class="row">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                Search Project Code
            </div>
            <!-- <div class="card-body">
           </div> -->
           <ul class="list-group list-group-flush">
            <li class="list-group-item">
                </li>
               <li class="list-group-item">
                    <div class="row">
                        <div class="row mb-3">
                            <div class="col-sm-12">Input kode projek yang akan dicari.</div>
                        </div>
                    </div>
                    <div class="row">
                        <form name="form1" method="get" action="index.php"> 
                            <div class="row mb-3">
                                <label for="project_code" class="col-sm-3 col-form-label col-form-label-sm">No. KP</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control form-control-sm" name="project_code" id="project_code">
                                </div>
                                <div class="col-sm-2">
                                    <input type="submit" class="btn btn-primary" name="btn_serach" id="btn_search" value="Search">
                                </div>
                                <div class="col-sm-2">
                                    <input type="submit" class="btn btn-secondary" name="btn-cancel" id="btn-cancel" value="Cancel">
                                </div>
                                <input type="hidden" name="mod" id="mod" value="service_budget">
                                <input type="hidden" name="act" id="act" value="add">
                                <input type="hidden" name="submit" id="submit" value="Submit">
                            </div>
                        </form>
                    </div>
                </li>
                <li class="list-group-item">
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="row mb-3">
                            <div class="col-sm-12">Alternatif pencarian projek.</div>
                        </div>
                    </div>
                    <div class="row">
                        <form name="form2" method="get" action="index.php"> 
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <input type="hidden" name="mod" id="mod" value="project_list">
                                    <input type="submit" class="btn btn-primary" name="btn_serach" id="btn_search" value="Search">
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-4"></div>
    </div>
<?PHP } ?>