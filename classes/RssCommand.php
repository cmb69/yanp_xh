<?php

/**
 * The RSS commands.
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
 * The RSS commands.
 *
 * @category CMSimple_XH
 * @package  Yanp
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Yanp_XH
 */
class Yanp_RssCommand extends Yanp_Command
{
    /**
     * Executes the command.
     *
     * @return void
     */
    public function execute()
    {
        if (isset($_GET['yanp_feed'])) {
            header('Content-Type: application/xml');
            echo $this->renderRss();
            exit;
        }
        $this->writeHeadLink();
    }

    /**
     * Returns the XML of the complete RSS feed.
     *
     * @return string XML
     *
     * @global array  The paths of system files and folders.
     * @global array  The localization of the core.
     * @global string The requested language.
     * @global array  The language configuration of the core.
     * @global array  The configuration of the core.
     * @global array  The localization of the core.
     */
    protected function renderRss()
    {
        global $pth, $tx, $cf, $sl, $txc, $plugin_cf, $plugin_tx;

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
            . '  <generator>' . CMSIMPLE_XH_VERSION . ' &#8211; Yanp_XH '
            . YANP_VERSION
            . '</generator>' . "\n";
        if ($pcf['feed_image'] != '') {
            if (!is_readable($pth['folder']['images'] . $pcf['feed_image'])) {
                e('missing', 'file', $pth['folder']['images'] . $pcf['feed_image']);
            }
            $feed .= '  <image>' . "\n"
                . '    <url>'
                . $this->getAbsoluteUrl(
                    $pth['folder']['images'] . $pcf['feed_image']
                )
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
     * Returns the XML for a single feed item.
     *
     * @param int $id The number of the page.
     *
     * @return string XML
     *
     * @global object The page data router.
     * @global array  The URLs of the pages.
     * @global array  The headings of the pages.
     * @global array  The configuration of the plugins.
     */
    protected function renderRssItem($id)
    {
        global $pd_router, $u, $h, $plugin_cf;

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
            . '    <pubDate>' . date('r', $this->timestamp($pd)) . '</pubDate>'
            . "\n"
            . '  </item>' . "\n";
        return $xml;
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
            . ' href="' . $fn . '"'
        ) . "\n";
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
     * Returns an absolute URL.
     *
     * @param string $url A relative URL.
     *
     * @return string
     */
    protected function getAbsoluteUrl($url)
    {
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
                . (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'
                   ? 's' : '')
                . '://' . $_SERVER['HTTP_HOST'] . $sn;
        }
        return preg_replace('/index\.php$/', '', $baseUrl);
    }
}

?>
