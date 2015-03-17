<?php

/**
 * The newsbox commands.
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
 * The newsbox commands.
 *
 * @category CMSimple_XH
 * @package  Yanp
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Yanp_XH
 */
class Yanp_NewsboxCommand extends Yanp_Command
{
    /**
     * Executes the command.
     *
     * @return string
     */
    public function execute()
    {
        echo $this->renderItems(array($this, 'renderNewsboxItem'));
    }

    /**
     * Returns the (X)HTML for the display of the page news.
     *
     * @param int $id The page number.
     *
     * @return string
     *
     * @global array  The headings of the pages.
     * @global array  The URLs of the pages.
     * @global array  The configuration of the core.
     * @global string The script name.
     * @global object The page data router.
     * @global array  The configuration of the plugins.
     * @global array  The localization of the plugins.
     */
    protected function renderNewsboxItem($id)
    {
        global $h, $u, $cf, $sn, $pd_router, $plugin_cf, $plugin_tx;

        $pcf = $plugin_cf['yanp'];
        $ptx = $plugin_tx['yanp'];
        $pd = $pd_router->find_page($id);
        $lvl = min($cf['menu']['levels'] + 1, 6);
        $desc = $pd['yanp_description'];
        if (!$pcf['html_markup']) {
            $desc = htmlspecialchars($desc, ENT_COMPAT, 'UTF-8');
        }
        $htm = '<div class="yanp-news">' . "\n"
            . '<h' . $lvl . '>' . $h[$id] . '</h' . $lvl . '>' . "\n"
            . '<p><em>' . date($ptx['news_date_format'], $this->timestamp($pd))
            .'</em></p>' . "\n"
            . '<p>' . $desc
            . ' <span class="read-more"><a href="' . $sn . '?' . $u[$id]
            . '" title="' . $h[$id] . '">'
            . $ptx['news_read_more'] . '</a></span></p>' . "\n"
            . '</div>' . "\n";
        return $htm;
    }

}

?>
