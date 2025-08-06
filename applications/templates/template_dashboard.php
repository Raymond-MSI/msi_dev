<?php
function item1($title, $value, $color="primary")
{
    ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-<?php echo $color; ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-<?php echo $color; ?> text-uppercase mb-1">
                            <?php echo $title; ?>
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <?php echo $value; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function item2($title="", $data="", $color="danger")
{
    $total = $data['total'];
    $title = $data['title'];
    $msg = $data['data'];
    ?>
    <div class="col-xl-12 col-md-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-xs font-weight-bold text-<?php echo $color; ?> text-uppercase mb-1"><?php echo $title; ?></div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $total; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="text-xs mb-1 table-responsive">
                                    <?php echo $msg; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function show_dashboard($title, $items)
{
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-secondary"><?php echo $title; ?></h6>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <?php
                $i=0;
                foreach($items as $item)
                {
                    if($i==0)
                    {
                        $color = "danger";
                    } elseif($i==1)
                    {
                        $color = "success";
                    } elseif($i==2)
                    {
                        $color = "primary";
                    } elseif($i==3)
                    {
                        $color = "info";
                    } elseif($i==4)
                    {
                        $color = "secondary";
                    }
                    if($item['type']=="item1")
                    {
                        item1($item['title'], $item['data'], $color);
                    } else
                    {
                        item2($item['title'], $item['data'], $color);
                    }
                    $i++;
                }
                ?>
            </div>
        </div>
    </div>
    <?php 
} 
?>
