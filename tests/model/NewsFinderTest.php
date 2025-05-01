<?php

namespace Yanp\Model;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use XH\PageDataRouter;
use XH\Pages;

/** @small */
class NewsFinderTest extends TestCase
{
    /** @var Pages&Stub */
    private $pages;

    /** @var PageDataRouter&Stub */
    private $pageData;

    public function setUp(): void
    {
        $this->pages = $this->createStub(Pages::class);
        $this->pages->method("heading")->willReturnMap([
            [8, "Eight"],
            [15, "Fifteen"],
        ]);
        $this->pages->method("url")->willReturnMap([
            [8, "Eight"],
            [15, "Ten/Fifteen"],
        ]);
        $this->pageData = $this->createStub(PageDataRouter::class);
        $this->pageData->method("find_all")->willReturn([
            0 => [
                "published" => "0",
            ],
            8 => [
                "published" => "1",
                "last_edit" => strtotime("2025-04-30T22:06:00+00:00"),
                "yanp_timestamp" => strtotime("2025-04-30T22:06:00+00:00"),
                "yanp_description" => "description of eight",
            ],
            15 => [
                "published" => "1",
                "last_edit" => strtotime("2025-04-30T22:05:11+00:00"),
                "yanp_timestamp" => strtotime("2025-04-30T22:05:11+00:00"),
                "yanp_description" => "description of fifteen",
            ],
        ]);
    }

    private function sut(): NewsFinder
    {
        return new NewsFinder($this->pages, $this->pageData);
    }

    public function testFindsNews(): void
    {
        $expected = new News([
            8 => (object) [
                "title" => "Eight",
                "url" => "Eight",
                "mtime" => strtotime("2025-04-30T22:06:00+00:00"),
                "description" => "description of eight",
            ],
            15 => (object) [
                "title" => "Fifteen",
                "url" => "Ten/Fifteen",
                "mtime" => strtotime("2025-04-30T22:05:11+00:00"),
                "description" => "description of fifteen",
            ],
        ]);
        $news = $this->sut()->find($this->pages, $this->pageData);
        $this->assertEquals($expected, $news);
    }
}
