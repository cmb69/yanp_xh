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

class NewsboxCommand
{
    /** @var NewsService */
    private $newsService;

    /** @var View */
    private $view;

    public function __construct(NewsService $newsService, View $view)
    {
        $this->newsService = $newsService;
        $this->view = $view;
    }

    /**
     * @return void
     */
    public function execute()
    {
        global $h, $u, $cf, $sn, $plugin_tx;

        $this->view->render('newsbox', [
            'pageIds' => $this->newsService->getPageIds(),
            'headingTag' => 'h' . min($cf['menu']['levels'] + 1, 6),
            'heading' => function (int $id) use ($h): HtmlString {
                return new HtmlString($h[$id]);
            },
            'date' => function (int $id) use ($plugin_tx): string {
                return date($plugin_tx['yanp']['news_date_format'], $this->newsService->getLastMod($id));
            },
            'description' => /** @return string|HtmlString */ function (int $id) {
                return $this->newsService->getDescription($id);
            },
            'url' => function (int $id) use ($sn, $u): string {
                return "$sn?{$u[$id]}";
            },
        ]);
    }
}
