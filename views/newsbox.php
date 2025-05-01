<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var list<int> $pageIds
 * @var string $headingTag
 * @var callable $heading
 * @var callable $date
 * @var callable $description
 * @var callable $url
 */
?>

<!-- Yanp_XH newsbox -->
<?foreach ($pageIds as $pageId):?>
<div class="yanp-news">
  <<?=$this->esc($headingTag)?>><?=$this->raw($heading($pageId))?></<?=$this->esc($headingTag)?>>
  <p><em><?=$this->esc($date($pageId))?></em></p>
  <p>
    <?=$this->raw($description($pageId))?>
    <span class="read-more">
      <a href="<?=$this->esc($url($pageId))?>" title="<?=$this->raw($heading($pageId))?>"><?=$this->plain('news_read_more')?></a>
    </span>
  </p>
</div>
<?endforeach?>
