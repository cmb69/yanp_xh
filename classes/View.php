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

    public function __get(string $name)
    {
        return $this->data[$name];
    }

    public function __isset(string $name)
    {
        return isset($this->data[$name]);
    }

    public function __call(string $name, array $args)
    {
        if (is_callable($this->data[$name])) {
            return $this->escape($this->data[$name](...$args));
        }
        return $this->escape($this->data[$name]);
    }

    /**
     * @param mixed $args
     */
    protected function text(string $key, ...$args): string
    {
        global $plugin_tx;

        return vsprintf($plugin_tx['yanp'][$key], $args);
    }

    /**
     * @param mixed $args
     */
    protected function plural(string $key, int $count, ...$args): string
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
     * @param array<string,mixed> $data
     * @return void
     */
    public function render(string $_template, array $data)
    {
        global $pth;

        $this->data = $data;
        unset($data);
        /** @psalm-suppress UnresolvableInclude */
        include "{$pth['folder']['plugins']}yanp/views/{$_template}.php";
    }

    /**
     * @param mixed $value
     */
    private function escape($value): string
    {
        if (is_scalar($value)) {
            return XH_hsc((string) $value);
        } else {
            return $value;
        }
    }
}
