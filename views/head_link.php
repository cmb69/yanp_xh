<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $url
 */
?>

<link rel="alternate" type="application/rss+xml" title="<?=$this->text("feed_link_title")?>" href="<?=$this->esc($url)?>">
