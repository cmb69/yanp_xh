<?php

/**
 * Copyright 2011-2021 Christoph M. Becker
 *
 * This file is part of Yanp_XH.
 *
 * Yanp_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Yanp_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Yanp_XH.  If not, see <http://www.gnu.org/licenses/>.
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

        $phpVersion = '5.4.0';
        $xhVersion = '1.6';
        $checks = array(
            (object) array(
                'state' => $this->getPhpVersionState($phpVersion),
                'key' => 'syscheck_phpversion',
                'param' => $phpVersion
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
