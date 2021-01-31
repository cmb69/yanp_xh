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
    /** @var View */
    private $view;

    /**
     * @var string
     */
    private $output;

    public function __construct(View $view)
    {
        global $o;

        $this->view = $view;
        $this->output =& $o;
    }

    public function execute()
    {
        $this->output .= $this->render();
    }

    /**
     * @return string
     */
    private function render()
    {
        global $pth;

        $this->view->version = YANP_VERSION;
        $this->view->checks = $this->getSystemChecks();
        $this->view->stateIcon = function ($state) use ($pth) {
            return "{$pth['folder']['plugins']}yanp/images/$state.png";
        };
        return $this->view->render('info');
    }

    /**
     * @return string
     */
    private function getSystemChecks()
    {
        global $pth;

        $phpVersion = '5.4.0';
        $xhVersion = '1.7.0';
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
    private function getPhpVersionState($version)
    {
        return version_compare(PHP_VERSION, $version) >= 0 ? 'okay' : 'fail';
    }

    /**
     * @param string $version
     * @return string
     */
    private function getXhVersionState($version)
    {
        return version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version", 'gt') ? 'okay' : 'fail';
    }

    /**
     * @param string $filename
     * @return string
     */
    private function getWritabilityState($filename)
    {
        return is_writable($filename) ? 'okay' : 'warn';
    }
}
