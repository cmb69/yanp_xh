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
     * The feed.
     *
     * @var Yanp_Feed
     */
    protected $feed;

    /**
     * Initializes a new instance.
     *
     * @param Yanp_Feed $feed A feed.
     */
    public function __construct(Yanp_Feed $feed)
    {
        $this->feed = $feed;
    }

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
     */
    protected function renderRss()
    {
        $channel = $this->renderChannel();
        return <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">$channel</rss>
EOT;
    }

    /**
     * Renders the channel.
     *
     * @return string XML
     *
     * @global string The requested language.
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the plugins.
     */
    protected function renderChannel()
    {
        global $sl, $pth, $plugin_cf;

        $link = $this->getBaseUrl();
        $feed = '<channel>'
            . '<title>' . $this->feed->getTitle() . '</title>'
            . '<link>' . $link . '</link>'
            . '<description>' . $this->feed->getDescription() . '</description>'
            . '<language>' . $sl . '</language>'
            . $this->renderCopyright()
            . '<pubDate>' . date('r', filemtime($pth['file']['content']))
            . '</pubDate>'
            . '<generator>' . CMSIMPLE_XH_VERSION . ' &#8211; Yanp_XH '
            . YANP_VERSION
            . '</generator>';
        if ($plugin_cf['yanp']['feed_image'] != '') {
            $feed .= $this->renderFeedImage($link);
        }
        $feed .= $this->renderItems(array($this, 'renderRssItem'))
            . '</channel>';
        return $feed;
    }

    /**
     * Renders the copyright.
     *
     * @return string XML
     *
     * @global array The localization of the plugins.
     */
    protected function renderCopyright()
    {
        global $plugin_tx;

        if ($plugin_tx['yanp']['feed_copyright'] != '') {
            return '<copyright>' . $plugin_tx['yanp']['feed_copyright']
                . '</copyright>';
        } else {
            return '';
        }
    }

    /**
     * Renders the feed image.
     *
     * @return string XML
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the plugins.
     */
    protected function renderFeedImage()
    {
        global $pth, $plugin_cf;

        $pcf = $plugin_cf['yanp'];
        if (!is_readable($pth['folder']['images'] . $pcf['feed_image'])) {
            e('missing', 'file', $pth['folder']['images'] . $pcf['feed_image']);
        }
        $url = $this->getAbsoluteUrl(
            $pth['folder']['images'] . $pcf['feed_image']
        );
        $title = $this->feed->getTitle();
        $link = $this->getBaseUrl();
        return <<<EOT
<image>
    <url>$url</url>
    <title>$title</title>
    <link>$link</link>
</image>
EOT;
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
        $desc = XH_hsc($pd['yanp_description']);
        if (!$pcf['html_markup']) {
            $desc = XH_hsc($desc);
        }
        $guid = $link . ' ' . $this->getLastMod($pd);
        $timestamp = date('r', $this->getLastMod($pd));
        return <<<EOT
<item>
    <title>$h[$id]</title>
    <link>$link</link>
    <description>$desc</description>
    <guid isPermaLink="false">$guid</guid>
    <pubDate>$timestamp</pubDate>
</item>
EOT;
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
        return CMSIMPLE_URL;
    }
}

?>
