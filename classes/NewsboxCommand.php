<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

namespace Yanp;

class NewsboxCommand extends Command
{
    /**
     * @return string
     */
    public function execute()
    {
        echo $this->renderItems(array($this, 'renderNewsboxItem'));
    }

    /**
     * @param int $id
     * @return string
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
            $desc = XH_hsc($desc);
        }
        $htm = '<div class="yanp-news">' . "\n"
            . '<h' . $lvl . '>' . $h[$id] . '</h' . $lvl . '>' . "\n"
            . '<p><em>' . date($ptx['news_date_format'], $this->getLastMod($pd))
            .'</em></p>' . "\n"
            . '<p>' . $desc
            . ' <span class="read-more"><a href="' . $sn . '?' . $u[$id]
            . '" title="' . $h[$id] . '">'
            . $ptx['news_read_more'] . '</a></span></p>' . "\n"
            . '</div>' . "\n";
        return $htm;
    }
}
