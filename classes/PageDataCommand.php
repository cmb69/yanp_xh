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
     * @var array<mixed>
     */
    private $pageData;

    /** @var View */
    private $view;

    /** @param array<mixed> $pageData */
    public function __construct(array $pageData, View $view)
    {
        $this->pageData = $pageData;
        $this->view = $view;
    }

    /** @return void */
    public function execute()
    {
        global $sn, $su, $plugin_tx;

        $this->view->render('pdtab', [
            'actionUrl' => "$sn?$su",
            'timestamp' => time(),
            'icon' => new HtmlString(XH_helpIcon($plugin_tx['yanp']['tab_description_info'])),
            'description' => $this->pageData['yanp_description'],
        ]);
    }
}
