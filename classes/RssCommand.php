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

use DOMDocument;
use Plib\Logger;
use Plib\Request;
use Plib\Response;
use Plib\View;
use Yanp\Model\NewsFinder;

class RssCommand
{
    /** @var string */
    private $pluginFolder;

    /** @var string */
    private $imageFolder;

    /** @var array<string,string> */
    private $conf;

    /** @var NewsFinder */
    private $newsFinder;

    /** @var Logger */
    private $logger;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        string $pluginFolder,
        string $imageFolder,
        array $conf,
        NewsFinder $newsFinder,
        Logger $logger,
        View $view
    ) {
        $this->pluginFolder = $pluginFolder;
        $this->imageFolder = $imageFolder;
        $this->conf = $conf;
        $this->newsFinder = $newsFinder;
        $this->logger = $logger;
        $this->view = $view;
    }

    public function execute(Request $request): Response
    {
        if (!$this->conf["feed_enabled"]) {
            return Response::create();
        }
        if ($request->get("yanp_feed") === null) {
            return Response::create()->withHjs($this->view->render("head_link", [
                "url" => $request->url()->page("")->with("yanp_feed")->absolute(),
            ]));
        }
        $rss = $this->renderRss($request);
        if (!$this->validateRss($rss)) {
            $this->logger->log("error", "RSS feed", "the RSS feed is invalid");
            if (!$request->admin()) {
                return Response::error(503, $this->view->text("error_broken_feed"));
            }
        }
        return Response::create($rss)->withContentType("application/xml");
    }

    private function renderRss(Request $request): string
    {
        $news = $this->newsFinder->find();
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            . $this->view->render('feed', [
                'title' => $news->title(),
                'link' => $request->url()->page("")->absolute(),
                'description' => $news->description(),
                'language' => $request->language(),
                'pubDate' => date('r', $news->mtime()),
                'generator' => 'Yanp_XH',
                'hasImage' => $this->conf['feed_image'],
                'imageUrl' => $request->url()->path($this->imageFolder . $this->conf['feed_image'])->absolute(),
                'pages' => $news->pages((int) $this->conf["entries_max"]),
                'itemLink' => function (string $url) use ($request): string {
                    return $request->url()->page($url)->absolute();
                },
                'itemGuid' => function (string $url, int $mtime) use ($request): string {
                    return $request->url()->page($url)->absolute() . " " . $mtime;
                },
                'formatDate' => function (int $timestamp): string {
                    return date('r', $timestamp);
                },
            ]);
    }

    public function validateRss(string $rss): bool
    {
        if (!extension_loaded("dom")) {
            return true;
        }
        $doc = new DOMDocument();
        if (!@$doc->loadXML($rss)) {
            return false;
        };
        return $doc->schemaValidate($this->pluginFolder . "rss-2_0_1-rev9.xsd");
    }
}
