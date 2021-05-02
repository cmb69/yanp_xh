<?php

/**
 * Copyright 2011-2021 Christoph M. Becker
 *
 * This file is part of Yanp_XH.
 *
 * Yanp_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Yanp_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Yanp_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Yanp;

class RssCommand
{
    /** @var NewsService */
    private $newsService;

    /**
     * @var Feed
     */
    private $feed;

    /** @var View */
    private $view;

    public function __construct(NewsService $newsService, Feed $feed, View $view)
    {
        $this->newsService = $newsService;
        $this->feed = $feed;
        $this->view = $view;
    }

    /** @return void */
    public function execute()
    {
        if (isset($_GET['yanp_feed'])) {
            header('Content-Type: application/xml');
            $this->renderRss();
            exit;
        }
        $this->writeHeadLink();
    }

    /** @return void */
    private function renderRss()
    {
        global $sl, $pth, $h, $u, $plugin_cf;

        $this->view->title = $this->feed->getTitle();
        $this->view->link = CMSIMPLE_URL;
        $this->view->description = $this->feed->getDescription();
        $this->view->language = $sl;
        $this->view->pubDate = date('r', filemtime($pth['file']['content']));
        $this->view->generator = 'Yanp_XH';
        $this->view->hasImage = $plugin_cf['yanp']['feed_image'] != '';
        $this->view->imageUrl = $this->getAbsoluteUrl($pth['folder']['images'] . $plugin_cf['yanp']['feed_image']);
        $this->view->pageIds = $this->newsService->getPageIds();
        $this->view->itemHeading = function (int $id) use ($h): HtmlString {
            return new HtmlString($h[$id]);
        };
        $this->view->itemLink = function (int $id) use ($u): string {
            return CMSIMPLE_URL . "?{$u[$id]}";
        };
        $this->view->itemDescription = /** @return string|HtmlString */ function (int $id) {
            return $this->newsService->getDescription($id);
        };
        $this->view->itemGuid = function (int $id) use ($u): string {
            return CMSIMPLE_URL . "?{$u[$id]} " . $this->newsService->getLastMod($id);
        };
        $this->view->itemPubDate = function (int $id): string {
            return date('r', $this->newsService->getLastMod($id));
        };
        echo '<?xml version="1.0" encoding="UTF-8"?>', PHP_EOL;
        $this->view->render('feed');
    }

    /**
     * @return void
     */
    private function writeHeadLink()
    {
        global $hjs, $plugin_tx;

        $fn = $this->getFeedUrl();
        $hjs .= '<link rel="alternate" type="application/rss+xml"'
            . ' title="' . $plugin_tx['yanp']['feed_link_title'] . '"'
            . ' href="' . $fn . '">'
            . "\n";
    }

    private function getFeedUrl(): string
    {
        global $sn;

        return $sn . '?&yanp_feed';
    }

    private function getAbsoluteUrl(string $url): string
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
