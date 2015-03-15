<?php

/**
 * Front-end of Yanp_XH.
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
 * The plugin version.
 */
define('YANP_VERSION', '@YANP_VERSION@');

/**
 * Returns the base URL of the installation.
 *
 * @return string
 *
 * @global string The script name.
 */
function Yanp_baseUrl()
{
    global $sn;

    if (defined(CMSIMPLE_URL)) {
        $baseUrl = CMSIMPLE_URL;
    } else {
        $baseUrl = 'http'
            . (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 's' : '')
            . '://' . $_SERVER['HTTP_HOST'] . $sn;
    }
    return preg_replace('/index\.php$/', '', $baseUrl);
}

/**
 * Returns an absolute URL.
 *
 * @param string $url A relative URL.
 *
 * @return string
 *
 * @global string The script name.
 */
function Yanp_absoluteUrl($url)
{
    global $sn;

    list($scheme, $path) = explode('//', Yanp_baseUrl() . $url);
    $parts = explode('/', $path);
    $i = 0;
    while ($i < count($parts)) {
        switch ($parts[$i]) {
        case '.':
            array_splice($parts, $i, 1);
            break;
        case '..':
            array_splice($parts, $i-1, 2);
            $i--;
            break;
        default:
            $i++;
        }
    }
    return $scheme . '//' . implode('/', $parts);
}

/**
 * Returns the path of the data folder.
 *
 * @return string
 *
 * @global array The paths of system files and folders.
 * @global array The configuration of the plugins.
 */
function Yanp_dataFolder()
{

    global $pth, $plugin_cf;

    $pcf = $plugin_cf['yanp'];

    $fn = $pcf['folder_data'] == ''
            ? $pth['folder']['plugins'].'yanp/data/'
            : $pth['folder']['base'] . $pcf['folder_data'];
    if (substr($fn, -1) != '/') {
        $fn .= '/';
    }
    if (file_exists($fn)) {
        if (!is_dir($fn)) {
            e('cntopen', 'folder', $fn);
        }
    } else {
        if (mkdir($fn, 0777, true)) {
            chmod($fn, 0777);
        } else {
            e('cntwriteto', 'folder', $fn);
        }
    }
    return $fn;
}

/**
 * Returns the relative file name of the feed file.
 *
 * @return string
 *
 * @global string The requested language.
 * @global array  The configuration of the plugins.
 */
function Yanp_feedFilename()
{
    global $sl, $plugin_cf;

    return Yanp_dataFolder() . 'feed-' . $sl . '.'
        . $plugin_cf['yanp']['feed_extension'];
}

/**
 * Returns the timestamp of the news of a page.
 *
 * @param array $pd An array of page data.
 *
 * @return int
 */
function Yanp_timestamp($pd)
{
    return empty($pd['last_edit'])
        ? (empty($pd['yanp_timestamp']) ? 0 : $pd['yanp_timestamp'])
        : min($pd['yanp_timestamp'], $pd['last_edit']);
}

/**
 * Inserts the alternate link for the RSS feed into the head.
 *
 * @return void
 *
 * @global string The (X)HTML fragment to insert into the head element.
 * @global array  The configuration of the plugins.
 * @global array  The localization of the plugins.
 */
function Yanp_headLink()
{
    global $hjs, $plugin_cf, $plugin_tx;

    $fn = Yanp_feedFilename();
    if ($plugin_cf['yanp']['feed_enabled']
        && file_exists($fn)
    ) {
        $hjs .= tag(
            'link rel="alternate" type="application/rss+xml"'
            . ' title="' . $plugin_tx['yanp']['feed_link_title'] . '"'
            . ' href="' . Yanp_absoluteUrl($fn) . '"'
        ) . "\n";
    }
}

/**
 * Returns the (X)HTML for the display of the page news.
 *
 * @param int $id The page number.
 *
 * @return string
 *
 * @global array  The headings of the pages.
 * @global array  The URLs of the pages.
 * @global array  The configuration of the core.
 * @global string The script name.
 * @global object The page data router.
 * @global array  The configuration of the plugins.
 * @global array  The localization of the plugins.
 */
function Yanp_newsboxItem($id)
{
    global $h, $u, $cf, $sn, $pd_router, $plugin_cf, $plugin_tx;

    $pcf = $plugin_cf['yanp'];
    $ptx = $plugin_tx['yanp'];
    $pd = $pd_router->find_page($id);
    $lvl = min($cf['menu']['levels'] + 1, 6);
    $desc = $pd['yanp_description'];
    if (!$pcf['html_markup']) {
        $desc = htmlspecialchars($desc, ENT_COMPAT, 'UTF-8');
    }
    $htm = '<div class="yanp-news">' . "\n"
        . '<h' . $lvl . '>' . $h[$id] . '</h' . $lvl . '>' . "\n"
        . '<p><em>' . date($ptx['news_date_format'], Yanp_timestamp($pd))
        .'</em></p>' . "\n"
        . '<p>' . $desc
        . ' <span class="read-more"><a href="' . $sn . '?' . $u[$id]
        . '" title="' . $h[$id] . '">'
        . $ptx['news_read_more'] . '</a></span></p>' . "\n"
        . '</div>' . "\n";
    return $htm;
}

/**
 * Returns the accumulated result of calling $func on all news.
 *
 * @param string $func A callback function.
 *
 * @return string
 *
 * @global object The page data router.
 */
function Yanp_items($func)
{
    global $pd_router;

    $pd = $pd_router->find_all();
    $ids = array_keys($pd);
    $dates = array_map(
        create_function('$elt', 'return Yanp_timestamp($elt);'), $pd
    );
    array_multisort($dates, SORT_DESC, $ids);
    $res = '';
    foreach ($ids as $id) {
        if (!empty($pd[$id]['yanp_description'])) {
            $res .= call_user_func($func, $id);
        }
    }
    return $res;
}

/**
 * Returns the (X)HTML for the display of the complete newsbox.
 *
 * @return string
 */
function Yanp_newsbox()
{
    return Yanp_items('Yanp_newsboxItem');
}

/**
 * Returns the (X)HTML of the RSS link.
 *
 * @param string $icon An icon filename.
 *
 * @return string
 *
 * @global array  The paths of system files and folders.
 * @global array  The configuration of the plugins.
 * @global array  The localization of the plugins.
 * @global string The requested language.
 */
function Yanp_feedlink($icon = null)
{
    global $pth, $plugin_cf, $plugin_tx, $sl;

    $pcf = $plugin_cf['yanp'];
    $ptx = $plugin_tx['yanp'];
    $icon = isset($icon)
        ? $pth['folder']['templateimages'] . $icon
        : $pth['folder']['plugins'].'yanp/images/feed.png';
    $fn = Yanp_feedFilename();
    if (file_exists($fn)) {
        return '<a href="' . $fn . '">'
            . tag(
                'img src="' . $icon . '"'
                . ' alt="' . $ptx['feed_link_title'] . '" title="'
                . $ptx['feed_link_title'] . '"'
            ) . '</a>';
    } else {
        return '';
    }
}

/**
 * Insert RSS <link> to <head>.
 */
Yanp_headLink();

?>
