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
        global $tx, $sn, $su, $plugin_tx;

        $ptx = $plugin_tx['yanp'];
        $actionUrl = $sn . '?' . $su;
        $time = time();
        $icon = XH_helpIcon($ptx['tab_description_info']);
        $saveLabel = ucfirst($tx['action']['save']);
        return <<<EOT
<form id="yanp" action="$actionUrl" method="post" onsubmit="return true">
    <p><strong>$ptx[tab_form_label]</strong></p>
    <input type="hidden" name="yanp_timestamp" value="$time">
    <p>
        $icon
        <label for="yanp_description">$ptx[tab_description_label]</label><br>
        <textarea id="yanp_description" name="yanp_description" cols="40"
            row="10">{$this->pageData['yanp_description']}</textarea>
    </p>
    <input type="hidden" name="save_page_data">
    <p style="text-align: right">
        <input type="submit" value="$saveLabel">
    </p>
</form>
EOT;
    }
}
