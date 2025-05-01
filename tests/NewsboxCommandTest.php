<?php

namespace Yanp;

use ApprovalTests\Approvals;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use Plib\View;
use Yanp\Model\News;
use Yanp\Model\NewsFinder;

class NewsboxCommandTest extends TestCase
{
    /** @var array<string,string> */
    private $conf;

    /** @var array<string,string> */
    private $lang;

    /** @var NewsFinder&Stub */
    private $newsFinder;

    /** @var View */
    private $view;

    public function setUp(): void
    {
        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["yanp"];
        $this->lang = XH_includeVar("./languages/en.php", "plugin_tx")["yanp"];
        $this->newsFinder = $this->createStub(NewsFinder::class);
        $this->view = new View("./views/", $this->lang);
    }

    private function sut(): NewsboxCommand
    {
        return new NewsboxCommand(
            $this->conf,
            $this->newsFinder,
            $this->view
        );
    }

    public function testRendersNewsbox(): void
    {
        $this->conf["html_markup"] = "";
        $this->newsFinder->method("find")->willReturn(new News(
            "",
            "",
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
                    "description" => "description of five & teen",
                ],
            ]
        ));
        $request = new FakeRequest();
        $response = $this->sut()->execute($request);
        Approvals::verifyHtml($response);
    }
}
