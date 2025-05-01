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

use Plib\Request;
use Plib\Response;
use Plib\View;
use Yanp\Model\NewsFinder;

class RssCommand
{
    /** @var string */
    private $imageFolder;

    /** @var string */
    private $contentFile;

    /** @var array<string,string> */
    private $conf;

    /** @var NewsFinder */
    private $newsFinder;

    /** @var Feed */
    private $feed;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        string $imageFolder,
        string $contentFile,
        array $conf,
        NewsFinder $newsFinder,
        Feed $feed,
        View $view
    ) {
        $this->imageFolder = $imageFolder;
        $this->contentFile = $contentFile;
        $this->conf = $conf;
        $this->newsFinder = $newsFinder;
        $this->feed = $feed;
        $this->view = $view;
    }

    public function execute(Request $request): Response
    {
        if ($request->get("yanp_feed") !== null) {
            return Response::create($this->renderRss($request))->withContentType("application/xml");
        }
        return Response::create()->withHjs($this->headLink($request));
    }

    private function renderRss(Request $request): string
    {
        $news = $this->newsFinder->find();
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            . $this->view->render('feed', [
                'title' => $this->feed->getTitle(),
                'link' => $request->url()->page("")->absolute(),
                'description' => $this->feed->getDescription(),
                'language' => $request->language(),
                'pubDate' => date('r', (int) filemtime($this->contentFile)),
                'generator' => 'Yanp_XH',
                'hasImage' => $this->conf['feed_image'] != '',
                'imageUrl' => $request->url()->path($this->imageFolder . $this->conf['feed_image'])->absolute(),
                'pages' => $news->pages((int) $this->conf["entries_max"]),
                'itemLink' => function (string $url) use ($request): string {
                    return $request->url()->page($url)->absolute();
                },
                'escapedItemDescription' => function (string $description): string {
                    if (!$this->conf["html_markup"]) {
                        $description = $this->view->esc($description);
                    }
                    return $description;
                },
                'itemGuid' => function (string $url, int $mtime) use ($request): string {
                    return $request->url()->page($url)->absolute() . " " . $mtime;
                },
                'formatDate' => function (int $timestamp): string {
                    return date('r', $timestamp);
                },
            ]);
    }

    private function headLink(Request $request): string
    {
        global $plugin_tx;

        $fn = $this->getFeedUrl($request);
        return '<link rel="alternate" type="application/rss+xml"'
            . ' title="' . $plugin_tx['yanp']['feed_link_title'] . '"'
            . ' href="' . $fn . '">'
            . "\n";
    }

    private function getFeedUrl(Request $request): string
    {
        return $request->url()->page("")->with("yanp_feed")->absolute();
    }
}
