<?php

const CMSIMPLE_URL = "http://example.com/";

const CMSIMPLE_XH_VERSION = "CMSimple_XH 1.7.4";

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

namespace XH {
    class PageDataRouter {
        /** @return array<int,array> */
        public function find_all(): array {}
        public function find_page(int $id): array {}
        public function add_interest(string $field) {}
        public function add_tab(string $tab_name, string $tab_view, string $cssClass = null) {}
    }
}