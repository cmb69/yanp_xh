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

class View
{
    /** @var array<string,mixed> */
    private $data = array();

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function __get($name)
    {
        return $this->escape($this->data[$name]);
    }

    /**
     * @param string $name
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * @param string $name
     */
    public function __call($name, array $args)
    {
        $callable = $this->data[$name];
        return $this->escape($callable(...$args));
    }

    /**
     * @param string $key
     * @param mixed $args
     * @return string
     */
    protected function text($key, ...$args)
    {
        global $plugin_tx;

        return vsprintf($plugin_tx['yanp'][$key], $args);
    }

    /**
     * @param string $key
     * @param int $count
     * @param mixed $args
     * @return string
     */
    protected function plural($key, $count, ...$args)
    {
        global $plugin_tx;

        if ($count == 0) {
            $key .= '_0';
        } else {
            $key .= XH_numberSuffix($count);
        }
        return vsprintf($plugin_tx['yanp'][$key], $args);
    }

    /**
     * @param string $_template
     * @return string
     */
    public function render($_template)
    {
        global $pth;

        ob_start();
        /** @psalm-suppress UnresolvableInclude */
        include "{$pth['folder']['plugins']}yanp/views/{$_template}.php";
        return ob_get_clean();
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function escape($value)
    {
        if (is_scalar($value)) {
            return XH_hsc((string) $value);
        } else {
            return $value;
        }
    }
}
