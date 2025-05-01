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

use XH\PageDataRouter;
use XH\Pages;

class NewsFinder
{
    /** @var string */
    private $contentFile;

    /** @var array<string,array<string,string>> */
    private $coreLang;

    /** @var array<string,string> */
    private $lang;

    /** @var Pages */
    private $pages;

    /** @var PageDataRouter */
    private $pageData;

    /**
     * @param array<string,array<string,string>> $coreLang
     * @param array<string,string> $lang
     */
    public function __construct(
        string $contentFile,
        array $coreLang,
        array $lang,
        Pages $pages,
        PageDataRouter $pageData
    ) {
        $this->contentFile = $contentFile;
        $this->coreLang = $coreLang;
        $this->lang = $lang;
        $this->pages = $pages;
        $this->pageData = $pageData;
    }

    public function find(): News
    {
        $title = $this->lang["feed_title"] ?: $this->coreLang["site"]["title"];
        $description = $this->lang["feed_description"] ?: $this->coreLang["meta"]["description"];
        $mtime = (int) filemtime($this->contentFile);
        $newsPages = [];
        foreach ($this->pageData->find_all() as $id => $data) {
            if ($data["published"] !== "0" && $data["yanp_description"] != "") {
                $newsPages[$id] = (object) [
                    "title" => $this->pages->heading($id),
                    "url" => $this->pages->url($id),
                    "mtime" => min(
                        isset($data["last_edit"]) ? (int) $data["last_edit"] : 0,
                        isset($data["yanp_timestamp"]) ? (int) $data["yanp_timestamp"] : 0
                    ),
                    "description" => $data["yanp_description"],
                ];
            }
        }
        return new News($title, $description, $mtime, $newsPages);
    }
}
