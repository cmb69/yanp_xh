<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
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
        global $pth, $plugin_tx;

        $ptx = $plugin_tx['yanp'];
        $icon = isset($this->icon)
            ? $pth['folder']['templateimages'] . $this->icon
            : $pth['folder']['plugins'].'yanp/images/feed.png';
        $fn = $this->getFeedUrl();
        return '<a href="' . $fn . '">'
            . tag(
                'img src="' . $icon . '"'
                . ' alt="' . $ptx['feed_link_title'] . '" title="'
                . $ptx['feed_link_title'] . '"'
            ) . '</a>';
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
