<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            background-color: #f2f2f2;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
    global $wpdb;

    $table_name = $wpdb->prefix . 'employee_management_table';

    $query = "SELECT * FROM $table_name";
    $results = $wpdb->get_results($query);

    if ($results) {
        echo '<table>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Name</th>';
        echo '<th>Email</th>';
        echo '</tr>';

        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row->id) . '</td>';
            echo '<td>' . esc_html($row->name) . '</td>';
            echo '<td>' . esc_html($row->email) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No employees found';
    }
    ?>
</body>
</html>
