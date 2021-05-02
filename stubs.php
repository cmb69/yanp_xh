<?php

const CMSIMPLE_URL = "http://example.com/";

const CMSIMPLE_XH_VERSION = "CMSimple_XH 1.7.4";

const YANP_VERSION = "1pl5";


/**
 * @return string
 */
function plugin_admin_common() {}

/**
 * @param string $main
 * @return string
 */
function print_plugin_admin($main) {}

/**
 * @param string $tooltip
 * @return string
 */
function XH_helpIcon($tooltip) {}

/**
 * @param string $string
 * @return string
 */
function XH_hsc($string) {}

/**
 * @param int $count
 * @return string
 */
function XH_numberSuffix($count) {}

/**
 * @param bool $showMain
 * @return void
 */
function XH_registerStandardPluginMenuItems($showMain) {}

/**
 * @param string $pluginName
 * @return bool
 */
function XH_wantsPluginAdministration($pluginName) {}
