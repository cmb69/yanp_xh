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
use XH\Pages;

class NewsboxCommand
{
    /** @var array<string,string> */
    private $conf;

    /** @var string */
    private $dateFormat;

    /** @var Pages */
    private $pages;

    /** @var NewsService */
    private $newsService;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        string $dateFormat,
        Pages $pages,
        NewsService $newsService,
        View $view
    ) {
        $this->conf = $conf;
        $this->dateFormat = $dateFormat;
        $this->pages = $pages;
        $this->newsService = $newsService;
        $this->view = $view;
    }

    public function execute(Request $request): string
    {
        return $this->view->render('newsbox', [
            'pageIds' => $this->newsService->getPageIds(),
            'headingTag' => $this->conf['heading_level'],
            'heading' => function (int $id): string {
                return $this->pages->heading($id);
            },
            'date' => function (int $id): string {
                return date($this->dateFormat, $this->newsService->getLastMod($id));
            },
            'description' => function (int $id): string {
                $res = $this->newsService->getDescription($id);
                if (!$this->conf["html_markup"]) {
                    $res = $this->view->esc($res);
                }
                return $res;
            },
            'url' => function (int $id) use ($request): string {
                return $request->url()->page($this->pages->url($id))->relative();
            },
        ]);
    }
}
