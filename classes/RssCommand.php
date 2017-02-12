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
        global $sl, $pth, $h, $u, $pd_router, $plugin_cf;

        $view = new View('feed');
        $view->title = $this->feed->getTitle();
        $view->link = CMSIMPLE_URL;
        $view->description = $this->feed->getDescription();
        $view->language = $sl;
        $view->pubDate = date('r', filemtime($pth['file']['content']));
        $view->generator = CMSIMPLE_XH_VERSION . ' – Yanp_XH ' . YANP_VERSION;
        $view->hasImage = $plugin_cf['yanp']['feed_image'] != '';
        $view->imageUrl = $this->getAbsoluteUrl($pth['folder']['images'] . $plugin_cf['yanp']['feed_image']);
        $view->pageIds = $this->getPageIds();
        $view->itemHeading = function ($id) use ($h) {
            return new HtmlString($h[$id]);
        };
        $view->itemLink = function ($id) use ($u) {
            return CMSIMPLE_URL . "?{$u[$id]}";
        };
        $view->itemDescription = function ($id) use ($pd_router) {
            return $this->getDescription($id);
        };
        $view->itemGuid = function ($id) use ($u) {
            return CMSIMPLE_URL . "?{$u[$id]} " . $this->getLastMod($id);
        };
        $view->itemPubDate = function ($id) {
            return date('r', $this->getLastMod($id));
        };
        return '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . $view->render();
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
        list($scheme, $path) = explode('//', CMSIMPLE_URL . $url);
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
}
