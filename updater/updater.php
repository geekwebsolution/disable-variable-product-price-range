<?php

if (!defined('ABSPATH')) exit;

/**
 * License manager module
 */
function wdvppr_updater_utility() {
    $prefix = 'WDVPPR_';
    $settings = [
        'prefix' => $prefix,
        'get_base' => WDVPPR_PLUGIN_BASENAME,
        'get_slug' => WDVPPR_PLUGIN_DIR,
        'get_version' => WDVPPR_BUILD,
        'get_api' => 'https://download.geekcodelab.com/',
        'license_update_class' => $prefix . 'Update_Checker'
    ];

    return $settings;
}

function wdvppr_updater_activate() {

    // Refresh transients
    delete_site_transient('update_plugins');
    delete_transient('wdvppr_plugin_updates');
    delete_transient('wdvppr_plugin_auto_updates');
}

require_once(WDVPPR_PLUGIN_DIR_PATH . 'updater/class-update-checker.php');