<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="dashboard-navbar">
        <?php
        $current_user = wp_get_current_user();

        if ($current_user && $current_user->display_name) {
            echo '<p>Welcome, ' . esc_html($current_user->display_name) . '</p>';
        }
        ?>
        <P>
            <?php
            $logout_url = wp_logout_url(home_url('/login'));

            echo '<a href="' . esc_url($logout_url) . '">Logout</a>';
            ?>
        </P>
    </div>


</body>


</html>
<?php

function showCertificates()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'certificate';

    $query = "SELECT * FROM $table_name WHERE status = 1";

    $results = $wpdb->get_results($query);



    if ($results) {

        echo '<h3>Certificates</h3>';

        echo '<div class="certificate-table-head">
        <p>Name</p>
        <p>Description</p>
        <p>Validity in days</p>
        <p>Created</p>
        <p>Apply</p>
        </div>';


        foreach ($results as $row) {
            echo '<div id="certificateList" >';
            echo '<p>' . $row->name . '</p>';


            $description = $row->description;
            if (strlen($description) > 12) {
                $displayEmail = substr($description, 0, 15) . '...';
            } else {
                $displayEmail = $description;
            }
            echo '<p>' . $displayEmail . '</p>';


            echo '<p>' . $row->validity_in_days . ' Days</p>';
            echo '<p>' . $row->created . '</p>';

            echo '<div><form method="post">';
            echo '<input type="hidden" name="apply_certificate" value="' . esc_attr($row->id) . '">';
            echo '<button  type="submit">Apply</button>';
            echo '</form></div>';

            echo '</div>';
        }
    }
}

function showMyCertificates()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'certificate_holder';
    $user_id = get_current_user_id();


    $query = "SELECT * FROM $table_name WHERE employee_id = $user_id";

    $results = $wpdb->get_results($query);

    if ($results) {
        echo '<h3>My certificates</h3>';

        echo '<div class="certificate-table-head">
        <p>Name</p>
        <p>Status</p>
        <p>Left</p>
        <p>Started From</p>
        <p>Applied</p>
        </div>';

        foreach ($results as $row) {
            echo '<div id="certificateList" >';
            echo '<p>' . $row->name . '</p>';

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

            $today_date = date('Y-m-d');
            $date1 = strtotime($today_date);
            $date2 = strtotime($row->end_date);
            $difference_in_seconds = $date2 - $date1;
            $leftdays = $difference_in_seconds / (60 * 60 * 24);
            if ($row->end_date == null) {
                echo '<p>0</p>';
            } else {
                echo '<p>' . $leftdays . ' </p>';
            }

            echo '<p>' . $row->start_date . '</p>';
            echo '<p>' . $row->apply_date . '</p>';
            echo '</div>';
        }
    }
}


if ($_POST['apply_certificate']) {


    $certificate_id = intval($_POST['apply_certificate']);
    $user_id = get_current_user_id();

    global $wpdb;
    $table_name = $wpdb->prefix . 'certificate';

    $certificate = $wpdb->get_row($wpdb->prepare("SELECT name FROM $table_name WHERE id = %d", $certificate_id));
    
    $certificate_validity = $wpdb->get_row($wpdb->prepare("SELECT validity_in_days FROM $table_name WHERE id = %d", $certificate_id));


    if ($certificate) {

        $certificate_name = $certificate->name;

        $validity_in_days = $certificate_validity->validity_in_days;

        $today_date = date('Y-m-d');


        $data_to_insert = array(
            'name' => $certificate_name,
            'employee_id' => $user_id,
            'certificate_id' => $certificate_id,
            'certificate_name' => $certificate_name,
            'validity_in_days' => $validity_in_days,
            'apply_date' => $today_date,
            'start_date' => null,
            'end_date' => null,
            'status' => 'Pending'
        );

        $wpdb->insert('wp_certificate_holder', $data_to_insert);

        // if ($wpdb->insert('wp_certificate_holder', $data_to_insert) === false) {
        //     echo 'Error: ' . $wpdb->last_error;
        // } else {
        //     echo 'success';
        // }
    }
}


showMyCertificates();
showCertificates();
