<?php

/**
 * Copyright 2021 Christoph M. Becker
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

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;

class PageDataCommandTest extends TestCase
{
    public function testRendersPageDataTab(): void
    {
        global $pth, $tx, $plugin_tx;

        $pth["folder"]["corestyle"] = "../../assets/css/";
        $tx["editmenu"]["help"] = "Help";
        $plugin_tx = ['yanp' => ['tab_description_info' => ""]];
        $view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["yanp"]);
        $subject = new PageDataCommand(['yanp_description' => ''], $view);
        $request = new FakeRequest(["time" => strtotime("2025-04-30T15:45:43+00:00")]);
        Approvals::verifyHtml($subject->execute($request));
    }
}
