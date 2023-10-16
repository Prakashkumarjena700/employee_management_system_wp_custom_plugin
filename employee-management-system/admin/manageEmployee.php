<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <button onclick="showCreateForm()">Add + </button>
    <div id="createEmployeeFormContainer">
        <form id="employee-registration-form" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <!-- Add more fields as needed -->

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

    // Check if the username is already in use
    if (username_exists($username)) {
        echo '<p class="error-message">Username already exists. Please choose a different one.</p>';
        return;
    }

    // Create a new user
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
</script>