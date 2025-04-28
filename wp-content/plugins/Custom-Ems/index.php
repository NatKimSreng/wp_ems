<?php
/*
Plugin Name: Custom EMS
Description: A custom plugin to create a database table for employee management with search functionality.
Version: 1.4
Author: Your Name
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Start output buffering to catch any unintended output
ob_start();

// Register shortcode
add_shortcode('custom_ems', 'show_on_page');

// Register activation hook
register_activation_hook(__FILE__, 'my_plugin_activate');

function my_plugin_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
        emp_name VARCHAR(255) NOT NULL,
        emp_email VARCHAR(255) NOT NULL,
        emp_phone VARCHAR(255) NOT NULL,
        emp_address VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) === $table_name) {
        error_log("Custom EMS: Table $table_name created or already exists.");
    } else {
        error_log("Custom EMS: Failed to create table $table_name. SQL: $sql. Error: " . $wpdb->last_error);
    }
}

// Helper function to check table existence
function my_plugin_check_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';
    if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) !== $table_name) {
        my_plugin_activate();
        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) !== $table_name) {
            return false;
        }
        error_log("Custom EMS: Table $table_name created during check.");
    }
    return true;
}

// Enqueue styles and scripts
function my_plugin_enqueue_styles($hook) {
    wp_enqueue_style(
        'my-plugin-employee-table',
        plugin_dir_url(__FILE__) . './css/my-plugin.css',
        [],
        '1.0.2' // Increment version for cache busting
    );
}
add_action('wp_enqueue_scripts', 'my_plugin_enqueue_styles'); // For front-end
add_action('admin_enqueue_scripts', 'my_plugin_enqueue_styles'); // For admin

function my_plugin_enqueue_scripts() {
    wp_enqueue_script(
        'my-plugin-ajax',
        plugin_dir_url(__FILE__) . './js/my-plugin.js',
        ['jquery'],
        '1.0.0',
        true
    );
    wp_localize_script(
        'my-plugin-ajax',
        'myPluginAjax',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('my_plugin_search_nonce'),
        ]
    );
}
add_action('wp_enqueue_scripts', 'my_plugin_enqueue_scripts');

// Add admin menu
function my_plugin_add_menu() {
    add_menu_page(
        'Custom EMS',
        'Custom EMS',
        'manage_options',
        'custom-ems',
        'my_plugin_page',
        'dashicons-admin-tools'
    );
    add_submenu_page(
        'custom-ems',
        'Employee List',
        'Employee List',
        'manage_options',
        'employee-list',
        'my_plugin_employee_list'
    );
    add_submenu_page(
        'custom-ems',
        'Add Employee',
        'Add Employee',
        'manage_options',
        'add-employee',
        'my_plugin_add_employee'
    );
    add_submenu_page(
        null,
        'Edit Employee',
        'Edit Employee',
        'manage_options',
        'edit-employee',
        'my_plugin_edit_employee'
    );
    add_submenu_page(
        null,
        'Delete Employee',
        'Delete Employee',
        'manage_options',
        'delete-employee',
        'my_plugin_delete_employee'
    );
    add_submenu_page(
        'custom-ems',
        'Employee List shortcode',
        'Employee List shortcode',
        'manage_options',
        'emp-shortcode',
        'my_plugin_shortcode_page'
    );
}
add_action('admin_menu', 'my_plugin_add_menu');

// Shortcode page
function my_plugin_shortcode_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }

    ?>
    <div class="wrap custom-ems">
        <h1>Custom EMS Shortcode</h1>
        <input type="text" value="[custom_ems]" readonly style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;" />
        <p>Use the above shortcode in any post or page to display the employee list.</p>
        <h2>Shortcode Usage</h2>
        <p>To use the shortcode, simply copy and paste the following code into your post or page:</p>
    </div>
    <?php
}

// Main plugin page
function my_plugin_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }

    ?>
    <div class="wrap custom-ems">
        <h1>Custom EMS</h1>
        <p>Welcome to the Custom EMS plugin!</p>
    </div>
    <?php
}

// Employee list page
function my_plugin_employee_list() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';

    if (!my_plugin_check_table()) {
        echo '<div class="error"><p>Error: Table does not exist. Please reactivate the plugin or contact support.</p></div>';
        return;
    }

    // Handle search query
    $search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $query = "SELECT * FROM $table_name";
    if (!empty($search_term)) {
        $search_term = '%' . $wpdb->esc_like($search_term) . '%';
        $query .= $wpdb->prepare(
            " WHERE emp_name LIKE %s OR emp_email LIKE %s OR emp_phone LIKE %s OR emp_address LIKE %s",
            $search_term,
            $search_term,
            $search_term,
            $search_term
        );
    }
    $employees = $wpdb->get_results($query);

    ?>
    <div class="wrap">
        <h1>Employee List</h1>
        <!-- Search Form -->
        <form method="get" class="ems-search-form">
            <input type="hidden" name="page" value="employee-list" />
            <input type="search" name="s" value="<?php echo esc_attr($search_term); ?>" placeholder="Search employees..." class="regular-text" />
            <input type="submit" class="button" value="Search" />
            <?php if (!empty($search_term)) : ?>
                <a href="<?php echo esc_url(admin_url('admin.php?page=employee-list')); ?>" class="button">Clear Search</a>
            <?php endif; ?>
        </form>
        <?php if ($employees) : ?>
            <table class="ems-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee) : ?>
                        <tr>
                            <td><?php echo esc_html($employee->id); ?></td>
                            <td><?php echo esc_html($employee->emp_name); ?></td>
                            <td><?php echo esc_html($employee->emp_email); ?></td>
                            <td><?php echo esc_html($employee->emp_phone); ?></td>
                            <td><?php echo esc_html($employee->emp_address); ?></td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=edit-employee&employee_id=' . $employee->id)); ?>" class="button">Edit</a>
                                <?php
                                $delete_url = wp_nonce_url(
                                    admin_url('admin.php?page=delete-employee&employee_id=' . $employee->id),
                                    'delete_employee_' . $employee->id
                                );
                                ?>
                                <a href="<?php echo esc_url($delete_url); ?>" class="button button-link-delete" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No employees found.</p>
        <?php endif; ?>
    </div>
    <?php
}

// Render employee form
function my_plugin_render_employee_form($employee = null, $errors = [], $success = '', $is_edit = false) {
    ?>
    <div class="wrap">
        <h1><?php echo $is_edit ? 'Edit Employee' : 'Add Employee'; ?></h1>
        <?php if ($success) : ?>
            <div class="updated"><p><?php echo esc_html($success); ?></p></div>
        <?php endif; ?>
        <?php if ($errors) : ?>
            <div class="error">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo esc_html($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <?php wp_nonce_field($is_edit ? 'edit_employee_nonce' : 'add_employee_nonce', 'nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="emp_name">Employee Name</label></th>
                    <td><input type="text" name="emp_name" id="emp_name" value="<?php echo esc_attr($employee->emp_name ?? ''); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="emp_email">Employee Email</label></th>
                    <td><input type="email" name="emp_email" id="emp_email" value="<?php echo esc_attr($employee->emp_email ?? ''); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="emp_phone">Employee Phone</label></th>
                    <td><input type="text" name="emp_phone" id="emp_phone" value="<?php echo esc_attr($employee->emp_phone ?? ''); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="emp_address">Employee Address</label></th>
                    <td><input type="text" name="emp_address" id="emp_address" value="<?php echo esc_attr($employee->emp_address ?? ''); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <?php submit_button($is_edit ? 'Update Employee' : 'Add Employee'); ?>
        </form>
    </div>
    <?php
}

// Add employee page
function my_plugin_add_employee() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';
    $errors = [];
    $success = '';

    // Process form submission before any output
    if (isset($_POST['submit']) && check_admin_referer('add_employee_nonce', 'nonce')) {
        $emp_name = sanitize_text_field($_POST['emp_name'] ?? '');
        $emp_email = sanitize_email($_POST['emp_email'] ?? '');
        $emp_phone = sanitize_text_field($_POST['emp_phone'] ?? '');
        $emp_address = sanitize_text_field($_POST['emp_address'] ?? '');

        if (empty($emp_name)) {
            $errors[] = 'Employee name is required.';
        }
        if (empty($emp_email) || !is_email($emp_email)) {
            $errors[] = 'Valid employee email is required.';
        }
        if (empty($emp_phone)) {
            $errors[] = 'Employee phone is required.';
        }
        if (!preg_match('/^[0-9+\-\(\) ]{7,20}$/', $emp_phone)) {
            $errors[] = 'Invalid phone number format.';
        }
        if (empty($emp_address)) {
            $errors[] = 'Employee address is required.';
        }

        if (empty($errors) && my_plugin_check_table()) {
            $result = $wpdb->insert(
                $table_name,
                [
                    'emp_name' => $emp_name,
                    'emp_email' => $emp_email,
                    'emp_phone' => $emp_phone,
                    'emp_address' => $emp_address,
                ],
                ['%s', '%s', '%s', '%s']
            );
            if ($result !== false) {
                // Clean output buffer and redirect
                ob_end_clean();
                wp_redirect(admin_url('admin.php?page=employee-list'));
                exit;
            } else {
                $errors[] = 'Error adding employee: ' . $wpdb->last_error;
            }
        } elseif (!my_plugin_check_table()) {
            $errors[] = 'Error: Table does not exist and could not be created. Please reactivate the plugin or check debug logs.';
        }
    }

    // Render form only if no redirect
    my_plugin_render_employee_form(null, $errors, $success, false);

    // Clean output buffer
    ob_end_flush();
}

// Edit employee page
function my_plugin_edit_employee() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';
    $errors = [];
    $success = '';
    $employee = null;

    // Validate employee ID
    if (!isset($_GET['employee_id']) || !is_numeric($_GET['employee_id'])) {
        wp_die('Invalid employee ID.');
    }

    $employee_id = intval($_GET['employee_id']);
    if (my_plugin_check_table()) {
        $employee = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $employee_id));
        if (!$employee) {
            wp_die('Employee not found.');
        }
    } else {
        $errors[] = 'Error: Table does not exist and could not be created. Please reactivate the plugin or check debug logs.';
    }

    // Process form submission before any output
    if (isset($_POST['submit']) && check_admin_referer('edit_employee_nonce', 'nonce')) {
        $emp_name = sanitize_text_field($_POST['emp_name'] ?? '');
        $emp_email = sanitize_email($_POST['emp_email'] ?? '');
        $emp_phone = sanitize_text_field($_POST['emp_phone'] ?? '');
        $emp_address = sanitize_text_field($_POST['emp_address'] ?? '');

        if (empty($emp_name)) {
            $errors[] = 'Employee name is required.';
        }
        if (empty($emp_email) || !is_email($emp_email)) {
            $errors[] = 'Valid employee email is required.';
        }
        if (empty($emp_phone)) {
            $errors[] = 'Employee phone is required.';
        }
        if (!preg_match('/^[0-9+\-\(\) ]{7,20}$/', $emp_phone)) {
            $errors[] = 'Invalid phone number format.';
        }
        if (empty($emp_address)) {
            $errors[] = 'Employee address is required.';
        }

        if (empty($errors)) {
            $result = $wpdb->update(
                $table_name,
                [
                    'emp_name' => $emp_name,
                    'emp_email' => $emp_email,
                    'emp_phone' => $emp_phone,
                    'emp_address' => $emp_address,
                ],
                ['id' => $employee_id],
                ['%s', '%s', '%s', '%s'],
                ['%d']
            );
            if ($result !== false) {
                // Clean output buffer and redirect
                ob_end_clean();
                wp_redirect(admin_url('admin.php?page=employee-list'));
                exit;
            } else {
                $errors[] = 'Error updating employee: ' . $wpdb->last_error;
            }
        }
    }

    // Render form only if no redirect
    my_plugin_render_employee_form($employee, $errors, $success, true);

    // Clean output buffer
    ob_end_flush();
}

// Delete employee page
function my_plugin_delete_employee() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';

    if (!my_plugin_check_table()) {
        wp_die('Error: Table does not exist. Please reactivate the plugin or contact support.');
    }

    if (!isset($_GET['employee_id']) || !is_numeric($_GET['employee_id'])) {
        wp_die('Invalid employee ID.');
    }

    $employee_id = intval($_GET['employee_id']);
    check_admin_referer('delete_employee_' . $employee_id);

    $result = $wpdb->delete($table_name, ['id' => $employee_id], ['%d']);
    if ($result !== false) {
        wp_redirect(admin_url('admin.php?page=employee-list'));
        exit;
    } else {
        wp_die('Error deleting employee: ' . $wpdb->last_error);
    }
}

// AJAX handler for search
function my_plugin_ajax_search() {
    check_ajax_referer('my_plugin_search_nonce', 'nonce');

    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';
    $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';

    if (!my_plugin_check_table()) {
        wp_send_json_error(['message' => 'Table does not exist.']);
    }

    $query = "SELECT * FROM $table_name";
    if (!empty($search_term)) {
        $search_term = '%' . $wpdb->esc_like($search_term) . '%';
        $query .= $wpdb->prepare(
            " WHERE emp_name LIKE %s OR emp_email LIKE %s OR emp_phone LIKE %s OR emp_address LIKE %s",
            $search_term,
            $search_term,
            $search_term,
            $search_term
        );
    }
    $employees = $wpdb->get_results($query);

    ob_start();
    if ($employees) {
        ?>
        <table class="ems-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee) : ?>
                    <tr>
                        <td><?php echo esc_html($employee->id); ?></td>
                        <td><?php echo esc_html($employee->emp_name); ?></td>
                        <td><?php echo esc_html($employee->emp_email); ?></td>
                        <td><?php echo esc_html($employee->emp_phone); ?></td>
                        <td><?php echo esc_html($employee->emp_address); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    } else {
        echo '<p>No employees found.</p>';
    }
    $output = ob_get_clean();
    wp_send_json_success(['html' => $output]);
}
add_action('wp_ajax_my_plugin_search', 'my_plugin_ajax_search');
add_action('wp_ajax_nopriv_my_plugin_search', 'my_plugin_ajax_search');

// Shortcode output with search
function show_on_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';

    if (!my_plugin_check_table()) {
        return '<div class="error"><p>Error: Table does not exist. Please reactivate the plugin or contact support.</p></div>';
    }

    // Handle search query for non-AJAX fallback
    $search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $query = "SELECT * FROM $table_name";
    if (!empty($search_term)) {
        $search_term = '%' . $wpdb->esc_like($search_term) . '%';
        $query .= $wpdb->prepare(
            " WHERE emp_name LIKE %s OR emp_email LIKE %s OR emp_phone LIKE %s OR emp_address LIKE %s",
            $search_term,
            $search_term,
            $search_term,
            $search_term
        );
    }
    $employees = $wpdb->get_results($query);

    ob_start();
    ?>
    <div class="wrap">
        <h1>Employee List</h1>
        <!-- Search Form -->
        <form method="get" class="ems-search-form" onsubmit="return false;">
            <input type="search" name="s" value="<?php echo esc_attr($search_term); ?>" placeholder="Search employees..." class="regular-text" aria-label="Search employees" />
            <input type="submit" class="button" value="Search" />
            <?php if (!empty($search_term)) : ?>
                <a href="<?php echo esc_url(get_permalink()); ?>" class="button">Clear Search</a>
            <?php endif; ?>
        </form>
        <!-- Results Container -->
        <div id="employee-results" aria-live="polite">
            <?php if ($employees) : ?>
                <table class="ems-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee) : ?>
                            <tr>
                                <td><?php echo esc_html($employee->id); ?></td>
                                <td><?php echo esc_html($employee->emp_name); ?></td>
                                <td><?php echo esc_html($employee->emp_email); ?></td>
                                <td><?php echo esc_html($employee->emp_phone); ?></td>
                                <td><?php echo esc_html($employee->emp_address); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No employees found.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}