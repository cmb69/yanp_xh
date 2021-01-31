<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Yanp_XH feed-link -->
<a href="<?=$this->feedUrl?>">
    <img src="<?=$this->icon?>" alt="<?=$this->text('feed_link_title')?>" title="<?=$this->text('feed_link_title')?>">
</a>
