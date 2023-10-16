<?php
/*
Plugin Name: Employee Management System
Description: This is the plugin we can manage the employee
Author: Prakash Kumar Jena
Version: 1.0
*/

show_admin_bar(false);

register_activation_hook(__FILE__, 'create_employee_page');
register_deactivation_hook(__FILE__, 'delete_page');


add_shortcode('display_login', 'display_login_func');
add_shortcode('display_employee_dashboard', 'display_employee_dashboard_func');


add_action('wp_enqueue_scripts', 'enqueue_login_css');
add_action('admin_enqueue_scripts', 'enqueue_manage_employee_styles');
add_action('admin_menu', 'custom_admin_menu');


function create_employee_page()
{
    if (!get_page_by_title('Employee Login')) {
        // Create a new page
        $employee_login_page = array(
            'post_title' => 'Employee Login',
            'post_content' => '[display_login]',
            'post_status' => 'publish',
            'post_type' => 'page',
        );
        wp_insert_post($employee_login_page);
    }

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
    $employee_login_page = get_page_by_title('Employee Login');
    $employee_dashboard_page = get_page_by_title('Employee Dashboard');


    if ($employee_login_page) {
        wp_delete_post($employee_login_page->ID, true);
    }
    if ($employee_dashboard_page) {
        wp_delete_post($employee_dashboard_page->ID, true);
    }
}

function display_login_func()
{
    include 'pages/login.php';
}
function display_employee_dashboard_func()
{
    include 'pages/employeeDashboard.php';
}


function enqueue_login_css()
{
    wp_enqueue_style('login-styles', plugin_dir_url(__FILE__) . 'css/login.css');
}

function enqueue_manage_employee_styles() {
    $stylesheet_path = plugin_dir_url(__FILE__) . 'css/manageEmployee.css';

    wp_enqueue_style('manage-employee-styles', $stylesheet_path);
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
    echo '<h2>Custom Menu Page Content</h2>';
}

function manage_employee_callback()
{
    include "admin/manageEmployee.php";
}

function manage_certificate_callback()
{
    include "admin/manageCertificate.php";
}

register_activation_hook(__FILE__, 'cepl_activate');

function cepl_activate() {
    // Add a custom function to prevent access to the employee dashboard
    add_action('admin_init', 'cepl_redirect_dashboard');
}

function cepl_redirect_dashboard() {
    // Check if the current user is logged in
    if (!is_user_logged_in()) {
        // If not logged in, display the custom login form
        include 'login-template.php'; // Update this path to the actual path of your login template
        exit;
    }
}


?>