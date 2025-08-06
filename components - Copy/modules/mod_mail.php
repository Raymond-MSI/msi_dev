<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    if(isset($_POST['send'])) {
        $from=$_POST['from'];
        $to=$_POST['to'];
        $cc=$_POST['cc'];
        $bcc=$_POST['bcc'];
        $reply=$from;
        $subject=$_POST['subject'];
        $msg=$_POST['message'];
        $headers = "From: " . $from . "\r\n" .
            "Reply-To: " . $reply . "\r\n" .
            "Cc: " . $cc . "\r\n" .
            "Bcc: " . $bcc . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
            
        $ALERT=new Alert();
        if(!mail($to, $subject, $msg, $headers))
        {
            echo $ALERT->email_not_send();
        } else
        {
            echo $ALERT->email_send();
        }
    }
    ?>
    <form name="form" method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>"> 
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                </div>
                <div class="card-body">
                    <div class="row">
                        <form action="index.php?mod=mail" method="POST">
                            <!-- <div class="row mb-3">
                                <label class="col-sm-1 col-form-label col-form-label-sm">From : </label>
                                <div class="col-sm-11"> -->
                                    <input type="hidden" name="from" class="form-control form-control-sm" value="MSIZone<msizone@mastersystem.co.id>">
                                <!-- </div>
                            </div> -->
                            <div class="row mb-3">
                                <label class="col-sm-1 col-form-label col-form-label-sm">To : </label>
                                <div class="col-sm-11">
                                    <input type="text" name="to" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-1 col-form-label col-form-label-sm">Cc : </label>
                                <div class="col-sm-11">
                                    <input type="text" name="cc" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-1 col-form-label col-form-label-sm">Bcc : </label>
                                <div class="col-sm-11">
                                    <input type="text" name="bcc" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-1 col-form-label col-form-label-sm">Subject : </label>
                                <div class="col-sm-11">
                                    <input type="text" name="subject" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-1 col-form-label col-form-label-sm">Message : </label>
                                <div class="col-sm-11">
                                    <textarea name="message" class="form-control form-control-sm"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <input type="submit" class="btn btn-secondary" name="send" value="Send">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>

<?php } ?>