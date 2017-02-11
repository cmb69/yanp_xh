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
        global $pth, $plugin_tx;

        return '<h1>Yanp</h1>' . "\n"
            . tag(
                'img src="' . $pth['folder']['plugins'] . 'yanp/yanp.png"'
                . ' class="yanp_plugin_icon" alt="' . $plugin_tx['yanp']['alt_logo']
                . '"'
            )
            . '<p>Version: '.YANP_VERSION.'</p>' . "\n"
            . $this->renderLicense();
    }

    /**
     * @return string
     */
    protected function renderLicense()
    {
        return <<<EOT
<p>Copyright &copy; 2011-2017 <a href="http://3-magi.net/"
target="_blank">Christoph M. Becker</a></p>
<p class="yanp_license">This program is free software: you can redistribute it
and/or modify it under the terms of the GNU General Public License as published
by the Free Software Foundation, either version 3 of the License, or (at your
option) any later version.</p>
<p class="yanp_license">This program is distributed in the hope that it will be
useful, but <em>without any warranty</em>; without even the implied warranty of
<em>merchantability</em> or <em>fitness for a particular purpose</em>. See the
GNU General Public License for more details.</p>
<p class="yanp_license">You should have received a copy of the GNU General
Public License along with this program. If not, see <a
href="http://www.gnu.org/licenses/" target="_blank">
http://www.gnu.org/licenses/</a>.</p>
EOT;
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
