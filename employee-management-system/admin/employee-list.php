<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #0073e6;
        color: #fff;
    }

    td {
        border-bottom: 1px solid #ddd;
    }

    .delete-button,
    .edit-button {
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .delete-button:hover,
    .edit-button:hover {
        background-color: #c0392b;
    }

    #edit-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    .popup-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .popup-content h2 {
        margin-bottom: 10px;
    }

    .popup-content input[type="text"],
    .popup-content input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 16px;
    }

    .popup-content button {
        background-color: #0073e6;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 18px;
        border-radius: 3px;
        cursor: pointer;
    }

    .popup-content button:hover {
        background-color: #0058a8;
    }
</style>

<body>
    <?php
    global $wpdb;

    $table_name = $wpdb->prefix . 'employee_management_table';

    if (isset($_POST['delete_employee'])) {
        $employee_id_to_delete = intval($_POST['delete_employee']);

        if ($employee_id_to_delete > 0) {
            $wpdb->delete($table_name, array('id' => $employee_id_to_delete));
        }
    }

    if (isset($_POST['edit_employee'])) {
        $employee_id_to_edit = intval($_POST['edit_employee']);

        if ($employee_id_to_edit > 0) {

            $employee = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $employee_id_to_edit));

            if ($employee) {
                echo '<div id="edit-popup" style="display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">';
                echo '<div style="width: 500px; padding: 20px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.5);">';
                echo '<h2>Edit Employee</h2>';

                echo '<form method="post" action="">';
                echo '<input style="width: 100%; padding:10px;" type="hidden" name="employee_id_to_edit" value="' . esc_attr($employee_id_to_edit) . '">';
                echo '<label style="display: block; margin-top: 10px; font-weight: 700;" for="edit_name">Name:</label>';
                echo '<input style="width: 100%; padding:10px;" type="text" name="edit_name" id="edit_name" value="' . esc_attr($employee->name) . '"><br>';
                echo '<label style="display: block; margin-top: 10px; font-weight: 700;" for="edit_email">Email:</label>';
                echo '<input style="width: 100%; padding:10px;" type="text" name="edit_email" id="edit_email" value="' . esc_attr($employee->email) . '"><br>';
                echo '<label style="display: block; margin-top: 10px; font-weight: 700;" for="designation">Designation:</label>';
                echo '<select style="max-width: 100%; padding:10px;" id="designation" name="designation" required>';
                echo '<option value="">Select</option>';
                echo '<option value="trainee" ' . ($employee->designation === 'trainee' ? 'selected' : '') . '>Trainee</option>';
                echo '<option value="juniorDeveloper" ' . ($employee->designation === 'juniorDeveloper' ? 'selected' : '') . '>Junior Developer</option>';
                echo '<option value="seniorDeveloper" ' . ($employee->designation === 'seniorDeveloper' ? 'selected' : '') . '>Senior Developer</option>';
                echo '<option value="projectManager" ' . ($employee->designation === 'projectManager' ? 'selected' : '') . '>Project Manager</option>';
                echo '</select><br>';
                echo '<button style="background-color: #0058a8; color: white; width: 100%; margin-top: 10px; padding:10px;" type="submit" name="update_employee">Update</button>';
                echo '</form>';

                echo '<button style="background-color: #0058a8; color: white; width: 100%; margin-top: 10px; padding:10px;" onclick="closeEditPopup()">Cancel</button>';
                echo '</div>';

                echo '</div>';
            }
        }
    }

    if (isset($_POST['update_employee'])) {
        $employee_id_to_update = intval($_POST['employee_id_to_edit']);
        $new_name = sanitize_text_field($_POST['edit_name']);
        $new_email = sanitize_text_field($_POST['edit_email']);
        $designation = sanitize_text_field($_POST['designation']);


        if ($employee_id_to_update > 0) {
            $wpdb->update(
                $table_name,
                array('name' => $new_name, 'email' => $new_email, 'designation' => $designation),
                array('id' => $employee_id_to_update)
            );
        }
    }

    $query = "SELECT * FROM $table_name";
    $results = $wpdb->get_results($query);

    if ($results) {
        echo '<table>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Name</th>';
        echo '<th>Email</th>';
        echo '<th>Designation</th>';
        echo '<th>DELETE</th>';
        echo '<th>EDIT</th>';
        echo '</tr>';

        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row->id) . '</td>';
            echo '<td>' . esc_html($row->name) . '</td>';
            echo '<td>' . esc_html($row->email) . '</td>';

            echo '<td>' . esc_html($row->designation) . '</td>';

            echo '<td><form method="post">';
            echo '<input type="hidden" name="delete_employee" value="' . esc_attr($row->id) . '">';
            echo '<button style="border: none; " type="submit">❌</button>';
            echo '</form></td>';


            echo '<td><form method="post">';
            echo '<input type="hidden" name="edit_employee" value="' . esc_attr($row->id) . '">';
            echo '<button style="border: none; " type="submit">🖊️</button>';
            echo '</form></td>';

            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No employees found';
    }

    ?>
</body>

</html>
<script>
    function closeEditPopup() {
        document.getElementById('edit-popup').style.display = 'none';
    }
</script>