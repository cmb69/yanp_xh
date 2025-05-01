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
use Plib\View;
use Yanp\Model\NewsFinder;

class NewsboxCommand
{
    /** @var array<string,string> */
    private $conf;

    /** @var NewsFinder */
    private $newsFinder;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        NewsFinder $newsFinder,
        View $view
    ) {
        $this->conf = $conf;
        $this->newsFinder = $newsFinder;
        $this->view = $view;
    }

    public function execute(Request $request): string
    {
        $news = $this->newsFinder->find();
        return $this->view->render('newsbox', [
            'pages' => $news->pages((int) $this->conf["entries_max"]),
            'headingTag' => $this->conf['heading_level'],
            'formatDate' => function (int $timestamp): string {
                return date($this->view->plain("news_date_format"), $timestamp);
            },
            'escapedDescription' => function (string $description): string {
                if (!$this->conf["html_markup"]) {
                    $description = $this->view->esc($description);
                }
                return $description;
            },
            'url' => function (string $url) use ($request): string {
                return $request->url()->page($url)->relative();
            },
        ]);
    }
}
