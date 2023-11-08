<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    #createEmployeeFormContainer {
        display: none;
    }
</style>

<body>
    <button onclick="showCreateForm()">Add + </button>
    <div id="createEmployeeFormContainer">
        <form id="employee-registration-form" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <div>
                <input type="submit" name="submit" value="Create Employee">
                <button onclick="hideCreateForm()">close</button>
            </div>
        </form>
    </div>

</body>

</html>
<?php
if (isset($_POST['submit'])) {
    $username = sanitize_user($_POST['username']);
    $password = $_POST['password'];
    $email = sanitize_email($_POST['email']);

    if (username_exists($username)) {
        echo '<p class="error-message">Username already exists. Please choose a different one.</p>';
        return;
    }

    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        echo '<p class="error-message">Error creating employee: ' . $user_id->get_error_message() . '</p>';
    } else {
        echo '<p class="success-message">Employee created successfully!</p>';
    }
}


?>
<script>
    const showCreateForm = () => {
        const createEmployeeFormContainer = document.getElementById('createEmployeeFormContainer');
        createEmployeeFormContainer.style.display = 'flex'

    }
    const hideCreateForm = () => {
        const createEmployeeFormContainer = document.getElementById('createEmployeeFormContainer');
        createEmployeeFormContainer.style.display = 'none'

    }
</script> -->
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
        border: 1px solid #213159;
        display: flex;
        justify-content: center;
        align-items: center;
        border-bottom: none;
        border-top: none;
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
</style>

<body>
    <button class="create-btn" onclick="showForm()">Create Employee ➡</button>
    <div id="createCertificateForm">
        <div>
            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>

                <div>
                    <button onclick="hideForm()">Close</button>
                    <button type="submit" name="create_employee" value="Add Data">Create Certificate</button>
                </div>
            </form>
        </div>
    </div>
    <div class="certificate-table-head">
        <p>ID</p>
        <p>Name</p>
        <p>Email</p>
        <p>Created Date</p>
        <p>Created Time</p>
        <!-- <p>Status</p> -->
        <p>Role</p>
        <p>Edit</p>
        <p>Delete</p>
    </div>


</body>

</html>
<?php


function showEmployeeList() 
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'users';

    $query = "SELECT * FROM $table_name";

    $results = $wpdb->get_results($query);



    if ($results) {

        foreach ($results as $row) {
            echo '<div id="certificateList" >';
            echo '<p>' . $row->ID . '</p>';

            $name = $row->display_name;
            if (strlen($name) > 10) {
                $displayName = substr($name, 0, 15) . '...';
            } else {
                $displayName = $name;
            }
            echo '<p>' . $displayName . '</p>';

            $email = $row->user_email;
            if (strlen($email) > 12) {
                $displayEmail = substr($email, 0, 15) . '...';
            } else {
                $displayEmail = $email;
            }
            echo '<p>' . $displayEmail . '</p>';



            $userRegisteredTimestamp = strtotime($row->user_registered);
            $dateOnly = date('Y-m-d', $userRegisteredTimestamp);

            echo '<p>' . $dateOnly . '</p>';



            $userRegisteredTimestamp = strtotime($row->user_registered);
            $timeOnly = date('H:i:s', $userRegisteredTimestamp);

            echo '<p>' . $timeOnly . '</p>';

            $statusClass = ($row->status == 1) ? 'active-certificate' : 'inactive-certificate';
            // echo '<p > <button class="' . $statusClass . '" >' . ($row->status == 1 ? 'Active' : 'Inactive') . '</button></p>';



            $user = get_userdata($row->ID);

            if ($user && in_array('administrator', $user->roles)) {

                echo '<p>Administrator</p>';
            } else {

                echo '<p>User</p>';
            }

            echo '<div><form method="post">';
            echo '<input type="hidden" name="edit_employee" value="' . esc_attr($row->ID) . '">';
            echo '<button type="submit">🖊️</button>';
            echo '</form></div>';

            if (!$user || !in_array('administrator', $user->roles)) {
                // Only render the delete button if the user is not an administrator
                echo '<div><form method="post">';
                echo '<input type="hidden" name="delete_employee" value="' . esc_attr($row->ID) . '">';
                echo '<button type="submit">❌</button>';
                echo '</form></div>';
            }


            echo '</div>';
        }
    }
}
if (isset($_POST['toggle_certificate_status'])) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'users';

    $certificate_id = intval($_POST['toggle_certificate_status']);

    $current_status = $wpdb->get_var("SELECT status FROM $table_name WHERE ID = $certificate_id");

    $new_status = ($current_status == 0) ? 1 : 0;

    $wpdb->update(
        $table_name,
        array('status' => $new_status),
        array('id' => $certificate_id)
    );
}

if (isset($_POST['create_employee'])) {

    $username = sanitize_user($_POST['username']);
    $password = $_POST['password'];
    $email = sanitize_email($_POST['email']);

    if (username_exists($username)) {
        echo '<p class="error-message">Username already exists. Please choose a different one.</p>';
        return;
    }

    $user_id = wp_create_user($username, $password, $email);


    // if ($wpdb->insert_id) {
    //     echo "<p class='success-alert' >Certificate data inserted successfully!</p>";
    // } else {
    //     echo "<p class='error-alert' >Error: Certificate data insertion failed.</p>";
    // }
}

if (isset($_POST['delete_employee'])) {

    $delete_employee = intval($_POST['delete_employee']);

    echo '<div id="delete-popup" class="pop-up-from" >';
    echo '<div>';
    echo '<p>Are you sure you want to detete the certificate ?</p>';
    echo '<form method="post" action="">';
    echo '<input style="width: 100%; padding:10px;" type="hidden" name="delete_employee" value="' . esc_attr($delete_employee) . '">';
    echo '<div>';
    echo '<button class="deleteBtn" type="submit" name="sure_detete_certificate">Delete</button>';
    echo '<button onclick="closeDeletePopup()">Cancel</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
}


if (isset($_POST['update_user_details'])) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'users';

    $user_id = intval($_POST['employee_id']);
    $edit_user_login = sanitize_text_field($_POST['edit_user_login']);
    $edit_user_email = sanitize_email($_POST['edit_user_email']);

    if ($user_id > 0) {
        $wpdb->update(
            $table_name,
            array('user_login' => $edit_user_login, 'display_name' => $edit_user_login, 'user_email' => $edit_user_email),
            array('id' => $user_id)
        );
    }
}

if (isset($_POST['edit_employee'])) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'users';

    $employee_id = intval($_POST['edit_employee']);

    if ($employee_id > 0) {
        $user = get_userdata($employee_id);

        if ($user) {
            echo '<div id="edit-popup" class="pop-up-from">';
            echo '<div style="margin-top:15%;">';
            echo '<h2>Edit Employee</h2>';
            echo '<form method="post" action="">';
            echo '<input type="hidden" name="employee_id" value="' . esc_attr($employee_id) . '">';
            echo '<label for="edit_user_login">Username:</label>';
            echo '<input type="text" name="edit_user_login" id="edit_user_login" value="' . esc_attr($user->user_login) . '"><br>';
            echo '<label for="edit_user_email">Email:</label>';
            echo '<input type="text" name="edit_user_email" id="edit_user_email" value="' . esc_attr($user->user_email) . '"><br>';
            echo '<div>';
            echo '<button onclick="closeEditPopup()">Cancel</button>';
            echo '<button type="submit" name="update_user_details">Update</button>';
            echo '</div>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
    }
}

if (isset($_POST['sure_detete_certificate'])) {

    global $wpdb;

    $employee_id_to_delete = intval($_POST['delete_employee']);


    $table_name = $wpdb->prefix . 'users';

    if ($employee_id_to_delete > 0) {
        $wpdb->delete($table_name, array('id' => $employee_id_to_delete));
    }

    echo "<script>document.getElementById('delete-popup').style.display = 'none';</script>";
}

showEmployeeList();

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