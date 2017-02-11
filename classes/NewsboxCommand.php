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
        global $h, $u, $cf, $sn, $pd_router, $plugin_cf, $plugin_tx;

        $view = new View('newsbox');
        $view->pageIds = $this->getPageIds();
        $view->headingTag = 'h' . min($cf['menu']['levels'] + 1, 6);
        $view->heading = function ($id) use ($h) {
            return new HtmlString($h[$id]);
        };
        $view->date = function ($id) use ($pd_router, $plugin_tx) {
            $pd = $pd_router->find_page($id);
            $timestamp = min(
                isset($pd['last_edit']) ? $pd['last_edit'] : 0,
                isset($pd['yanp_timestamp']) ? $pd['yanp_timestamp'] : 0
            );
            return date($plugin_tx['yanp']['news_date_format'], $timestamp);
        };
        $view->description = function ($id) use ($pd_router, $plugin_cf) {
            $pd = $pd_router->find_page($id);
            return $plugin_cf['yanp']['html_markup']
                ? new HtmlString($pd['yanp_description'])
                : $pd['yanp_description'];
        };
        $view->url = function ($id) use ($sn, $u) {
            return "$sn?{$u[$id]}";
        };
        return $view->render();
    }
}
