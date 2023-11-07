<style>
    #alert {
        background-color: green;
        color: white;
        position: absolute;
        top: 68px;
        right: 10px;
        padding: 10px;

    }

    #alert button {
        background: none;
        color: #fff;
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
    }

    .success-alert {
        background-color: green;
    }


    .entry-content form {
        max-width: 500px;
        margin: 0 auto;
        padding: 30px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 5px;
    }


    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }


    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 16px;
    }

    .entry-content form select {
        max-width: 100%;
        padding: 10px;
    }

    input[type="submit"] {
        background-color: #0073e6;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 18px;
        border-radius: 3px;
        cursor: pointer;
        width: 100%;
    }

    input[type="submit"]:hover {
        background-color: #0058a8;
    }
</style>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title">Adding New Employee</h1>
            </header>

            <div class="entry-content">
                <form method="post" action="">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required><br>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br>

                    <label for="designation">Designation:</label>
                    <select id="designation" name="designation" required>
                        <option value="">Select</option>
                        <option value="trainee">Trainee</option>
                        <option value="juniorDeveloper">Junior Developer</option>
                        <option value="seniorDeveloper">Senior Developer</option>
                        <option value="projectManager">Project Manager</option>
                    </select><br>

                    <input type="submit" name="submit_data" value="Add Data">
                </form>

                <?php
                if (isset($_POST['submit_data'])) {
                    global $wpdb;
                    $table_name = 'wp_employee_management_table';
                    $name = sanitize_text_field($_POST['name']);
                    $email = sanitize_email($_POST['email']);
                    $designation = sanitize_text_field($_POST['designation']);
                    $password = sanitize_text_field($_POST['password']);

                    $wpdb->insert(
                        $table_name,
                        array(
                            'name' => $name,
                            'email' => $email,
                            'password' => $password,
                            'designation' => $designation,
                        ),
                        array(
                            '%s',
                            '%s',
                            '%s',
                            '%s'
                        )
                    );

                    echo '<div id="alert" class="success-alert" >A new employee has been added successfully!! <button onclick="closeEditPopup()" >&times;</button> </div>';
                }
                ?>
            </div>
        </article>
    </main>
</div>
<script>
    function closeEditPopup() {
        document.getElementById('alert').style.display = 'none';
    }
</script>