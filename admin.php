<?php

/**
 * Back-end of Yanp_XH.
 *
 * PHP versions 4 and 5
 *
 * @category  CMSimple_XH
 * @package   Yanp
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2011-2015 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Yanp_XH
 */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * Returns the plugin version information.
 *
 * @return string (X)HTML
 *
 * @global array The paths of system files and folders.
 * @global array The localization of the plugins.
 */
function Yanp_version()
{
    global $pth, $plugin_tx;

    return '<h1>Yanp</h1>' . "\n"
        . tag(
            'img src="' . $pth['folder']['plugins'] . 'yanp/yanp.png"'
            . ' class="yanp_plugin_icon" alt="' . $plugin_tx['yanp']['alt_logo']
            . '"'
        )
        . '<p>Version: '.YANP_VERSION.'</p>' . "\n"
        . '<p>Copyright &copy; 2011-2015 <a href="http://3-magi.net/"'
        . ' target="_blank">Christoph M. Becker</a></p>' . "\n"
        . '<p class="yanp_license">This program is free software: you can'
        . ' redistribute it and/or modify'
        . ' it under the terms of the GNU General Public License as published by'
        . ' the Free Software Foundation, either version 3 of the License, or'
        . ' (at your option) any later version.</p>' . "\n"
        . '<p class="yanp_license">This program is distributed in the hope that'
        . ' it will be useful,'
        . ' but WITHOUT ANY WARRANTY; without even the implied warranty of'
        . ' MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the'
        . ' GNU General Public License for more details.</p>' . "\n"
        . '<p class="yanp_license">You should have received a copy of the GNU'
        . ' General Public License'
        . ' along with this program.  If not, see'
        . ' <a href="http://www.gnu.org/licenses/" target="_blank">'
        . 'http://www.gnu.org/licenses/</a>.</p>' . "\n";
}

/**
 * Returns the requirements information.
 *
 * @return string (X)HTML
 *
 * @global array The paths of system files and folders.
 * @global array The localization of the core.
 * @global array The localization of the plugins.
 * @global array The configuration of the plugins.
 */
function Yanp_systemCheck()
{
    global $pth, $tx, $plugin_tx, $plugin_cf;

    define('YANP_PHP_VERSION', '4.1.0');
    $ptx = $plugin_tx['yanp'];
    $imgdir = $pth['folder']['plugins'] . 'yanp/images/';
    $ok = tag('img src="' . $imgdir . 'ok.png" alt="ok"');
    $warn = tag('img src="' . $imgdir . 'warn.png" alt="warning"');
    $fail = tag('img src="' . $imgdir . 'fail.png" alt="failure"');
    $htm = tag('hr') . '<h4>' . $ptx['syscheck_title'] . '</h4>'
        . (version_compare(PHP_VERSION, YANP_PHP_VERSION) >= 0 ? $ok : $fail)
        . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_phpversion'], YANP_PHP_VERSION)
        . tag('br') . tag('br') . "\n";
    foreach (array('date') as $ext) {
        $htm .= (extension_loaded($ext) ? $ok : $fail)
            . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_extension'], $ext)
            . tag('br') . "\n";
    }
    $htm .= tag('br')
        . (strtoupper($tx['meta']['codepage']) == 'UTF-8' ? $ok : $warn)
        . '&nbsp;&nbsp;' . $ptx['syscheck_encoding'] . tag('br') . "\n";
    $htm .= (!get_magic_quotes_runtime() ? $ok : $warn)
        . '&nbsp;&nbsp;' . $ptx['syscheck_magic_quotes'] . tag('br') . tag('br')
        . "\n";
    foreach (array('config/', 'css/', 'languages/') as $folder) {
        $folders[] = $pth['folder']['plugins'] . 'yanp/' . $folder;
    }
    $folders[] = Yanp_dataFolder();
    foreach ($folders as $folder) {
        $htm .= (is_writable($folder) ? $ok : $warn)
            . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_writable'], $folder)
            . tag('br') . "\n";
    }
    return $htm;
}

/**
 * Returns the XML for a single feed item.
 *
 * @param int $id The number of the page.
 *
 * @return string XML
 *
 * @global object The page data router.
 * @global string The script name.
 * @global array  The URLs of the pages.
 * @global array  The headings of the pages.
 * @global array  The contents of the pages.
 * @global array  The configuration of the plugins.
 */
function Yanp_rssItem($id)
{
    global $pd_router, $sn, $u, $h, $c, $plugin_cf;

    $pcf = $plugin_cf['yanp'];
    $pd = $pd_router->find_page($id);
    $link = Yanp_baseUrl() . '?' . $u[$id];
    $desc = htmlspecialchars($pd['yanp_description'], ENT_COMPAT, 'UTF-8');
    if (!$pcf['html_markup']) {
        $desc = htmlspecialchars($desc, ENT_COMPAT, 'UTF-8');
    }
    $guid = $link . ' ' . Yanp_timestamp($pd);
    $xml = '  <item>' . "\n"
        . '    <title>' . $h[$id] . '</title>' . "\n"
        . '    <link>' . $link . '</link>' . "\n"
        . '    <description>' . $desc . '</description>' . "\n"
        . '    <guid isPermaLink="false">' . $guid . '</guid>' . "\n"
        . '    <pubDate>' . date('r', Yanp_timestamp($pd)) . '</pubDate>' . "\n"
        . '  </item>' . "\n";
    return $xml;
}

/**
 * Returns the XML of the complete RSS feed.
 *
 * @return string XML
 *
 * @global array  The paths of system files and folders.
 * @global string The script name.
 * @global array  The localization of the core.
 * @global string The requested language.
 * @global array  The language configuration of the core.
 * @global array  The configuration of the core.
 * @global array  The localization of the core.
 */
function Yanp_rss()
{
    global $pth, $sn, $tx, $cf, $sl, $txc, $plugin_cf, $plugin_tx;

    $pcf = $plugin_cf['yanp'];
    $ptx = $plugin_tx['yanp'];
    $link = Yanp_baseUrl();
    $title = $ptx['feed_title'] == ''
            ? (isset($txc['site']['title'])
               ? $txc['site']['title'] : $cf['site']['title'])
            : $ptx['feed_title'];
    if ($ptx['feed_description'] != '') {
        $desc = $ptx['feed_description'];
    } elseif (isset($tx['meta']['description'])) {
        $desc = $tx['meta']['description'];
    } elseif (isset($txc['meta']['description'])) {
        $desc = $txc['meta']['description'];
    } else {
        $desc = $cf['meta']['description'];
    }
    $feed = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
        . '<rss version="2.0">' . "\n"
        . '<channel>' . "\n"
        . '  <title>' . $title . '</title>' . "\n"
        . '  <link>' . $link . '</link>' . "\n"
        . '  <description>' . $desc . '</description>' . "\n"
        . '  <language>' . $sl . '</language>' . "\n"
        . (!empty($ptx['feed_copyright'])
           ? '  <copyright>' . $ptx['feed_copyright'] . '</copyright>' . "\n"
           : '')
        . '  <pubDate>' . date('r', filemtime($pth['file']['content']))
        . '</pubDate>' . "\n"
        . '  <generator>' . CMSIMPLE_XH_VERSION . ' – Yanp_XH ' . YANP_VERSION
        . '</generator>' . "\n";
    if ($pcf['feed_image'] != '') {
        if (!is_readable($pth['folder']['images'] . $pcf['feed_image'])) {
            e('missing', 'file', $pth['folder']['images'] . $pcf['feed_image']);
        }
        $feed .= '  <image>' . "\n"
            . '    <url>'
            . Yanp_absoluteUrl($pth['folder']['images'] . $pcf['feed_image'])
            . '</url>' . "\n"
            . '    <title>' . $title . '</title>' . "\n"
            . '    <link>' . $link . '</link>' . "\n"
            . '  </image>' . "\n";
    }
    $feed .= Yanp_items('Yanp_rssItem')
        . '</channel>' . "\n"
        . '</rss>' . "\n";
    return $feed;
}

/**
 * Writes the RSS feed to a file.
 *
 * @return void
 *
 * @global array  The paths of system files and folders.
 * @global string The requested language.
 * @global array  The configuration of the core.
 */
function Yanp_writeRss()
{
    global $pth, $sl, $plugin_cf;

    $pcf = $plugin_cf['yanp'];
    $fn = Yanp_dataFolder() . 'feed-' . $sl . '.' . $pcf['feed_extension'];
    if (($fh = fopen($fn, 'w')) === false || fwrite($fh, Yanp_rss()) === false) {
        e('cntwriteto', 'file', $fn);
    }
    if ($fh !== false) {
        fclose($fh);
    }
}

/*
 * Register plugin menu items.
 */
if (function_exists('XH_registerStandardPluginMenuItems')) {
    XH_registerStandardPluginMenuItems(false);
}

/**
 * The pagedata hook
 */
$pd_router->add_interest('yanp_timestamp');
$pd_router->add_interest('yanp_description');
$pd_router->add_tab(
    $plugin_tx['yanp']['tab_label'],
    $pth['folder']['plugins'] . 'yanp/yanp_view.php'
);

/**
 * Write the RSS file.
 */
if ($plugin_cf['yanp']['feed_enabled']
    && ($function == 'save'                               // changes from the editor
    || isset($menumanager) && $action == 'saverearranged' // changes from menumanager
    || isset($pagemanager) && $action == 'plugin_save'    // changes from pagemanager
    || $s >= 0 && isset($_POST['save_page_data']))        // changes to pagedata
) {
    Yanp_writeRss();
}

/*
 * Plugin administration
 */
if (function_exists('XH_wantsPluginAdministration')
    && XH_wantsPluginAdministration('yanp')
    || isset($yanp) && $yanp == 'true'
) {
    $o .= print_plugin_admin('off');

    switch ($admin) {
    case '':
        $o .= Yanp_version() . Yanp_systemCheck();
        break;
    default:
        $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
