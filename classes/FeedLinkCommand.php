<?php

/**
 * The feedlink commands.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Yanp
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2011-2015 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Yanp_XH
 */

/**
 * The feedlink commands.
 *
 * @category CMSimple_XH
 * @package  Yanp
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Yanp_XH
 */
class Yanp_FeedlinkCommand extends Yanp_Command
{
    /**
     * The icon filename.
     *
     * @var string
     */
    protected $icon;

    /**
     * Initializes a new instance.
     *
     * @param string $icon An icon filename.
     */
    public function __construct($icon)
    {
        $this->icon = $icon;
    }

    /**
     * Executes the command.
     *
     * @return void
     */
    public function execute()
    {
        echo $this->renderFeedLink();
    }

    /**
     * Returns the (X)HTML of the RSS link.
     *
     * @return string
     *
     * @global array  The paths of system files and folders.
     * @global array  The localization of the plugins.
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
     * Returns the relative file name of the feed file.
     *
     * @return string
     *
     * @global string The script name.
     */
    protected function getFeedUrl()
    {
        global $sn;

        return $sn . '?&yanp_feed';
    }

}

?>
