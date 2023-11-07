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

    h2 {
        text-align: center;
        padding: 10px;
        background-color: #0073E6;
        color: white;
    }

    .profileImg {
        width: 100px;
        border-radius: 50%;
        position: absolute;
        right: 20px;
        top: 150px;
        cursor: pointer;
    }
</style>

<body>
    <?php

    function displayCertificates()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'certificate_details';


        $sql = "SELECT * FROM $table_name";
        $certificates = $wpdb->get_results($sql, ARRAY_A);

        if ($certificates) {
            foreach ($certificates as $certificate) {
                echo '<tr>';
                echo '<td>' . esc_html($certificate['name']) . '</td>';
                echo '<td>' . esc_html($certificate['duration_in_days']) . '</td>';
                echo '<td>' . esc_html($certificate['description']) . '</td>';
                echo '<td>Apply</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="3">No certificates found.</td></tr>';
        }
    }
    session_start();
    $userData = $_SESSION['userData'];
    echo "<h2>Employee Dashboard,,, Hello " . $userData['name'] . " have a good day !!</h1>";

    ?>

    <button onclick="showProfile()">
        <img class="profileImg" src="https://img.freepik.com/premium-photo/blue-circle-with-man-s-head-circle-with-white-background_745528-3499.jpg" alt="">
    </button>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Duration (In days)</th>
                <th>Description</th>
                <th>Apply</th>
            </tr>
        </thead>
        <tbody>
            <?php displayCertificates();
            ?>
        </tbody>
    </table>

</body>

</html>
<script>
    const showProfile = () => {
        console.log('hello')
    }
</script>