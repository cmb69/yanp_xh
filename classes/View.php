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
    /** @var string */
    private $templateFolder;

    /** @var array<string,string> */
    private $lang;

    /** @var array<string,mixed> */
    private $data = array();

    /** @param array<string,string> $lang */
    public function __construct(string $templateFolder, array $lang)
    {
        $this->templateFolder = $templateFolder;
        $this->lang = $lang;
    }

    /** @return mixed */
    public function __get(string $name)
    {
        return $this->data[$name];
    }

    public function __isset(string $name)
    {
        return isset($this->data[$name]);
    }

    /** @param array<mixed> $args */
    public function __call(string $name, array $args): string
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
        return vsprintf($this->lang[$key], $args);
    }

    /**
     * @param mixed $args
     */
    protected function plural(string $key, int $count, ...$args): string
    {
        if ($count == 0) {
            $key .= '_0';
        } else {
            $key .= XH_numberSuffix($count);
        }
        return vsprintf($this->lang[$key], $args);
    }

    /**
     * @param array<string,mixed> $data
     * @return void
     */
    public function render(string $_template, array $data)
    {
        $this->data = $data;
        unset($data);
        include $this->templateFolder . $_template . ".php";
    }

    /**
     * @param mixed $value
     */
    private function escape($value): string
    {
        if (is_scalar($value)) {
            return XH_hsc((string) $value);
        } else {
            return (string) $value;
        }
    }
}
