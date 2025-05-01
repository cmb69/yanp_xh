<?php

namespace Yanp\Model;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use XH\PageDataRouter;
use XH\Pages;

/** @small */
class NewsFinderTest extends TestCase
{
    /** @var array<string,array<string,string>> */
    private $coreLang;

    /** @var array<string,string> */
    private $lang;

    /** @var Pages&Stub */
    private $pages;

    /** @var PageDataRouter&Stub */
    private $pageData;

    public function setUp(): void
    {
        vfsStream::setup("root");
        touch(vfsStream::url("root/content.htm"), strtotime("2025-04-30T22:06:11+00:00"));
        $this->coreLang = XH_includeVar("../../cmsimple/languages/en.php", "tx");
        $this->lang = XH_includeVar("./languages/en.php", "plugin_tx")["yanp"];
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
        return new NewsFinder(
            vfsStream::url("root/content.htm"),
            $this->coreLang,
            $this->lang,
            $this->pages,
            $this->pageData
        );
    }

    public function testFindsNews(): void
    {
        $expected = new News(
            "English Site Title",
            "Enter website description for search engine results here",
            strtotime("2025-04-30T22:06:11+00:00"),
            [
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
            ]
        );
        $news = $this->sut()->find();
        $this->assertEquals($expected, $news);
    }
}
