<?php
date_default_timezone_set( "Asia/Jakarta" );

$hostname = "10.20.50.161";
$username = "ITAdmin";
$password = "P@ssw0rd.1";

//$hostname = "localhost";
//$username = "root";
//$password = "";

$dbname = "sa_md_hcm";
$DBHCM = new mysqli($hostname, $username, $password, $dbname);
$mysql = "SELECT employee_name, employee_email FROM sa_view_employees WHERE organization_name='Solution Engineering'";
$res = mysqli_query( $DBHCM, $mysql ) or die($DBHCM->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBHCM->error . "<br/>");
$row = mysqli_fetch_assoc( $res );

$bcc = '';
do {
    $bcc .= $row['employee_name'] . "<" . $row['employee_email'] . ">,";
} while($row=$res->fetch_assoc());

$bcc .= "Syamsul Arham<syamsul@mastersystem.co.id>, Fortuna Arumsari<fortuna@mastersystem.co.id>,Lucky<lucky.andiani@mastersystem.co.id>,Raymond Citra<raymon@mastersystem.co.id>";

$to = "";
//$to = "Syamsul Arham<syamsul@mastersystem.co.id>";
$from="MSIZone<msizone@mastersystem.co.id>";
$subject="[MSIZone] Service Budget Reports " . date("F Y", strtotime("-1 month"));

$greating ='<p>Dear All,</p><p>Berikut ini merupakan report Service Budget untuk bulan ' . date("F Y", strtotime("-1 month")) . '. Silahkan ditindak lanjuti bila diperlukan.';

$msg ='
<table>
    <tr>
        <td style="width:20%; vertical-align:top; padding:10px; color:white" rowspan="7">
        </td>
        <td style="width:60%; border: thin solid #dadada; padding:20px 20px 20px 20px"><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></td>
        <td style="width:20%; rowspan="7"></td>
    </tr>
    <tr>
        <td style="width:60%; border: thin solid #dadada; height:5px; background:red"></td>
    </tr>
    <tr>
        <td style="width:60%; border: thin solid #dadada; padding:20px 20px 20px 20px">' . $greating . '</td>
    </tr>
    <tr>
        <td style="width:60%; border: thin solid #dadada; padding:20px 20px 20px 20px">';

$dbname = "sa_ps_service_budgets";
$DBSB = new mysqli($hostname, $username, $password, $dbname);

$msg .= '<table width="100%"><tr>';
    $condition = "status='draft'";
    $mysql = "SELECT * FROM sa_trx_project_list WHERE " . $condition;
    $res = mysqli_query( $DBSB, $mysql ) or die($$DBSB->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBSB->error . "<br/>");
    $tdoc = mysqli_num_rows( $res ); 

$msg .= '<td style="width:25%; height:100px; vertical-align:midle; background:gray; font-size:18px; font-weight:bold; text-align:center; color:white;"><p>Draft</p><p>' . $tdoc . '</p></td>';

    $condition = "status='submited'";
    $mysql = "SELECT * FROM sa_trx_project_list WHERE " . $condition;
    $res = mysqli_query( $DBSB, $mysql ) or die($$DBSB->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBSB->error . "<br/>");
    $tdoc = mysqli_num_rows( $res ); 

$msg .= '<td style="width:25%; height:100px; vertical-align:midle; background:purple; font-size:18px; font-weight:bold; text-align:center; color:white;"><p>Submited</p><p>' . $tdoc . '</p></td>';

    $condition = "status='approved' AND MONTH(modified_date)=\"" . date("m", strtotime("-1 month")) . "\" AND YEAR(modified_date)=\"" . date("Y", strtotime("-1 month")) . "\"";
    $mysql = "SELECT * FROM sa_trx_project_list WHERE " . $condition;
    $res = mysqli_query( $DBSB, $mysql ) or die($$DBSB->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBSB->error . "<br/>");
    $tdoc = mysqli_num_rows( $res ); 

$msg .= '<td style="width:25%; height:100px; vertical-align:midle; background:teal; font-size:18px; font-weight:bold; text-align:center; color:white;"><p>Approved</p><p>' . $tdoc . '</p></td>';

    $condition = "status='rejected'";
    $mysql = "SELECT * FROM sa_trx_project_list WHERE " . $condition;
    $res = mysqli_query( $DBSB, $mysql ) or die($$DBSB->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBSB->error . "<br/>");
    $tdoc = mysqli_num_rows( $res ); 

$msg .= '<td style="width:25%; height:100px; vertical-align:midle; background:orange; font-size:18px; font-weight:bold; text-align:center; color:white;"><p>Rejected</p><p>' . $tdoc . '</p></td>';

$msg .= '<tr></table>';

$msg .='
        </td>
    </tr>';

    $solution_name = array('ASA'=>'Adaptive Security Architecture', 'BDA'=>'Big Data & Analytics', 'DCCI'=>'Data Center & Cloud Infrastructure', 'EC'=>'Enterprise Collaboratoion', 'DBM'=>'Digital Business Management', 'SP'=>'Service Provider');

    $mysql = "select `solution_name`, avg(coalesce(`product`,0)) AS `product` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `so_number` IN (select `so_number` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `product`>0 AND MONTH(`modified_date`)='" . date("m", strtotime("-1 month")) . "' group by `so_number` order by `so_number`) group by `solution_name` order by `so_number`;";
    $res1 = mysqli_query( $DBSB, $mysql ) or die($$DBSB->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBSB->error . "<br/>"); 
    $row1 = mysqli_fetch_assoc($res1);
    $tot1 = mysqli_num_rows( $res1 ); 

    if($tot1>0) {

$msg .= '<tr>
             <td style="width:60%; border: thin solid #dadada; padding:20px 20px 20px 20px">';

$msg .= '    <table width="100%">
             <thead>
             <tr><th style="border-bottom: solid thin #dadada">Solution Name</th><th style="border-bottom: solid thin #dadada">Product</th><th style="border-bottom: solid thin #dadada">Service</th></tr>
             </thead>
             <tbody>';

             $tProduct=0; $tService=0;
             do {
                 $mysql = "select `solution_name`, avg(coalesce(`services`,0)) AS `service` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `so_number` IN (select `so_number` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `services`>0 AND MONTH(`modified_date`)='" . date("m", strtotime("-1 month")) . "' group by `so_number` order by `so_number`) AND `solution_name`='" . $row1['solution_name'] . "' group by `solution_name` order by `so_number`;";
                 $res2 = mysqli_query( $DBSB, $mysql ) or die($$DBSB->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBSB->error . "<br/>");
                 $row2 = mysqli_fetch_assoc($res2);
                 $tot2 = mysqli_num_rows( $res2 ); 
    
$msg .= '        <tr><td>' . $solution_name[$row1['solution_name']] . '</td><td style="text-align:right">' . number_format($row1['product'], 2, ",", ".") . '%</td><td style="text-align:right">' . number_format($row2['service'], 2, ",", ".") . '%</td></tr>';

                    $tProduct += $row1['product']; $tService += $row2['service'];
                    
             } while($row1=$res1->fetch_assoc());

$msg .= '    </tbody>
             <tfoot>
             <tr><th style="text-align:right; border-top: solid thin #dadada; border-bottom: double 3px #dadada">Total</th><th style="text-align:right; border-top: solid thin #dadada; border-bottom: double 3px #dadada">' . number_format($tProduct, 2, ",", ".") . '%</th><th style="text-align:right; border-top: solid thin #dadada; border-bottom: double 3px #dadada">' . number_format($tService, 2, ",", ".") . '%</th></tr>
             </tfoot>
         </table>';

$msg .= '    </td>
         </tr>';

            }

    $dbname = "sa_msiguide";
    $DBDOC = new mysqli($hostname, $username, $password, $dbname);
    $mysql = "SELECT post_title, post_modified, post_name FROM wp_posts WHERE post_status='publish' AND post_type='post' AND post_title LIKE'%Service Budget%' ORDER BY post_modified DESC LIMIT 0,5";
    mysqli_set_charset( $DBDOC, 'utf8' );
    $res = mysqli_query( $DBDOC, $mysql ) or die($$DBDOC->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBDOC->error . "<br/>");
    $row = mysqli_fetch_assoc( $res );
    $total_rows = mysqli_num_rows( $res ); 
    
$msg .='<tr>
        <td style="width:60%; border: thin solid #dadada">
            <table width="100%">
            <tr><td style="width:50%; padding:20px 20px 20px 20px">
            <div style="font-weight:bold; vertical-align:top; border-bottom:red thin solid; margin-bottom:5px">Service Budget Update</div>';
             do {
                $titleexp = explode(":", $row['post_title']);
                if(count($titleexp)>1) {
                    $title = $titleexp[1];
                } else {
                    $title = $titleexp[0];
                }
          $msg .= '<p><span style="font-color:#000;"><a href="https://msizone.mastersystem.co.id/msiguide/' . $row['post_name'] . '" target="_blank" style="color:black;text-decoration: none;">' . $title . '</a></span><br/>';
              } while($row=$res->fetch_assoc());
$msg .= '</td>';

$mysql = "SELECT post_title, post_modified, post_name FROM wp_posts WHERE post_status='publish' AND post_type='post' AND post_title LIKE'%MSIZone%' ORDER BY post_modified DESC LIMIT 0,2";
mysqli_set_charset( $DBDOC, 'utf8' );
$res = mysqli_query( $DBDOC, $mysql ) or die($$DBDOC->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBDOC->error . "<br/>");
$row = mysqli_fetch_assoc( $res );
$tot = mysqli_num_rows($res);

$msg .= '          <td style="vertical-align:top; width:50%; padding:20px 20px 20px 20px"">
                   <div style="font-weight:bold; vertical-align:top; border-bottom:red thin solid; margin-bottom:5px">MSIZone Update</div>';
                   if($tot>0) {
                   do {
                        $titleexp = explode(":", $row['post_title']);
                        if(count($titleexp)>1) {
                            $title = $titleexp[1];
                        } else {
                            $title = $titleexp[0];
                        }
                        $msg .= '<p><span style="font-color:#000;"><a href="https://msizone.mastersystem.co.id/msiguide/' . $row['post_name'] . '" target="_blank" style="color:black;text-decoration: none;">' . $title . '</a></span><br/>';
                    } while($row=$res->fetch_assoc());
                    }
                   $msg .='<div style="font-weight:bold; vertical-align:top; border-bottom:red thin solid; margin-bottom:5px">MSIZone Link</div>
                   <p><a href="https://msizone.mastersystem.co.id" target="_blank" style="color:black;text-decoration: none;">MSIZone</a></p>
                   <p><a href="https://msizone.mastersystem.co.id/msiguide" target="_blank" style="color:black;text-decoration: none;">MSIGuide</a></p>
               </td>
            </tr></table>';
 $msg .='</td>
    </tr>
    <tr>
        <td style="width:60%; border: thin solid #dadada; padding:10px 20px 10px 20px; font-size:10px">Dikirim secara otomatis oleh sistem MSIZone.<br/>
Jangan membalas/reply email ini.</td>
    </tr>
</table>';
  echo $msg;
$headers = "From: " . $from . "\r\n" .
    "Bcc: " . $bcc . "\r\n" .
    "MIME-Version: 1.0" . "\r\n" .
    "Content-Type: text/html; charset=UTF-8" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();
    
if(mail($to, $subject, $msg, $headers)) {
    echo "Email terkirim pada jam " . date("d M Y G:i:s");
} else {
   echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
}
?>
