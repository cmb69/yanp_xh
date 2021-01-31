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

class FeedlinkCommand extends Command
{
    /**
     * @var string
     */
    protected $icon;

    /**
     * @param string $icon
     */
    public function __construct($icon)
    {
        $this->icon = $icon;
    }

    public function execute()
    {
        echo $this->renderFeedLink();
    }

    /**
     * @return string
     */
    public function renderFeedLink()
    {
        global $pth;

        $view = new View();
        $view->feedUrl = $this->getFeedUrl();
        $view->icon = isset($this->icon)
            ? $pth['folder']['templateimages'] . $this->icon
            : $pth['folder']['plugins'].'yanp/images/feed.png';
        return $view->render('feed-link');
    }

    /**
     * @return string
     */
    protected function getFeedUrl()
    {
        global $sn;

        return $sn . '?&yanp_feed';
    }
}
