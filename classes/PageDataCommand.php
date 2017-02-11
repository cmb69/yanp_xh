<?php

/**
 * The page data commands.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Yanp
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Yanp_XH
 */

/**
 * The page data commands.
 *
 * @category CMSimple_XH
 * @package  Yanp
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Yanp_XH
 */
class Yanp_PageDataCommand extends Yanp_Command
{
    /**
     * The page data.
     *
     * @var array
     */
    protected $pageData;

    /**
     * Initializes a new instance.
     *
     * @param array $pageData An array of page data.
     */
    public function __construct(array $pageData)
    {
        $this->pageData = $pageData;
    }

    /**
     * Executes the command.
     *
     * @return void
     */
    public function execute()
    {
        echo $this->render();
    }

    /**
     * Returns the (X)HTML of the pagedata form.
     *
     * @return string
     *
     * @global array  The localization of the core.
     * @global string The script name.
     * @global string The requested page URL.
     * @global array  The configuration of the plugins.
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

?>
