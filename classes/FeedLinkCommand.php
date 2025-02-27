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

class FeedLinkCommand
{
    /**
     * @var string|null
     */
    private $icon;

    /** @var View */
    private $view;

    /**
     * @param string|null $icon
     */
    public function __construct($icon, View $view)
    {
        $this->icon = $icon;
        $this->view = $view;
    }

    /** @return void */
    public function execute()
    {
        global $pth;

        $this->view->render('feed-link', [
            'feedUrl' => $this->getFeedUrl(),
            'icon' => isset($this->icon)
                ? $pth['folder']['templateimages'] . $this->icon
                : $pth['folder']['plugins'] . 'yanp/images/feed.svg',
        ]);
    }

    private function getFeedUrl(): string
    {
        global $sn;

        return $sn . '?&yanp_feed';
    }
}
