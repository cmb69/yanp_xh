<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

namespace Yanp;

class RssCommand extends Command
{
    /**
     * @var Feed
     */
    protected $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

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
     * @return string
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
     * @return string
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
     * @return string
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
     * @return string
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
     * @param int $id
     * @return string
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
     * @return string
     */
    protected function getFeedUrl()
    {
        global $sn;

        return $sn . '?&yanp_feed';
    }

    /**
     * @param string $url
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
     * @return string
     */
    protected function getBaseUrl()
    {
        return CMSIMPLE_URL;
    }
}
