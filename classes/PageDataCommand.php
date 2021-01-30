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

class PageDataCommand extends Command
{
    /**
     * @var array
     */
    protected $pageData;

    /**
     * @param array $pageData
     */
    public function __construct(array $pageData)
    {
        $this->pageData = $pageData;
    }

    public function execute()
    {
        echo $this->render();
    }

    /**
     * @return string
     */
    protected function render()
    {
        global $sn, $su, $plugin_tx;

        $view = new View('pdtab');
        $view->actionUrl = "$sn?$su";
        $view->timestamp = time();
        $view->icon = new HtmlString(XH_helpIcon($plugin_tx['yanp']['tab_description_info']));
        $view->description = $this->pageData['yanp_description'];
        return $view->render();
    }
}
