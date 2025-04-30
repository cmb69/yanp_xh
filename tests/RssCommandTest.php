<?php

namespace Yanp;

use ApprovalTests\Approvals;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use XH\Pages;

class RssCommandTest extends TestCase
{
    /** @var string */
    private $imageFolder;

    /** @var string */
    private $contentFile;

    /** @var array<string,string> */
    private $conf;

    /** @var array<string,string> */
    private $lang;

    /** @var Pages&Stub */
    private $pages;

    /** @var NewsService&Stub */
    private $newsService;

    /** @var Feed */
    private $feed;

    /** @var View */
    private $view;

    public function setUp(): void
    {
        vfsStream::setup("root");
        $this->imageFolder = "";
        $this->contentFile = vfsStream::url("root/content.php");
        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["yanp"];
        $this->lang = XH_includeVar("./languages/en.php", "plugin_tx")["yanp"];
        $this->pages = $this->createStub(Pages::class);
        $this->newsService = $this->createStub(NewsService::class);
        $this->feed = new Feed("title", "description", $this->lang);
        $this->view = new View("./views/", $this->lang);
    }

    private function sut(): RssCommand
    {
        return new RssCommand(
            $this->imageFolder,
            $this->contentFile,
            $this->conf,
            $this->pages,
            $this->newsService,
            $this->feed,
            $this->view
        );
    }

    public function testRendersHeadLink(): void
    {
        global $plugin_tx;
        $plugin_tx["yanp"]["feed_link_title"] = "My Feed";
        $response = $this->sut()->execute(new FakeRequest());
        $this->assertSame(
            "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"My Feed\""
                . " href=\"http://example.com/?&yanp_feed\">\n",
            $response->hjs()
        );
    }

    public function testRendersFeed(): void
    {
        touch(vfsStream::url("root/content.php"), strtotime("2025-04-30T22:06:11+00:00"));
        $this->newsService->method("getPageIds")->willReturn([8, 15]);
        $this->newsService->method("getDescription")->willReturnMap([
            [8, "description of eight"],
            [15, "description of fifteen"],
        ]);
        $this->newsService->method("getLastMod")->willReturnMap([
            [8, strtotime("2025-04-30T22:06:00+00:00")],
            [15, strtotime("2025-04-30T22:05:11+00:00")],
        ]);
        $this->pages->method("heading")->willReturnMap([
            [8, "Eight"],
            [15, "Fifteen"],
        ]);
        $this->pages->method("url")->willReturnMap([
            [8, "Eight"],
            [15, "Ten/Fifteen"],
        ]);
        $request = new FakeRequest(["url" => "http://example.com/?&yanp_feed"]);
        $response = $this->sut()->execute($request);
        Approvals::verifyHtml($response->output());
    }
}