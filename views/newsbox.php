<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var array<int,object{title:string,url:string,mtime:int,description:string}> $pages
 * @var string $headingTag
 * @var callable $formatDate
 * @var callable $escapedDescription
 * @var callable $url
 */
?>

<!-- Yanp_XH newsbox -->
<?foreach ($pages as $pageId => $page):?>
<div class="yanp-news">
  <<?=$this->esc($headingTag)?>><?=$this->esc($page->title)?></<?=$this->esc($headingTag)?>>
  <p><em><?=$this->esc($formatDate($page->mtime))?></em></p>
  <p>
    <?=$this->raw($escapedDescription($page->description))?>
    <span class="read-more">
      <a href="<?=$this->esc($url($page->url))?>" title="<?=$this->esc($page->title)?>"><?=$this->plain('news_read_more')?></a>
    </span>
  </p>
</div>
<?endforeach?>
