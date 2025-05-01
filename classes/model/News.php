<?php

/**
 * Copyright (c) Christoph M. Becker
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

namespace Yanp\Model;

class News
{
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var int */
    private $mtime;

    /** @var array<int,object{title:string,url:string,mtime:int,description:string}> */
    private $pages = [];

    /** @param array<int,object{title:string,url:string,mtime:int,description:string}> $pages */
    public function __construct(string $title, string $description, int $mtime, array $pages)
    {
        $this->title = $title;
        $this->description = $description;
        $this->mtime = $mtime;
        uasort($pages, function ($page1, $page2) {
            return -($page1->mtime <=> $page2->mtime);
        });
        $this->pages = $pages;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function mtime(): int
    {
        return $this->mtime;
    }

    /** @return array<int,object{title:string,url:string,mtime:int,description:string}> */
    public function pages(int $count): array
    {
        if ($count >= 0) {
            return array_slice($this->pages, 0, $count, true);
        }
        return $this->pages;
    }
}
