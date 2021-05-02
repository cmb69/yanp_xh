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

class PageDataCommand
{
    /**
     * @var array
     */
    private $pageData;

    /** @var View */
    private $view;

    public function __construct(array $pageData, View $view)
    {
        $this->pageData = $pageData;
        $this->view = $view;
    }

    /** @return void */
    public function execute()
    {
        echo $this->render();
    }

    private function render(): string
    {
        global $sn, $su, $plugin_tx;

        $this->view->actionUrl = "$sn?$su";
        $this->view->timestamp = time();
        $this->view->icon = new HtmlString(XH_helpIcon($plugin_tx['yanp']['tab_description_info']));
        $this->view->description = $this->pageData['yanp_description'];
        return $this->view->render('pdtab');
    }
}
