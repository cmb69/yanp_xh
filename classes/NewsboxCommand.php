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
        echo $this->render();
    }

    /**
     * @return string
     */
    protected function render()
    {
        global $h, $u, $cf, $sn, $plugin_tx;

        $view = new View('newsbox');
        $view->pageIds = $this->getPageIds();
        $view->headingTag = 'h' . min($cf['menu']['levels'] + 1, 6);
        $view->heading = function ($id) use ($h) {
            return new HtmlString($h[$id]);
        };
        $view->date = function ($id) use ($plugin_tx) {
            return date($plugin_tx['yanp']['news_date_format'], $this->getLastMod($id));
        };
        $view->description = function ($id) {
            return $this->getDescription($id);
        };
        $view->url = function ($id) use ($sn, $u) {
            return "$sn?{$u[$id]}";
        };
        return $view->render();
    }
}
