<?php

if (!isset($this)) {http_reponse_code(403); exit;}

?>

<!-- Yanp_XH feed-link -->
<a class="yanp_feedlink" href="<?=$this->feedUrl()?>">
    <img src="<?=$this->icon()?>" alt="<?=$this->text('feed_link_title')?>" title="<?=$this->text('feed_link_title')?>">
</a>
