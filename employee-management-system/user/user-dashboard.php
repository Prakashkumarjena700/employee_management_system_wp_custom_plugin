<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Page</title>
</head>

<body>
    <?php

    global $wpdb;

    if (isset($_POST['submit'])) {
        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);

        $table_name = $wpdb->prefix . 'employee_management_table';
        $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE email = %s AND password=%s ", $email, $password);
        $employee_data = $wpdb->get_row($sql, ARRAY_A);

        if ($employee_data) {
            $userData = array(
                "email" => $employee_data['email'],
                "name" => $employee_data['name']
            );

            $_SESSION['userData'] = $userData;
        } else {
            echo '<h2>Wrong credential</h2>';
        }
    }

    if (isset($_SESSION['userData'])) {
        include 'actual-user-dashboard.php';
    } else {
        echo '<form method="post" action="">';
        echo ' <label for="email">Username:</label>';
        echo ' <input type="email" id="email" name="email" required>';
        echo ' <br>';
        echo ' <label for="password">Password:</label>';
        echo ' <input type="password" id="password" name="password" required>';
        echo ' <br>';
        echo ' <input type="submit" name="submit" value="Login">';
        echo '</form>';
    }

    ?>
</body>

</html>