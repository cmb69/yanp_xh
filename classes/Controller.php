<?php

/**
 * The controllers.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Yanp
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2011-2015 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Yanp_XH
 */

/**
 * The controllers.
 *
 * @category CMSimple_XH
 * @package  Yanp
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Yanp_XH
 */
class Yanp_Controller
{
    /**
     * Dispatches on plugin related requests.
     *
     * @return void
     *
     * @global bool   Whether we're in admin mode.
     * @global bool   Whether the yanp administration is requested.
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     * @global array  The paths of system files and folders.
     * @global object The page data router.
     * @global array  The configuration of the plugins.
     * @global array  The localization of the plugins.
     * @global string The (X)HTML to insert before the page contents.
     */
    public function dispatch()
    {
        global $adm, $yanp, $admin, $action, $pth, $pd_router, $plugin_cf,
            $plugin_tx, $o;

        if ($plugin_cf['yanp']['feed_enabled']) {
            if (isset($_GET['yanp_feed'])) {
                header('Content-Type: application/xml');
                echo $this->renderRss();
                exit;
            }
            $this->writeHeadLink();
        }
        if ($adm) {
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
                    $o .= $this->renderVersion() . $this->renderSystemCheck();
                    break;
                default:
                    $o .= plugin_admin_common($action, $admin, 'yanp');
                }
            }
        }
    }

    /**
     * Returns the plugin version information.
     *
     * @return string (X)HTML
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     */
    protected function renderVersion()
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
    protected function renderSystemCheck()
    {
        global $pth, $tx, $plugin_tx, $plugin_cf;

        define('YANP_PHP_VERSION', '5.1.2');
        $ptx = $plugin_tx['yanp'];
        $imgdir = $pth['folder']['plugins'] . 'yanp/images/';
        $ok = tag('img src="' . $imgdir . 'ok.png" alt="ok"');
        $warn = tag('img src="' . $imgdir . 'warn.png" alt="warning"');
        $fail = tag('img src="' . $imgdir . 'fail.png" alt="failure"');
        $htm = tag('hr') . '<h4>' . $ptx['syscheck_title'] . '</h4>'
            . (version_compare(PHP_VERSION, YANP_PHP_VERSION) >= 0 ? $ok : $fail)
            . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_phpversion'], YANP_PHP_VERSION)
            . tag('br') . tag('br') . "\n";
        foreach (array('pcre', 'spl') as $ext) {
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
        $folders[] = $this->getDataFolder();
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
    protected function renderRssItem($id)
    {
        global $pd_router, $sn, $u, $h, $c, $plugin_cf;

        $pcf = $plugin_cf['yanp'];
        $pd = $pd_router->find_page($id);
        $link = $this->getBaseUrl() . '?' . $u[$id];
        $desc = htmlspecialchars($pd['yanp_description'], ENT_COMPAT, 'UTF-8');
        if (!$pcf['html_markup']) {
            $desc = htmlspecialchars($desc, ENT_COMPAT, 'UTF-8');
        }
        $guid = $link . ' ' . $this->timestamp($pd);
        $xml = '  <item>' . "\n"
            . '    <title>' . $h[$id] . '</title>' . "\n"
            . '    <link>' . $link . '</link>' . "\n"
            . '    <description>' . $desc . '</description>' . "\n"
            . '    <guid isPermaLink="false">' . $guid . '</guid>' . "\n"
            . '    <pubDate>' . date('r', $this->timestamp($pd)) . '</pubDate>' . "\n"
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
    protected function renderRss()
    {
        global $pth, $sn, $tx, $cf, $sl, $txc, $plugin_cf, $plugin_tx;

        $pcf = $plugin_cf['yanp'];
        $ptx = $plugin_tx['yanp'];
        $link = $this->getBaseUrl();
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
            . '  <generator>' . CMSIMPLE_XH_VERSION . ' &#8211; Yanp_XH ' . YANP_VERSION
            . '</generator>' . "\n";
        if ($pcf['feed_image'] != '') {
            if (!is_readable($pth['folder']['images'] . $pcf['feed_image'])) {
                e('missing', 'file', $pth['folder']['images'] . $pcf['feed_image']);
            }
            $feed .= '  <image>' . "\n"
                . '    <url>'
                . $this->getAbsoluteUrl($pth['folder']['images'] . $pcf['feed_image'])
                . '</url>' . "\n"
                . '    <title>' . $title . '</title>' . "\n"
                . '    <link>' . $link . '</link>' . "\n"
                . '  </image>' . "\n";
        }
        $feed .= $this->renderItems(array($this, 'renderRssItem'))
            . '</channel>' . "\n"
            . '</rss>' . "\n";
        return $feed;
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
    public function renderFeedLink($icon)
    {
        global $pth, $plugin_cf, $plugin_tx, $sl;

        $pcf = $plugin_cf['yanp'];
        $ptx = $plugin_tx['yanp'];
        $icon = isset($icon)
            ? $pth['folder']['templateimages'] . $icon
            : $pth['folder']['plugins'].'yanp/images/feed.png';
        $fn = $this->getFeedUrl();
        return '<a href="' . $fn . '">'
            . tag(
                'img src="' . $icon . '"'
                . ' alt="' . $ptx['feed_link_title'] . '" title="'
                . $ptx['feed_link_title'] . '"'
            ) . '</a>';
    }

    /**
     * Returns the (X)HTML for the display of the complete newsbox.
     *
     * @return string
     */
    public function renderNewsbox()
    {
        return $this->renderItems(array($this, 'renderNewsboxItem'));
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
    protected function renderItems($func)
    {
        global $pd_router;

        $pd = $pd_router->find_all();
        $ids = array_keys($pd);
        $dates = array_map(
            create_function('$elt', 'global $_Yanp_controller; return $_Yanp_controller->timestamp($elt);'), $pd
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
    protected function renderNewsboxItem($id)
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
            . '<p><em>' . date($ptx['news_date_format'], $this->timestamp($pd))
            .'</em></p>' . "\n"
            . '<p>' . $desc
            . ' <span class="read-more"><a href="' . $sn . '?' . $u[$id]
            . '" title="' . $h[$id] . '">'
            . $ptx['news_read_more'] . '</a></span></p>' . "\n"
            . '</div>' . "\n";
        return $htm;
    }

    /**
     * Inserts the alternate link for the RSS feed into the head.
     *
     * @return void
     *
     * @global string The (X)HTML fragment to insert into the head element.
     * @global array  The localization of the plugins.
     */
    protected function writeHeadLink()
    {
        global $hjs, $plugin_tx;

        $fn = $this->getFeedUrl();
        $hjs .= tag(
            'link rel="alternate" type="application/rss+xml"'
            . ' title="' . $plugin_tx['yanp']['feed_link_title'] . '"'
            . ' href="' . $this->getAbsoluteUrl($fn) . '"'
        ) . "\n";
    }

    /**
     * Returns the timestamp of the news of a page.
     *
     * @param array $pd An array of page data.
     *
     * @return int
     */
    public function timestamp($pd)
    {
        return empty($pd['last_edit'])
            ? (empty($pd['yanp_timestamp']) ? 0 : $pd['yanp_timestamp'])
            : min($pd['yanp_timestamp'], $pd['last_edit']);
    }

    /**
     * Returns the relative file name of the feed file.
     *
     * @return string
     *
     * @global string The script name.
     */
    protected function getFeedUrl()
    {
        global $sn;

        return $sn . '?&yanp_feed';
    }

    /**
     * Returns the path of the data folder.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the plugins.
     */
    protected function getDataFolder()
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
     * Returns an absolute URL.
     *
     * @param string $url A relative URL.
     *
     * @return string
     *
     * @global string The script name.
     */
    protected function getAbsoluteUrl($url)
    {
        global $sn;

        list($scheme, $path) = explode('//', $this->getBaseUrl() . $url);
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
     * Returns the base URL of the installation.
     *
     * @return string
     *
     * @global string The script name.
     */
    protected function getBaseUrl()
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
     * Returns the (X)HTML of the pagedata form.
     *
     * @param array $page An array of page data.
     *
     * @return string
     *
     * @global array  The localization of the core.
     * @global string The script name.
     * @global string The requested page URL.
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the plugins.
     */
    public function renderPageDataView($page)
    {
        global $tx, $sn, $su, $pth, $plugin_tx;

        $ptx = $plugin_tx['yanp'];
        $help_icon = tag(
            'image src="' . $pth['folder']['plugins'] . 'yanp/images/help.png"'
            . ' alt="help"'
        );
        $htm = '<form id="yanp" action="' . $sn . '?' . $su
            . '" method="post" onsubmit="return true">' . "\n"
            . '<p><strong>' . $ptx['tab_form_label'] . '</strong></p>' . "\n";
        $htm .= tag(
            'input type="hidden" name="yanp_timestamp" value="' . time() . '"'
        );
        $htm .= '<a class="pl_tooltip" href="javascript:return false">' . $help_icon
            . '<span>' . $ptx['tab_description_info'] . '</span></a>&nbsp;'
            . '<label for="yanp_description"><span>' . $ptx['tab_description_label']
            . '</span></label>' . tag('br') . "\n";
        $htm .= '<textarea id="yanp_description" name="yanp_description" cols="40"'
            . ' row="10">'
            . $page['yanp_description'] . '</textarea>' . "\n";

        $htm .= tag('input type="hidden" name="save_page_data"') . "\n"
            . '<div style="text-align: right">' . "\n"
            . tag(
                'input type="submit" value="' . ucfirst($tx['action']['save']) . '"'
            ) . "\n"
            . '</div></form>' . "\n";
        return $htm;
    }

}

?>
