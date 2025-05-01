<?php

namespace Yanp;

use ApprovalTests\Approvals;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use Plib\Logger;
use Plib\View;
use Yanp\Model\News;
use Yanp\Model\NewsFinder;

class RssCommandTest extends TestCase
{
    /** @var string */
    private $imageFolder;

    /** @var array<string,string> */
    private $conf;

    /** @var array<string,string> */
    private $lang;

    /** @var NewsFinder&Stub */
    private $newsFinder;

    /** @var Logger&MockObject */
    private $logger;

    /** @var View */
    private $view;

    public function setUp(): void
    {
        $this->imageFolder = "";
        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["yanp"];
        $this->lang = XH_includeVar("./languages/en.php", "plugin_tx")["yanp"];
        $this->newsFinder = $this->createStub(NewsFinder::class);
        $this->logger = $this->createMock(Logger::class);
        $this->view = new View("./views/", $this->lang);
    }

    private function sut(): RssCommand
    {
        return new RssCommand(
            "./",
            $this->imageFolder,
            $this->conf,
            $this->newsFinder,
            $this->logger,
            $this->view
        );
    }

    public function testRendersHeadLink(): void
    {
        $response = $this->sut()->execute(new FakeRequest());
        $this->assertSame(
            "\n<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS feed\""
                . " href=\"http://example.com/?&amp;yanp_feed\">\n",
            $response->hjs()
        );
    }

    public function testRendersFeed(): void
    {
        $this->imageFolder = "./userfiles/images/";
        $this->conf["html_markup"] = "";
        $this->conf["feed_image"] = "logo.png";
        $this->lang["feed_copyright"] = "Christoph M. Becker";
        $this->view = new View("./views/", $this->lang);
        $this->newsFinder->method("find")->willReturn(new News(
            "title",
            "description",
            strtotime("2025-04-30T22:06:11+00:00"),
            $this->pages()
        ));
        $request = new FakeRequest(["url" => "http://example.com/?&yanp_feed"]);
        $response = $this->sut()->execute($request);
        Approvals::verifyHtml($response->output());
    }

    public function testRendersNoHeadLinkIfDisabled(): void
    {
        $this->conf["feed_enabled"] = "";
        $response = $this->sut()->execute(new FakeRequest());
        $this->assertNull($response->hjs());
    }

    public function testRendersNoFeedIfDisable(): void
    {
        $this->conf["feed_enabled"] = "";
        $request = new FakeRequest(["url" => "http://example.com/?&yanp_feed"]);
        $response = $this->sut()->execute($request);
        $this->assertSame("", $response->output());
    }

    /** @requires extension dom */
    public function testHandlesBrokenFeed(): void
    {
        // somehow break the feed
        vfsStream::setup("root");
        touch(vfsStream::url("root/feed.php"));
        $this->view = new View(vfsStream::url("root/"), $this->lang);
        $request = new FakeRequest(["url" => "http://example.com/?&yanp_feed"]);
        $this->logger->expects($this->once())->method("log")->with("error", "RSS feed", "the RSS feed is invalid");
        $response = $this->sut()->execute($request);
        $this->assertSame(503, $response->status());
        $this->assertSame("Sorry, we are currently unable to deliver the RSS feed!", $response->output());
    }

    private function pages(): array
    {
        return [
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
                "description" => "description of five & teen",
            ],
        ];
    }
}
