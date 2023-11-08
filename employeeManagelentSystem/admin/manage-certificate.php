<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    #createCertificateForm {
        display: none;
    }

    #createCertificateForm>div,
    .pop-up-from>div {
        border-radius: 2px;
        margin: auto;
        width: 40%;
        background-color: white;
        padding: 40px;
        font-size: 16px;
    }

    #createCertificateForm>div input {
        height: 40px;
    }

    .pop-up-from>div {
        margin-top: 20%;
    }

    input,
    textarea {
        width: 100%;
        margin: 5px 0px 20px 0px;
    }

    form div {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        font-size: 16px;

    }

    form div button {
        padding: 15px 25px;
        cursor: pointer;
        font-weight: 700;
        color: white;
        background-color: #3d6098;
        border: none;
        border: 1px solid white;

    }

    form div button:hover {
        background-color: #e7e7e7;
        border-radius: 5px;
        transition: all 700ms;
        color: #3d6098;
        border: 1px solid #3d6098;
    }

    form div .deleteBtn {
        background-color: #f04b4c;
    }

    form div .deleteBtn:hover {
        background-color: #e7e7e7;
        border-radius: 5px;
        transition: all 700ms;
        color: #f04b4c;
        border: 1px solid #f04b4c;

    }

    .pop-up-from {
        border: 1px solid red;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: #0000004e;
    }

    p {
        font-size: 16px;
    }

    .pop-up-from>div>div {
        border: 1px solid orange;
    }

    .create-btn {
        border: 1px solid #e7e7e7;
        font-size: 20px;
        margin: 20px 0px;
        padding: 10px 50px;
        color: #e7e7e7;
        background-color: #213159;
        cursor: pointer;
    }

    .create-btn:hover {
        background-color: #e7e7e7;
        border-radius: 5px;
        transition: all 700ms;
        color: #213159;
        border: 1px solid #213159;
    }

    .certificate-table-head,
    #certificateList {
        box-shadow: -1px 4px 11px -7px rgba(0, 0, 0, 0.75);
        width: 100%;
        font-size: 16px;
        background-color: #213159;
        color: white;
        display: grid;
        grid-template-columns: repeat(9, 1fr);
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

    #certificateList>div:last-child button {
        background-color: #3d6098;
        color: white;
        padding: 5px 50px;
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

    .success-alert {
        border: 1px solid red;
    }
</style>

<body>
    <button class="create-btn" onclick="showForm()">Create Certificate ➡</button>
    <div id="createCertificateForm">
        <div>
            <form method="post" action="">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="validity_in_days">Validity in days:</label>
                <input type="number" name="validity_in_days" id="validity_in_days">

                <label for="description">Description:</label>
                <textarea name="description" id="description" cols="30" rows="5"></textarea>

                <div>
                    <button onclick="hideForm()">Close</button>
                    <button type="submit" name="create_certificate" value="Add Data">Create Certificate</button>
                </div>
            </form>
        </div>
    </div>
    <div class="certificate-table-head">
        <p>ID</p>
        <p>Name</p>
        <p>Description</p>
        <p>Validity in days</p>
        <p>Created</p>
        <p>Status</p>
        <p>Edit</p>
        <p>Delete</p>
        <p>Change Status</p>
    </div>

</body>

</html>
<?php


function showCertificates()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'certificate';

    $query = "SELECT * FROM $table_name";

    $results = $wpdb->get_results($query);



    if ($results) {

        foreach ($results as $row) {
            echo '<div id="certificateList" >';
            echo '<p>' . $row->id . '</p>';
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
            $statusClass = ($row->status == 1) ? 'active-certificate' : 'inactive-certificate';
            echo '<p > <button class="' . $statusClass . '" >' . ($row->status == 1 ? 'Active' : 'Inactive') . '</button></p>';

            echo '<div><form method="post">';
            echo '<input type="hidden" name="edit_certificate" value="' . esc_attr($row->id) . '">';
            echo '<button type="submit">🖊️</button>';
            echo '</form></div>';


            echo '<div><form method="post">';
            echo '<input type="hidden" name="delete_certificate" value="' . esc_attr($row->id) . '">';
            echo '<button type="submit">❌</button>';
            echo '</form></div>';

            echo '<div><form method="post">';
            echo '<input type="hidden" name="toggle_certificate_status" value="' . esc_attr($row->id) . '">';
            echo '<button  type="submit">Change</button>';
            echo '</form></div>';

            echo '</div>';
        }
    }
}
if (isset($_POST['toggle_certificate_status'])) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'certificate';

    $certificate_id = intval($_POST['toggle_certificate_status']);

    $current_status = $wpdb->get_var("SELECT status FROM $table_name WHERE id = $certificate_id");

    $new_status = ($current_status == 0) ? 1 : 0;

    $wpdb->update(
        $table_name,
        array('status' => $new_status),
        array('id' => $certificate_id)
    );
}

if (isset($_POST['create_certificate'])) {
    global $wpdb;
    $name = sanitize_text_field($_POST['name']);
    $validity_in_days = intval($_POST['validity_in_days']);
    $description = sanitize_textarea_field($_POST['description']);
    $created = current_time('mysql');
    $status = 1;

    $data = array(
        'name' => $name,
        'validity_in_days' => $validity_in_days,
        'description' => $description,
        'created' => $created,
        'status' => $status
    );

    $certificate_table_name = $wpdb->prefix . 'certificate';

    $wpdb->insert($certificate_table_name, $data);

    // if ($wpdb->insert_id) {
    //     echo "<p class='success-alert' >Certificate data inserted successfully!</p>";
    // } else {
    //     echo "<p class='error-alert' >Error: Certificate data insertion failed.</p>";
    // }
}

if (isset($_POST['delete_certificate'])) {

    $delete_certificate = intval($_POST['delete_certificate']);

    echo '<div id="delete-popup" class="pop-up-from" >';
    echo '<div>';
    echo '<p>Are you sure you want to detete the certificate ?</p>';
    echo '<form method="post" action="">';
    echo '<input style="width: 100%; padding:10px;" type="hidden" name="delete_certificate" value="' . esc_attr($delete_certificate) . '">';
    echo '<div>';
    echo '<button class="deleteBtn" type="submit" name="sure_detete_certificate">Delete</button>';
    echo '<button onclick="closeDeletePopup()">Cancel</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
}


if (isset($_POST['update_certificate'])) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'certificate';

    $certificate_id = intval($_POST['certificate_id']);
    $edit_name = sanitize_text_field($_POST['edit_name']);
    $edit_validity_in_days = intval($_POST['edit_validity_in_days']);
    $edit_description = wp_kses($_POST['edit_description'], array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'p' => array()
    ));

    if ($certificate_id > 0) {
        $wpdb->update(
            $table_name,
            array('name' => $edit_name, 'validity_in_days' => $edit_validity_in_days, 'description' => $edit_description),
            array('id' => $certificate_id)
        );
    }
}

if (isset($_POST['edit_certificate'])) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'certificate';

    $certificate_id = intval($_POST['edit_certificate']);

    if ($certificate_id > 0) {

        $employee = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $certificate_id));

        if ($employee) {
            echo '<div id="edit-popup"  class="pop-up-from" >';
            echo '<div style="margin-top:15%;" >';
            echo '<h2>Edit Employee</h2>';
            echo '<form method="post" action="">';
            echo '<input  type="hidden" name="certificate_id" value="' . esc_attr($certificate_id) . '">';
            echo '<label  for="edit_name">Name:</label>';
            echo '<input  type="text" name="edit_name" id="edit_name" value="' . esc_attr($employee->name) . '"><br>';
            echo '<label  for="edit_validity_in_days">Validity in days:</label>';
            echo '<input  type="number" name="edit_validity_in_days" id="edit_validity_in_days" value="' . esc_attr($employee->validity_in_days) . '"><br>';
            echo '<label  for="edit_description">Description:</label>';
            echo '<textarea name="edit_description" id="edit_description">' . esc_textarea($employee->description) . '</textarea>';
            echo '<div>';
            echo '<button onclick="closeEditPopup()">Cancel</button>';
            echo '<button type="submit" name="update_certificate">Update</button>';
            echo '</div>';
            echo '</form>';

            echo '</div>';

            echo '</div>';
        }
    }
}

if (isset($_POST['sure_detete_certificate'])) {
    global $wpdb;

    $certificate_id_to_delete = intval($_POST['delete_certificate']);


    $table_name = $wpdb->prefix . 'certificate';

    if ($certificate_id_to_delete > 0) {
        $wpdb->delete($table_name, array('id' => $certificate_id_to_delete));
    }

    echo "<script>document.getElementById('delete-popup').style.display = 'none';</script>";
}

showCertificates();

?>
<script>
    const showForm = () => {
        const createCertificateForm = document.getElementById('createCertificateForm');
        createCertificateForm.style.display = 'flex';
        createCertificateForm.style.position = 'fixed';
        createCertificateForm.style.top = '50%';
        createCertificateForm.style.left = '50%';
        createCertificateForm.style.width = '100%';
        createCertificateForm.style.height = '100%';
        createCertificateForm.style.transform = 'translate(-50%, -50%)';
        createCertificateForm.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
        createCertificateForm.style.borderRadius = '5px';
        createCertificateForm.style.backgroundColor = '#0000004e';
        createCertificateForm.style.zIndex = '1000';
        createCertificateForm.style.padding = '20px';

    }
    const hideForm = () => {
        const createCertificateForm = document.getElementById('createCertificateForm');
        createCertificateForm.style.display = 'none';
    }

    const closeEditPopup = () => {
        document.getElementById('edit-popup').style.display = 'none';
    }
    const closeDeletePopup = (e) => {
        event.preventDefault()
        document.getElementById('delete-popup').style.display = 'none';
    }
</script>