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

use stdClass;

class InfoCommand
{
    /** @var View */
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /** @return void */
    public function execute()
    {
        global $pth;

        $this->view->render('info', [
            'version' => Plugin::VERSION,
            'checks' => $this->getSystemChecks(),
            'stateIcon' =>  function (string $state) use ($pth): string {
                return "{$pth['folder']['plugins']}yanp/images/$state.png";
            },
        ]);
    }

    /**
     * @return array<int,stdClass>
     */
    private function getSystemChecks(): array
    {
        global $pth;

        $phpVersion = '7.0.0';
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
        $folders = [];
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

    private function getPhpVersionState(string $version): string
    {
        return version_compare(PHP_VERSION, $version) >= 0 ? 'success' : 'fail';
    }

    private function getXhVersionState(string $version): string
    {
        return version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version") >= 0 ? 'success' : 'fail';
    }

    private function getWritabilityState(string $filename): string
    {
        return is_writable($filename) ? 'success' : 'warning';
    }
}
