<?php

namespace Yanp;

use ApprovalTests\Approvals;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use Plib\View;
use XH\Pages;

class NewsboxCommandTest extends TestCase
{
    /** @var array<string,string> */
    private $conf;

    /** @var array<string,string> */
    private $lang;

    /** @var Pages&Stub */
    private $pages;

    /** @var NewsService&Stub */
    private $newsService;

    /** @var View */
    private $view;

    public function setUp(): void
    {
        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["yanp"];
        $this->lang = XH_includeVar("./languages/en.php", "plugin_tx")["yanp"];
        $this->pages = $this->createStub(Pages::class);
        $this->newsService = $this->createStub(NewsService::class);
        $this->view = new View("./views/", $this->lang);
    }

    private function sut(): NewsboxCommand
    {
        return new NewsboxCommand(
            $this->conf,
            $this->pages,
            $this->newsService,
            $this->view
        );
    }

    public function testRendersNewsbox(): void
    {
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
        $request = new FakeRequest();
        $response = $this->sut()->execute($request);
        Approvals::verifyHtml($response);
    }
}
