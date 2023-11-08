<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .certificate-table-head,
    #certificateList {
        box-shadow: -1px 4px 11px -7px rgba(0, 0, 0, 0.75);
        width: 100%;
        font-size: 16px;
        background-color: #213159;
        color: white;
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        text-align: center;
    }

    .certificate-table-head p {
        border-left: 1px solid white;
        margin: 0;
        font-weight: 700;
        padding: 10px 0px;
    }

    .certificate-table-head p:first-child {
        border: none;
    }

    #certificateList {
        border: 1px solid #3d6098;
        background: none;
        color: #3d6098;
    }

    #certificateList p {
        margin: 0;
        padding: 10px 0px;
        border-left: 1px solid #213159;
        text-align: center;
    }

    #certificateList>div {
        border-left: 1px solid #213159;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #certificateList>div button {
        cursor: pointer;
        padding: 5px 10px;
        border: none;
    }


    #certificateList .active-certificate {
        background-color: lightgreen;
        border: none;
        padding: 5px 10px;
        color: #2F6D3E;
        width: 80%;
    }

    #certificateList .inactive-certificate {
        background-color: lightgray;
        border: none;
        width: 80%;
        padding: 5px 10px;
        color: gray;
    }

    .pop-up-from {
        border: 1px solid red;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-color: #0000004e;
    }

    .pop-up-from>div {
        border-radius: 2px;
        width: 45%;
        margin: auto;
        margin-top: 20%;
        background-color: white;
        padding: 20px;
    }

    .wp-die-message,
    p {
        font-size: 16px;
    }

    .pop-up-from form>div {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
    }

    .pop-up-from form>div button {
        padding: 15px 25px;
        cursor: pointer;
        font-weight: 700;
        color: white;
        background-color: #3d6098;
        border: none;
        border: 1px solid white;
        font-size: 16px;
    }

    .pop-up-from form>div button:hover {
        background-color: #e7e7e7;
        border-radius: 5px;
        transition: all 700ms;
        color: #3d6098;
        border: 1px solid #3d6098;
    }

    .pop-up-from form>div button:last-child {
        background-color: green;
    }

    .pop-up-from form>div button:last-child:hover {
        background-color: white;
        border: 1px solid green;
        color: green;
    }

    #reject-popup form>div button:last-child {
        background-color: #F04B4C;
    }

    #reject-popup form>div button:last-child:hover {
        border: 1px solid #F04B4C;
        color: #F04B4C;
        background-color: white;
    }

    .pending_status,
    .approved_status,
    .rejected_status {
        width: 80%;
        margin: auto;
        color: white;
    }

    .pending_status {
        background-color: yellow;
    }

    .approved_status {
        background-color: green;
    }

    .rejected_status {
        background-color: red;
    }
</style>

<body>
    <h1>Certificate requests</h1>
</body>

</html>
<?php
function showCertificates()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'certificate_holder';

    $query = "SELECT * FROM $table_name ";

    $results = $wpdb->get_results($query);



    if ($results) {
        echo '<div class="certificate-table-head">
        <p>Employee ID</p>
        <p>Certificate</p>
        <p>Applied</p>
        <p>Status</p>
        <p>Validity in days</p>
        <p>End Date</p>
        <p>Approve</p>
        <p>Reject</p>
        </div>';


        foreach ($results as $row) {
            echo '<div id="certificateList" >';
            echo '<p>' . $row->employee_id . '</p>';
            echo '<p>' . $row->name . '</p>';

            echo '<p>' . $row->apply_date . '</p>';

            $status = $row->status;

            $status_class = '';

            if ($status == 'Pending') {
                $status_class = 'pending_status';
            } elseif ($status == 'Approved') {
                $status_class = 'approved_status';
            } elseif ($status == 'Rejected') {
                $status_class = 'rejected_status';
            }

            echo '<div><button class="' . $status_class . '">' . $status . '</button></div>';

            echo '<p>' . $row->validity_in_days . ' Days</p>';
            echo '<p>' . $row->end_date . '</p>';


            echo '<div><form method="post">';
            echo '<input type="hidden" name="approve_certificate_pop_up" value="' . esc_attr($row->id) . '">';
            echo '<button  type="submit">✔️</button>';
            echo '</form></div>';

            echo '<div><form method="post">';
            echo '<input type="hidden" name="reject_request_pop_up" value="' . esc_attr($row->id) . '">';
            echo '<button  type="submit">❌</button>';
            echo '</form></div>';

            echo '</div>';
        }
    }
}

if (isset($_POST['reject_request_pop_up'])) {

    $reject_certificate_id = intval($_POST['reject_request_pop_up']);

    echo '<div id="reject-popup" class="pop-up-from" >';
    echo '<div>';
    echo '<p>Are you sure you want to reject the certificate ?</p>';
    echo '<form method="post" action="">';
    echo '<input style="width: 100%; padding:10px;" type="hidden" name="reject_certificate_id" value="' . $reject_certificate_id . '">';
    echo '<div>';
    echo '<button onclick="closeDeletePopup()">Cancel</button>';
    echo '<button class="deleteBtn" type="submit" >Reject</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
}

if (isset($_POST['reject_certificate_id'])) {

    $reject_certificate_id = intval($_POST['reject_certificate_id']);

    global $wpdb;


    $table_name = $wpdb->prefix . 'certificate_holder';

    if ($reject_certificate_id > 0) {
        $wpdb->update(
            $table_name,
            array('status' => 'Rejected'),
            array('id' => $reject_certificate_id)
        );
    }
}

if (isset($_POST['approve_certificate_pop_up'])) {

    $approve_certificate_id = intval($_POST['approve_certificate_pop_up']);

    echo '<div id="approve-popup" class="pop-up-from" >';
    echo '<div>';
    echo '<p>Are you sure you want to Approve the certificate ?</p>';
    echo '<form method="post" action="">';
    echo '<input style="width: 100%; padding:10px;" type="hidden" name="approve_certificate_id" value="' . $approve_certificate_id . '">';
    echo '<div>';
    echo '<button onclick="closeApprovePopup()">Cancel</button>';
    echo '<button class="deleteBtn" type="submit">Approve</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
}

if (isset($_POST['approve_certificate_id'])) {

    $approve_certificate_id = intval($_POST['approve_certificate_id']);

    global $wpdb;

    $table_name = $wpdb->prefix . 'certificate_holder';

    $query = $wpdb->prepare("SELECT validity_in_days FROM $table_name WHERE id = %d", $approve_certificate_id);

    $validity_in_days = $wpdb->get_var($query);


    $start_date = date('Y-m-d');
    $days_to_add = $validity_in_days;


    $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $days_to_add . ' days'));


    if ($approve_certificate_id > 0) {
        $wpdb->update(
            $table_name,
            array('status' => 'Approved', 'start_date' => $start_date, 'end_date' => $end_date),
            array('id' => $approve_certificate_id)
        );
    }
}

showCertificates();


?>

<script>
    const closeApprovePopup = () => {
        const approveopoup = document.getElementById('approve-popup')

        approveopoup.style.display = 'none'
    }

    const closeDeletePopup = () => {
        const rejectpoup = document.getElementById('reject-popup')

        rejectpoup.style.display = 'none'
    }
</script>