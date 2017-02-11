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
            $pageData = $pd_router->find_page($id);
            return $plugin_cf['yanp']['html_markup']
                ? new HtmlString($pageData['yanp_description'])
                : $pageData['yanp_description'];
        };
        $view->itemGuid = function ($id) use ($u, $pd_router) {
            $pageData = $pd_router->find_page($id);
            $timestamp = min(
                isset($pageData['last_edit']) ? $pageData['last_edit'] : 0,
                isset($pageData['yanp_timestamp']) ? $pageData['yanp_timestamp'] : 0
            );
            return CMSIMPLE_URL . "?{$u[$id]} $timestamp";
        };
        $view->itemPubDate = function ($id) use ($pd_router) {
            $pageData = $pd_router->find_page($id);
            $timestamp = min(
                isset($pageData['last_edit']) ? $pageData['last_edit'] : 0,
                isset($pageData['yanp_timestamp']) ? $pageData['yanp_timestamp'] : 0
            );
            return date('r', $timestamp);
        };
        return '<?xml version="1.0" encoding="UTF-8"?>' . $view->render();
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
