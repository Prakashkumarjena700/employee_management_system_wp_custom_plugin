<?php
/*
Plugin Name: Employee Management System
Description: This is the plugin we can manage the employee
Author: Prakash Kumar Jena
Version: 1.0
*/

show_admin_bar(false);

register_activation_hook(__FILE__, 'create_employee_page');
register_activation_hook(__FILE__, 'create_tables_activation');


register_deactivation_hook(__FILE__, 'delete_page');
register_deactivation_hook(__FILE__, 'delete_tables_on_deactivation');



add_shortcode('display_employee_dashboard', 'display_employee_dashboard_func');


add_action('admin_menu', 'custom_admin_menu');


function create_employee_page()
{

    if (!get_page_by_title('Employee Dashboard')) {
        $employee_dashboard_page = array(
            'post_title' => 'Employee Dashboard',
            'post_content' => '[display_employee_dashboard]',
            'post_status' => 'publish',
            'post_type' => 'page',
        );
        wp_insert_post($employee_dashboard_page);
    }
}

function delete_page()
{
    $employee_dashboard_page = get_page_by_title('Employee Dashboard');

    if ($employee_dashboard_page) {
        wp_delete_post($employee_dashboard_page->ID, true);
    }
}

function create_tables_activation()
{
    global $wpdb;

    $certificate_table_name = $wpdb->prefix . 'certificate';
    $certificate_holder_table_name = $wpdb->prefix . 'certificate_holder';

    $certificate_sql = "CREATE TABLE $certificate_table_name (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(50),
        description TEXT,
        validity_in_days INT,
        created DATE,
        status BOOLEAN DEFAULT TRUE,
        PRIMARY KEY (id)
    )";

    $certificate_holder_sql = "CREATE TABLE $certificate_holder_table_name (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(50),
        employee_id INT,
        certificate_id INT,
        certificate_name VARCHAR(50),
        validity_in_days INT,
        apply_date DATE,
        start_date DATE,
        end_date DATE,
        status VARCHAR(50),
        PRIMARY KEY (id)
    )";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($certificate_sql);
    dbDelta($certificate_holder_sql);
}

function delete_tables_on_deactivation()
{
    global $wpdb;

    $certificate_table_name = $wpdb->prefix . 'certificate';
    $certificate_holder_table_name = $wpdb->prefix . 'certificate_holder';

    $delete_certificate_sql = "DROP TABLE IF EXISTS $certificate_table_name";
    $delete_certificate_holder_sql = "DROP TABLE IF EXISTS $certificate_holder_table_name";

    $wpdb->query($delete_certificate_sql);
    $wpdb->query($delete_certificate_holder_sql);
}


function display_employee_dashboard_func()
{
    include 'pages/employee-dashboard.php';
}


function custom_admin_menu()
{
    add_menu_page(
        'Employees',
        'Employees',
        'manage_options',
        'custom-menu',
        'employees_menu_callback',
        'dashicons-admin-generic',
        30
    );

    add_submenu_page(
        'custom-menu',
        'Manage Employees',
        'Manage Employees',
        'manage_options',
        'submenu-option-1',
        'manage_employee_callback'
    );

    add_submenu_page(
        'custom-menu',
        'Manage Certificate',
        'Manage Certificate',
        'manage_options',
        'submenu-option-2',
        'manage_certificate_callback'
    );
}

function employees_menu_callback()
{
    include "admin/admin-main.php";
}

function manage_employee_callback()
{
    include "admin/manage-employee.php";
}

function manage_certificate_callback()
{
    include "admin/manage-certificate.php";
}

function enqueue_employee_dashboard_styles()
{
    wp_enqueue_style('employee-dashboard', plugins_url('/css/employee-dashboard.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'enqueue_employee_dashboard_styles');
