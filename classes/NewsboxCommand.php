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

class NewsboxCommand extends Command
{
    /** @var View */
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @return void
     */
    public function execute()
    {
        echo $this->render();
    }

    /**
     * @return string
     */
    private function render()
    {
        global $h, $u, $cf, $sn, $plugin_tx;

        $this->view->pageIds = $this->getPageIds();
        $this->view->headingTag = 'h' . min($cf['menu']['levels'] + 1, 6);
        $this->view->heading =
            /**
             * @param int $id
             * @return HtmlString
             */
            function ($id) use ($h) {
                return new HtmlString($h[$id]);
            };
        $this->view->date =
            /**
             * @param int $id
             * @return string
             */
            function ($id) use ($plugin_tx) {
                return date($plugin_tx['yanp']['news_date_format'], $this->getLastMod($id));
            };
        $this->view->description =
            /**
             * @param int $id
             * @return string|HtmlString
             */
            function ($id) {
                return $this->getDescription($id);
            };
        $this->view->url =
            /**
             * @param int $id
             * @return string
             */
            function ($id) use ($sn, $u) {
                return "$sn?{$u[$id]}";
            };
        return $this->view->render('newsbox');
    }
}
