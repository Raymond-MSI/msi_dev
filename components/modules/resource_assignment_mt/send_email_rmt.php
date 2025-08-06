<div class="modal fade" id="modal_email_to" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Email RMT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <?php
                        $order_number = $_GET['order_number'];
                        $count_email = "SELECT count(*) as count_total  FROM sa_email_renewal WHERE  order_number = '$order_number'";
                        $count = $DBKPI->get_sql($count_email);
                        $total_email_send = $count[0]['count_total'] + 1;
                        ?>
                        <label for="email_subject" class="form-label">Subject Email:</label>
                        <div class="d-flex gap-2">
                            <input
                                readonly
                                type="text"
                                id="email_subject"
                                class="form-control col-4"
                                name="subject_input"
                                placeholder="<?php echo '[RMT - ' . ($_GET['project_code'] ?? '') . ' - ' . ($_GET['order_number'] ?? '') . ']'; ?>" />

                            <input
                                type="text"
                                id="email_subject_2"
                                class="form-control col-7"
                                name="subject_input"
                                placeholder="Free Text Subject" />

                            <input
                                type="text"
                                id="email_subject_3"
                                class="form-control col-1"
                                name="count_email"
                                placeholder="<?php echo $total_email_send; ?>" readonly />
                        </div>
                    </div>
                    <label for="email_to">Email To </label>

                    <input type="text" id="email_search" class="form-control mb-3" onkeyup="searchEmail()" placeholder="Search Email ...">

                    <!-- Dropdown dengan multiple select -->
                    <select class="form-control" id="email_to" name="email_to[]" multiple onchange="addSelectedEmails()">
                        <?php
                        $email = $DBHCM->get_sql("SELECT employee_email FROM sa_view_employees WHERE resign_date IS NULL ORDER BY employee_email ASC;");
                        $row = $email[0];
                        do {
                            echo '<option value="' . $row['employee_email'] . '">' . $row['employee_email'] . '</option>';
                        } while ($row = $email[1]->fetch_assoc());
                        ?>
                    </select>

                    <!-- Container untuk menampilkan email yang dipilih -->
                    <div id="selected_emails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_email_to" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk mencari email dalam dropdown
    function searchEmail() {
        const searchTerm = document.getElementById('email_search').value.toLowerCase();
        const options = document.querySelectorAll('#email_to option');

        options.forEach(option => {
            const emailText = option.textContent.toLowerCase();
            option.style.display = emailText.includes(searchTerm) ? '' : 'none';
        });
    }

    // Fungsi untuk menambahkan email yang dipilih ke dalam daftar
    function addSelectedEmails() {
        const selectedEmails = Array.from(document.querySelectorAll('#email_to option:checked')).map(option => option.value);
        const selectedEmailsContainer = document.getElementById('selected_emails');

        // Membuat list untuk menampilkan email yang dipilih
        let selectedEmailsList = selectedEmailsContainer.querySelector('ul');
        if (!selectedEmailsList) {
            selectedEmailsList = document.createElement('ul');
            selectedEmailsContainer.appendChild(selectedEmailsList);
        }

        // Menambahkan email ke daftar jika belum ada
        selectedEmails.forEach(email => {
            if (!document.querySelector(`li[data-email="${email}"]`)) {
                const li = document.createElement('li');
                li.textContent = email;
                li.classList.add('email_2_kirim'); // Tambahkan class
                li.setAttribute('data-email', email); // Simpan email dalam attribute

                // Hidden input agar bisa dikirim dalam form
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'email_2_kirim[]';
                hiddenInput.value = email;
                li.appendChild(hiddenInput);

                // Tombol untuk menghapus email
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.classList.add('btn', 'btn-danger', 'btn-sm', 'm-2');
                deleteButton.onclick = () => removeEmail(email, li);

                li.appendChild(deleteButton);
                selectedEmailsList.appendChild(li);
            }
        });
    }

    // Fungsi untuk menghapus email dari daftar
    function removeEmail(email, listItem) {
        listItem.remove(); // Hapus dari tampilan
        const option = document.querySelector(`#email_to option[value="${email}"]`);
        if (option) {
            option.selected = false; // Uncheck di dropdown
        }
    }
</script>

<?php
if (isset($_POST['submit_email_to'])) {
    // Periksa apakah email_2_kirim dikirim dengan benar
    if (!empty($_POST['email_2_kirim'])) {
        $email_2_kirim = $_POST['email_2_kirim'];
        $project_code = isset($_GET['project_code']) ? $_GET['project_code'] : '';
        $order_number = isset($_GET['order_number']) ? $_GET['order_number'] : '';
        $project_type = isset($_GET['project_type']) ? $_GET['project_type'] : '';
        $subject_input = $_POST['subject_input'];
        $count_email = $_POST['count_email'];

        $checking3 = $DBKPI->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='mt_date_end'");
        if (empty($checking3[0]['date'])) {
            $mt_date_end = null;
        } else {
            $mt_date_end = $checking3[0]['date'];
        }
        $checking3 = $DBKPI->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='mt_date_start'");
        if (empty($checking3[0]['date'])) {
            $mt_date_start = null;
        } else {
            $mt_date_start = $checking3[0]['date'];
        }

        $wr = $DBWR->get_sql("SELECT * from sa_wrike_project_detail where project_code = '$project_code'");
        $perangkat = $wr[0]['product'];
        if ($perangkat == '') {
            $perangkat2 = 'Tidak ada';
        } else {
            $perangkat2 = $perangkat;
        }
        $project_category = $wr[0]['project_category'];


        $wr_list = $DBWR->get_sql("SELECT * from sa_wrike_project_list where project_code = '$project_code' and order_number = '$order_number'");
        $title = $wr_list[0]['title'];

        // var_dump($input_name_to);


        // $username = $_SESSION['Microservices_UserName'];
        // $email = "aryo.bimo@mastersystem.co.id";
        $from = $_SESSION['Microservices_UserEmail'];
        $repo = "repoadmin@mastersystem.co.id";
        $to = implode(",", $email_2_kirim);
        $to2 = $repo . "," . $to;
        $cc = $from;
        $bcc = "";
        $reply = $from;
        $subject_utama = "[RMT - " . $project_code . " - " . "$order_number" . "]";
        $subject = $subject_utama . " " . $subject_input . " [" . $total_email_send . "]";




        // $msg = $subject;
        // $msg .= "<br><br>";
        // $msg .= $to2;
        $msg = "<p>Mohon konfirmasinya mengenai proses renewal maintenance project Pemeliharaan</p>";
        // $msg .= $subject;
        // $msg .= "<br><br>";
        // $msg .= $to2;

        $msg .= "<table style='width:100%; border-collapse: collapse; border: thin solid #dadada;'>
            <tr>
                <th style='border: thin solid #dadada; padding: 8px; background-color: #f2f2f2;'>Perangkat</th>
                <th style='border: thin solid #dadada; padding: 8px; background-color: #f2f2f2;'>Project Type</th>
                <th style='border: thin solid #dadada; padding: 8px; background-color: #f2f2f2;'>Project Name</th>
                <th style='border: thin solid #dadada; padding: 8px; background-color: #f2f2f2;'>Project Code</th>
                <th style='border: thin solid #dadada; padding: 8px; background-color: #f2f2f2;'>Start Date</th>
                <th style='border: thin solid #dadada; padding: 8px; background-color: #f2f2f2;'>End Date</th>
                <th style='border: thin solid #dadada; padding: 8px; background-color: #f2f2f2;'>Maintenance Service</th>
            </tr>
            <tr>
                <td style='border: thin solid #dadada; padding: 8px;'>$perangkat2</td>
                <td style='border: thin solid #dadada; padding: 8px;'>$project_type</td>
                <td style='border: thin solid #dadada; padding: 8px;'>$title</td>
                <td style='border: thin solid #dadada; padding: 8px;'>$project_code</td>
                <td style='border: thin solid #dadada; padding: 8px;'>$mt_date_start</td>
                <td style='border: thin solid #dadada; padding: 8px;'>$mt_date_end</td>
                <td style='border: thin solid #dadada; padding: 8px;'>$project_category</td>
            </tr>
        </table>";

        // $msg .= "<p>Project Completed</p>";
        $msg .= "<br><br>";
        $msg .= "<p>Terima Kasih,<br/>";
        $msg .= "</td><td width='30%' rowspan='3'></td></tr>";
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";

        // var_dump("dikirim ke = " . $to2);
        // var_dump($msg);
        // // var_dump($from);
        // var_dump($subject);



        $headers = "From: " . $from . "\r\n" .
            "Reply-To: " . $reply . "\r\n" .
            "Cc: " . $cc . "\r\n" .
            "Bcc: " . $bcc . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        $ALERT = new Alert();
        if (!mail(
            $to2,
            $subject,
            $msg,
            $headers
        )) {
            echo "<script type='text/javascript'>
            alert('Email gagal terkirim pada jam " . date("d M Y G:i:s") . "');
        </script>";
        } else {
            echo "<script type='text/javascript'>
            alert('Email terkirim pada jam " . date("d M Y G:i:s") . "');
        </script>";
        }
    } else {
        echo "<script type='text/javascript'>
            alert('Tidak ada email yang dipilih');
        </script>";
    }
}
?>