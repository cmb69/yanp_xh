<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

namespace Yanp;

class PageDataCommand extends Command
{
    /**
     * @var array
     */
    protected $pageData;

    /**
     * @param array $pageData
     */
    public function __construct(array $pageData)
    {
        $this->pageData = $pageData;
    }

    public function execute()
    {
        echo $this->render();
    }

    /**
     * @return string
     */
    protected function render()
    {
        global $sn, $su, $plugin_tx;

        $view = new View('pdtab');
        $view->actionUrl = "$sn?$su";
        $view->timestamp = time();
        $view->icon = new HtmlString(XH_helpIcon($plugin_tx['yanp']['tab_description_info']));
        $view->description = $this->pageData['yanp_description'];
        return $view->render();
    }
}
