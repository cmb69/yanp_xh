<?php

/**
 * @copyright 2011-2017 Christoph M. Becker <http://3-magi.net/>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 */

namespace Yanp;

class InfoCommand
{
    /**
     * @var string
     */
    protected $output;

    public function __construct()
    {
        global $o;

        $this->output =& $o;
    }

    public function execute()
    {
        $this->output .= $this->renderVersion() . $this->renderSystemCheck();
    }

    /**
     * @return string
     */
    protected function renderVersion()
    {
        global $pth;

        $view = new View('info');
        $view->logo = "{$pth['folder']['plugins']}yanp/yanp.png";
        $view->version = YANP_VERSION;
        return $view->render();
    }

    /**
     * @return string
     */
    protected function renderSystemCheck()
    {
        global $pth, $plugin_tx;

        $phpVersion = '5.3.0';
        $xhVersion = '1.6';
        $ptx = $plugin_tx['yanp'];
        $htm = '<h4>' . $ptx['syscheck_title'] . '</h4>'
            . XH_message($this->getPhpVersionState($phpVersion), $ptx['syscheck_phpversion'], $phpVersion);
        foreach (array('pcre', 'spl') as $ext) {
            $htm .= XH_message($this->getExtensionState($ext), $ptx['syscheck_extension'], $ext);
        }
        $htm .= XH_message($this->getMagicQuotesState(), $ptx['syscheck_magic_quotes']);
        $htm .= XH_message($this->getXhVersionState($xhVersion), $ptx['syscheck_xhversion'], $xhVersion);
        foreach (array('config/', 'css/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'yanp/' . $folder;
        }
        foreach ($folders as $folder) {
            $htm .= XH_message($this->getWritabilityState($folder), $ptx['syscheck_writable'], $folder);
        }
        return $htm;
    }

    /**
     * @param string $version
     * @return string
     */
    protected function getPhpVersionState($version)
    {
        return version_compare(PHP_VERSION, $version) >= 0
            ? 'success'
            : 'fail';
    }

    /**
     * @param string $extension
     * @return string
     */
    protected function getExtensionState($extension)
    {
        return extension_loaded($extension)
            ? 'success'
            : 'fail';
    }

    /**
     * @return string
     */
    protected function getMagicQuotesState()
    {
        return get_magic_quotes_runtime()
            ? 'warning'
            : 'success';
    }

    /**
     * @param string $version
     * @return string
     */
    protected function getXhVersionState($version)
    {
        return defined('CMSIMPLE_XH_VERSION')
            && strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') === 0
            && version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version", 'gt')
            ? 'success'
            : 'fail';
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getWritabilityState($filename)
    {
        return is_writable($filename)
            ? 'success'
            : 'warning';
    }
}
