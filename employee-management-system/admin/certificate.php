<h1>Certificate</h1>

<button style="padding: 10px 50px;position: absolute;right: 68px;top: 54px;color: white;background-color: blue;font-weight: 900;cursor: pointer;" onclick="showAddCertificateForm()">Add +</button>

<style>
    #addCertificateForm {
        display: none;
    }


    .addCertificateFormContainer {
        margin: auto;
        padding: 31px;
        width: 500px;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .addCertificateFormContainer label {
        display: block;
        margin-top: 10px;
        font-weight: 700;
    }

    #addCertificateForm button {
        cursor: pointer;
    }

    #addCertificateForm form {
        margin-top: 20px;
    }

    #addCertificateForm .addCertificateFormContainer button {
        border: none;
        float: right;
        font-size: 20px;
        background: none;
        margin: 10px;
    }

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
</style>

<?php
// Check if the form is submitted
if (isset($_POST['submit'])) {
    global $wpdb;

    $table2_name = $wpdb->prefix . 'certificate_details';

    // Retrieve data from the form
    $name = sanitize_text_field($_POST['name']);
    $duration_in_days = intval($_POST['duration_in_days']);
    $description = sanitize_textarea_field($_POST['description']);

    // Insert data into table2
    $wpdb->insert(
        $table2_name,
        array(
            'name' => $name,
            'duration_in_days' => $duration_in_days,
            'description' => $description,
        )
    );
    echo 'Data added successfully!';
}
?>
<div id="addCertificateForm">
    <div class="addCertificateFormContainer">
        <button onclick="hideAddCertificateFrom()">✖️</button>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input style="width: 100%; padding:10px;" type="text" name="name" id="name" required><br>

            <label for="duration_in_days">Duration (in days):</label>
            <input style="width: 100%; padding:10px;" type="number" name="duration_in_days" id="duration_in_days" required><br>

            <label for="description">Description:</label>
            <textarea style="width: 100%; padding:10px;" name="description" id="description" rows="4" required></textarea><br>

            <input style="background-color: #0058a8; color: white; width: 100%; margin-top: 10px; padding:10px; cursor: pointer;" type="submit" name="submit" value="Add Certificate">
        </form>
    </div>
</div>

<?php
global $wpdb;

$table_name = $wpdb->prefix . 'certificate_details';

if (isset($_POST['delete_employee'])) {
    $employee_id_to_delete = intval($_POST['delete_employee']);

    if ($employee_id_to_delete > 0) {
        $wpdb->delete($table_name, array('id' => $employee_id_to_delete));
    }
}

if (isset($_POST['edit_certificate'])) {
    $employee_id_to_edit = intval($_POST['edit_certificate']);

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

            echo '<label style="display: block; margin-top: 10px; font-weight: 700;" for="edit_duration_in_days">Email:</label>';
            echo '<input style="width: 100%; padding:10px;" type="number" name="edit_duration_in_days" id="edit_duration_in_days" value="' . esc_attr($employee->duration_in_days) . '"><br>';


            echo '<label style="display: block; margin-top: 10px; font-weight: 700;" for="edit_description">Description:</label>';
            echo '<textarea style="width: 100%; padding: 10px;" name="edit_description" id="edit_description">' . esc_textarea($employee->description) . '</textarea><br>';


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
    $new_duration_in_days = intval($_POST['edit_duration_in_days']);
    $new_description = sanitize_textarea_field($_POST['edit_description']);



    if ($employee_id_to_update > 0) {
        $wpdb->update(
            $table_name,
            array('name' => $new_name, 'duration_in_days' => $new_duration_in_days, 'description' => $new_description),
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
    echo '<th>Duration in Days</th>';
    echo '<th>Description</th>';
    echo '<th>DELETE</th>';
    echo '<th>EDIT</th>';
    echo '</tr>';

    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row->id) . '</td>';
        echo '<td>' . esc_html($row->name) . '</td>';
        echo '<td>' . esc_html($row->duration_in_days) . ' Days' . '</td>';

        echo '<td>' . esc_html($row->description) . '</td>';

        echo '<td><form method="post">';
        echo '<input type="hidden" name="delete_employee" value="' . esc_attr($row->id) . '">';
        echo '<button style="border: none; cursor: pointer;" type="submit">❌</button>';
        echo '</form></td>';

        echo '<td><form method="post">';
        echo '<input type="hidden" name="edit_certificate" value="' . esc_attr($row->id) . '">';
        echo '<button style="border: none; cursor: pointer;" type="submit">🖊️</button>';
        echo '</form></td>';

        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<h3>No certificate found</h3>';
}

?>
<script>
    function closeEditPopup() {
        document.getElementById('edit-popup').style.display = 'none';
    }

    const showAddCertificateForm = () => {
        const addCertificateForm = document.getElementById('addCertificateForm');
        addCertificateForm.style.display = 'flex';
        addCertificateForm.style.position = 'fixed';
        addCertificateForm.style.top = '0';
        addCertificateForm.style.left = '0';
        addCertificateForm.style.right = '0';
        addCertificateForm.style.bottom = '0';
        addCertificateForm.style.width = '100%';
        addCertificateForm.style.backgroundColor = 'rgba(0,0,0,0.5)';
    }

    const hideAddCertificateFrom = () => {
        document.getElementById('addCertificateForm').style.display = 'none';
    }
</script>