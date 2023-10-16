<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee Login</title>
</head>
<body>
<form name="loginform" id="loginform" action="" method="post">
    <p>
        <label for="user_login">Username or Email Address:</label>
        <input type="text" name="log" id="user_login" class="input" value="" size="20" autocapitalize="off" />
    </p>
    <p>
        <label for="user_pass">Password:</label>
        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" />
    </p>
    <p class="submit">
        <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Log In" />
    </p>
</form>
<div id="login-result-message">
    <?php do_action('wp_login_failed'); ?>
</div>

</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_text_field($_POST['log']);
    $password = $_POST['pwd'];

    $user = wp_signon(array(
        'user_login' => $username,
        'user_password' => $password,
    ));

    if (is_wp_error($user)) {
        echo '<div class="error"><p>Wrong credentials. Please try again.</p></div>';
        include 'pages/login.php'; // Display the login form again
        exit;
    } else {
        // Redirect to the employee dashboard after successful login
        wp_redirect('/wordpress/employee-dashboard/');
        exit;
    }
}


?>