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

use XH\PageDataRouter as PageDataService;

class NewsService
{
    /** @var PageDataService */
    private $pageDataService;

    /** @var int */
    private $maxEntries;

    /** @var bool */
    private $isHtml;

    public function __construct(PageDataService $pageDataService, int $maxEntries, bool $isHtml)
    {
        $this->pageDataService = $pageDataService;
        $this->maxEntries = $maxEntries;
        $this->isHtml = $isHtml;
    }

    /**
     * @return (int|string)[]
     */
    public function getPageIds(): array
    {
        $allPageData = $this->pageDataService->find_all();
        /** @var array<int> */
        $ids = array_keys($allPageData);
        $dates = array_map(array($this, 'getLastMod'), $ids);
        array_multisort($dates, SORT_DESC, $ids);
        $ids = array_filter($ids, function ($id) use ($allPageData) {
            return $allPageData[$id]['published'] !== '0'
                && $allPageData[$id]['yanp_description'] != '';
        });
        if ($this->maxEntries >= 0) {
            $ids = array_slice($ids, 0, $this->maxEntries);
        }
        return $ids;
    }

    public function getLastMod(int $pageId): int
    {
        $pageData = $this->pageDataService->find_page($pageId);
        return min(
            isset($pageData['last_edit']) ? (int) $pageData['last_edit'] : 0,
            isset($pageData['yanp_timestamp']) ? (int) $pageData['yanp_timestamp'] : 0
        );
    }

    /**
     * @return string|HtmlString
     */
    public function getDescription(int $pageId)
    {
        $pageData = $this->pageDataService->find_page($pageId);
        return $this->isHtml
            ? new HtmlString($pageData['yanp_description'])
            : $pageData['yanp_description'];
    }
}
