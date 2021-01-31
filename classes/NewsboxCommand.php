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
    /**
     * @return string
     */
    public function execute()
    {
        echo $this->render();
    }

    /**
     * @return string
     */
    protected function render()
    {
        global $h, $u, $cf, $sn, $plugin_tx;

        $view = new View();
        $view->pageIds = $this->getPageIds();
        $view->headingTag = 'h' . min($cf['menu']['levels'] + 1, 6);
        $view->heading = function ($id) use ($h) {
            return new HtmlString($h[$id]);
        };
        $view->date = function ($id) use ($plugin_tx) {
            return date($plugin_tx['yanp']['news_date_format'], $this->getLastMod($id));
        };
        $view->description = function ($id) {
            return $this->getDescription($id);
        };
        $view->url = function ($id) use ($sn, $u) {
            return "$sn?{$u[$id]}";
        };
        return $view->render('newsbox');
    }
}
