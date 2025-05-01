<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $feedUrl
 * @var string $icon
 */
?>

<!-- Yanp_XH feed-link -->
<a class="yanp_feedlink" href="<?=$this->esc($feedUrl)?>">
  <img src="<?=$this->esc($icon)?>" alt="<?=$this->text('feed_link_title')?>" title="<?=$this->text('feed_link_title')?>">
</a>
