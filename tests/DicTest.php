<?php

namespace Yanp;

use PHPUnit\Framework\TestCase;
use XH\PageDataRouter;

class DicTest extends TestCase
{
    public function setUp(): void
    {
        global $pd_router, $pth, $tx, $plugin_cf, $plugin_tx;
        $pd_router = $this->createStub(PageDataRouter::class);
        $pth = ["folder" => ["corestyle" => "", "plugins" => ""]];
        $tx = ["meta" => ["description" => ""], "site" => ["title" => ""]];
        $plugin_tx = ["yanp" => []];
        $plugin_cf = ["yanp" => ["entries_max" => "", "html_markup" => ""]];
    }

    public function testMakesRssCommand(): void
    {
        $this->assertInstanceOf(RssCommand::class, Dic::rssCommand());
    }

    public function testMakesNewsboxCommand(): void
    {
        $this->assertInstanceOf(NewsboxCommand::class, Dic::newsboxCommand());
    }

    public function testMakesFeedLinkCommand(): void
    {
        $this->assertInstanceOf(FeedLinkCommand::class, Dic::feedLinkCommand(null));
    }

    public function testMakesPageDataCommand(): void
    {
        $this->assertInstanceOf(PageDataCommand::class, Dic::pageDataCommand([]));
    }

    public function testMakesInfoCommand(): void
    {
        $this->assertInstanceOf(InfoCommand::class, Dic::infoCommand());
    }
}