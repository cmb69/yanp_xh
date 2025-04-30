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

class PageDataCommand
{
    /** @var string */
    private $coreStyleFolder;

    /**
     * @var array<mixed>
     */
    private $pageData;

    /** @var View */
    private $view;

    /** @param array<mixed> $pageData */
    public function __construct(string $coreStyleFolder, array $pageData, View $view)
    {
        $this->coreStyleFolder = $coreStyleFolder;
        $this->pageData = $pageData;
        $this->view = $view;
    }

    public function execute(Request $request): string
    {
        global $sn, $su;

        return $this->view->render('pdtab', [
            'actionUrl' => "$sn?$su",
            'timestamp' => $request->time(),
            'icon' => $this->coreStyleFolder . "help_icon.svg",
            'description' => $this->pageData['yanp_description'],
        ]);
    }
}
