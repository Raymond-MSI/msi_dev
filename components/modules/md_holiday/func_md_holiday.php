<?php

if (isset($_SESSION['Microservices_UserEmail'])) {
    $loggedInUserName = $_SESSION['Microservices_UserEmail'];
} else {
    $loggedInUserName = "Anonymous";
}

function is_duplicate_date($DBHCM, $table, $date, $id = null)
{

    $monthDay = date("m-d-y", strtotime($date));
    $condition = "DATE_FORMAT(holiday_date, '%m-%d-%y') = " . GetSQLValueString($monthDay, "text") .
        " AND (is_deleted IS NULL OR is_deleted = 0)";

    if ($id !== null) {
        $condition .= " AND id != " . GetSQLValueString($id, "int");
    }
    $check = $DBHCM->get_data($table, $condition);
    if (is_array($check) && isset($check[2]) && $check[2] > 0) {
        return true;
    }
    return false;
}

// === ADD ===
if (isset($_POST['add']) && !empty($_POST['holiday_date']) && !empty($_POST['Descriptions'])) {
    // if (is_duplicate_date($DBHCM, $tblname, $_POST['holiday_date'])) {
    //     echo '<div class="alert alert-warning">A holiday already exists on this date (same day and month).</div>';
    // } else {
    //     $insert = sprintf(
    //         "(`id`,`holiday_date`,`Descriptions`,`created_by`,`modified_by`) VALUES (%s,%s,%s,%s,%s)",
    //         GetSQLValueString($_POST['id'], "int"),
    //         GetSQLValueString($_POST['holiday_date'], "text"),
    //         GetSQLValueString($_POST['Descriptions'], "text"),
    //         GetSQLValueString($loggedInUserName, "text"),
    //         GetSQLValueString($loggedInUserName, "text")
    //     );
    //     $res = $DBHCM->insert_data($tblname, $insert);
    //     $ALERT->savedata(); // Show success alert
    // }

    $dates = $_POST['holiday_date'];
    $descs = $_POST['Descriptions'];

    $duplicates = [];
    $inserted = 0;

    for ($i = 0; $i < count($dates); $i++) {
        $date = trim($dates[$i]);
        $desc = trim($descs[$i]);

        if (empty($date) || empty($desc)) {
            continue;
        }
        if (is_duplicate_date($DBHCM, $tblname, $date)) {
            $duplicates[] = $date;
        } else {
            $insert = sprintf(
                "(`holiday_date`, `Descriptions`, `created_by`, `modified_by`) VALUES (%s, %s, %s, %s)",
                GetSQLValueString($date, "text"),
                GetSQLValueString($desc, "text"),
                GetSQLValueString($loggedInUserName, "text"),
                GetSQLValueString($loggedInUserName, "text")
            );

            $DBHCM->insert_data($tblname, $insert);
            $inserted++;
        }
        if ($inserted > 0) {
            $ALERT->savedata(); // Show success alert
        }

        if (!empty($duplicates)) {
            echo '<div class="alert alert-warning">This date is already exist: <br>' .
                implode('<br>', array_map('htmlspecialchars', $duplicates)) . '</div>';
        }
    }

    // === EDIT ===
} elseif (isset($_POST['save']) && isset($_POST['holiday_date'])) {
    if (is_duplicate_date($DBHCM, $tblname, $_POST['holiday_date'], $_POST['id'])) {
        echo '<div class="alert alert-warning">Another holiday already exists on this dategi.</div>';
    } else {
        $condition = "id=" . GetSQLValueString($_POST['id'], "int");
        $update = sprintf(
            "`holiday_date`=%s,`Descriptions`=%s,`modified_by`=%s",
            GetSQLValueString($_POST['holiday_date'], "text"),
            GetSQLValueString(addslashes($_POST['Descriptions']), "text"),
            GetSQLValueString($loggedInUserName, "text")
        );
        $res = $DBHCM->update_data($tblname, $update, $condition);
        $ALERT->savedata(); // Show success alert
    }
} elseif (isset($_POST['del'])) {
    $condition = "id=" . GetSQLValueString($_POST['id'], "int");
    $update = "`is_deleted` = 1, `modified_by` = " . GetSQLValueString($loggedInUserName, "text");
    $res = $DBHCM->update_data($tblname, $update, $condition);
    // header("Location: index.php?mod=md_holiday");
    // exit;
    echo '<script>window.location.href="index.php?mod=md_holiday";</script>';
    exit;
} elseif (isset($_POST['cancel'])) {
    // header("Location: index.php?mod=md_holiday");
    // exit;
    echo '<script>window.location.href="index.php?mod=md_holiday";</script>';
    exit;
}
