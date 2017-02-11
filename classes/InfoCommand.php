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
        $this->output .= $this->render();
    }

    /**
     * @return string
     */
    protected function render()
    {
        global $pth;

        $view = new View('info');
        $view->logo = "{$pth['folder']['plugins']}yanp/yanp.png";
        $view->version = YANP_VERSION;
        $view->checks = $this->getSystemChecks();
        $view->stateIcon = function ($state) use ($pth) {
            return "{$pth['folder']['plugins']}yanp/images/$state.png";
        };
        return $view->render();
    }

    /**
     * @return string
     */
    protected function getSystemChecks()
    {
        global $pth;

        $phpVersion = '5.3.0';
        $xhVersion = '1.6';
        $checks = array(
            (object) array(
                'state' => $this->getPhpVersionState($phpVersion),
                'key' => 'syscheck_phpversion',
                'param' => $phpVersion
            ),
            (object) array(
                'state' => $this->getMagicQuotesState(),
                'key' => 'syscheck_magic_quotes',
                'param' => null
            ),
            (object) array(
                'state' => $this->getXhVersionState($xhVersion),
                'key' => 'syscheck_xhversion',
                'param' => $xhVersion
            )
        );
        foreach (array('config/', 'css/', 'languages/') as $folder) {
            $folders[] = "{$pth['folder']['plugins']}yanp/$folder";
        }
        foreach ($folders as $folder) {
            $checks[] = (object) array(
                'state' => $this->getWritabilityState($folder),
                'key' => 'syscheck_writable',
                'param' => $folder
            );
        }
        return $checks;
    }

    /**
     * @param string $version
     * @return string
     */
    protected function getPhpVersionState($version)
    {
        return version_compare(PHP_VERSION, $version) >= 0 ? 'okay' : 'fail';
    }

    /**
     * @return string
     */
    protected function getMagicQuotesState()
    {
        return get_magic_quotes_runtime() ? 'warn' : 'okay';
    }

    /**
     * @param string $version
     * @return string
     */
    protected function getXhVersionState($version)
    {
        return version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version", 'gt') ? 'okay' : 'fail';
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getWritabilityState($filename)
    {
        return is_writable($filename) ? 'okay' : 'warn';
    }
}
