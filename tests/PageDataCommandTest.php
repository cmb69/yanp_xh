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

use PHPUnit\Framework\TestCase;

class PageDataCommandTest extends TestCase
{
    public function testExecutionRendersTemplate(): void
    {
        global $plugin_tx;

        $plugin_tx = ['yanp' => ['tab_description_info' => ""]];
        $view = $this->createMock(View::class);
        $subject = new PageDataCommand(['yanp_description' => ''], $view);
        $view->expects($this->once())
            ->method('render')
            ->with($this->equalTo('pdtab'));
        $subject->execute();
    }
}
