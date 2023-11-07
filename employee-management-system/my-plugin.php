<?php

/*
*Plugin Name: Employee Management System 
*Author: Prakash Kumar Jena
*Description: A custom plugin for managing employee data in WordPress.
*/

// show_admin_bar(false);


function my_plugin_activation()
{
  global $wpdb;

  $table_name = $wpdb->prefix . 'employee_management_table';
  $table2_name = $wpdb->prefix . 'certificate_details';

  try {
    $sql = "CREATE TABLE $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(50),
            email VARCHAR(50),
            password VARCHAR(250),
            designation VARCHAR(50),
            active BOOLEAN DEFAULT TRUE,
            PRIMARY KEY (id)
        )";

    $sql2 = "CREATE TABLE $table2_name (
              id INT NOT NULL AUTO_INCREMENT,
              name VARCHAR(50),
              duration_in_days INT, 
              description TEXT, 
              PRIMARY KEY (id)
          )";

    $sql3 = "CREATE TABLE subscriptions (
              id INT NOT NULL AUTO_INCREMENT,
              certificate VARCHAR(50),
              apply_date DATE,
              duration_in_days INT,
              total_days_left INT,  
              status ENUM('pending', 'active', 'expired') DEFAULT 'pending',
              PRIMARY KEY (id)
          )";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql2);
  } catch (Exception $e) {
    echo "Error creating table: " . $e->getMessage();
  }
}


function my_plugin_deactivation()
{
  global $wpdb;

  $table_name = 'wp_employee_management_table';
  $certificate_table = 'wp_certificate_details';

  if ($wpdb->get_var("SHOW TABLES LIKE '$certificate_table'") == $certificate_table) {
    $wpdb->query("DROP TABLE $certificate_table");
  }

  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
    $wpdb->query("DROP TABLE $table_name");
  }
}

register_activation_hook(__FILE__, 'my_plugin_activation');

register_deactivation_hook(__FILE__, 'my_plugin_deactivation');

function show_about($atts)
{

  $atts = shortcode_atts(array( #default value
    'name' => 'am',
    'msg' => 'good'
  ), $atts);

  ob_start()
?>

  <h1>Hello 123</h1>
<?php
  $html = ob_get_clean();

  return $html;


  // return 'Hello I ' . $atts['name'] . $atts['msg'];
}

add_shortcode('test_sort', 'show_about');

function my_custom_scripts()
{
  $path = plugins_url('js/main.js', __FILE__);
  $dep = array('jquery');
  $ver = fileatime(plugin_dir_path(__FILE__ . 'js/main.js'));
  $is_login = is_user_logged_in() ? 1 : 0;

  wp_enqueue_script('my-custom-js', $path, $dep, $ver, true);
  wp_add_inline_script('my-custom-js', 'var is_login = ' . $is_login . ';', 'before');
}

add_action('wp_enqueue_scripts', 'my_custom_scripts');


function show_employee_list_funct()
{
  include 'user/employee-list.php';
}

add_shortcode('show_employee_list', 'show_employee_list_funct');

function my_plugin_page_menu_function()
{
  include 'admin/main-page.php';
}
function my_plugin_page_sub_menu_all_function()
{
  include 'admin/employee-list.php';
}

function my_plugin_page_sub_menu_certificate_function()
{
  include 'admin/certificate.php';
}

function my_plugin_menue()
{
  add_menu_page('My plugin page', 'Employee', 'manage_options', 'My-plugin-page', 'my_plugin_page_menu_function', '', 6);

  add_submenu_page('My-plugin-page', 'All Employee List', 'All Employee', 'manage_options', 'my-plugin-subpage', 'my_plugin_page_sub_menu_all_function');

  add_submenu_page('My-plugin-page', 'Certificate', 'Certificate', 'manage_options', 'my-certificate-subpage', 'my_plugin_page_sub_menu_certificate_function');
}

add_action('admin_menu', 'my_plugin_menue');


# Short code to show Dashboard
function show_employee_dashboard_function()
{
  include 'user/user-dashboard.php';
}


add_shortcode('user_dashboard', 'show_employee_dashboard_function');
