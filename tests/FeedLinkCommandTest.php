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
use Plib\View;

class FeedLinkCommandTest extends TestCase
{
    /** @var View */
    private $view;

    public function setUp(): void
    {
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["yanp"]);
    }

    private function sut(): FeedLinkCommand
    {
        return new FeedLinkCommand("./plugins/yanp/", "./templates/fhs-simple-2019/images", $this->view);
    }

    public function testRendersFeedLink(): void
    {
        $request = new FakeRequest();
        Approvals::verifyHtml($this->sut()->execute(null, $request));
    }

    public function testRendersFeedLinkWithTemplateImage(): void
    {
        $request = new FakeRequest();
        $this->assertStringContainsString(
            'src="./templates/fhs-simple-2019/imagesfeed.svg',
            $this->sut()->execute("feed.svg", $request)
        );
    }
}
