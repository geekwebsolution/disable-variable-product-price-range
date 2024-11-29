<?php

if (!defined('ABSPATH')) exit;

/**
 * License manager module
 */
function wqoecf_updater_utility() {
    $prefix = 'WQOECF_';
    $settings = [
        'prefix' => $prefix,
        'get_base' => WQOECF_PLUGIN_BASENAME,
        'get_slug' => WQOECF_PLUGIN_DIR,
        'get_version' => WQOECF_BUILD,
        'get_api' => 'https://download.geekcodelab.com/',
        'license_update_class' => $prefix . 'Update_Checker'
    ];

    return $settings;
}

register_activation_hook(__FILE__, 'wqoecf_updater_activate');
function wqoecf_updater_activate() {

    // Refresh transients
    delete_site_transient('update_plugins');
    delete_transient('wqoecf_plugin_updates');
    delete_transient('wqoecf_plugin_auto_updates');
}

require_once(WQOECF_PLUGIN_DIR_PATH . 'updater/class-update-checker.php');