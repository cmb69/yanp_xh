<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $title
 * @var string $link
 * @var string $description
 * @var string $language
 * @var string $pubDate
 * @var string $generator
 * @var bool $hasImage
 * @var string $imageUrl
 * @var array<int,object{title:string,url:string,mtime:int,description:string}> $pages
 * @var callable $itemLink
 * @var callable $escapedItemDescription
 * @var callable $itemGuid
 * @var callable $formatDate
 */
?>

<!-- Yanp_XH feed -->
<rss version="2.0">
  <channel>
    <title><?=$this->esc($title)?></title>
    <link><?=$this->esc($link)?></link>
    <description><?=$this->esc($description)?></description>
    <language><?=$this->esc($language)?></language>
<?if ($this->text('feed_copyright')):?>
    <copyright><?=$this->text('feed_copyright')?></copyright>
<?endif?>
    <pubDate><?=$this->esc($pubDate)?></pubDate>
    <generator><?=$this->esc($generator)?></generator>
<?if ($hasImage):?>
    <image>
      <url><?=$this->esc($imageUrl)?></url>
      <title><?=$this->esc($title)?></title>
      <link><?=$this->esc($link)?></link>
    </image>
<?endif?>
<?foreach ($pages as $pageId => $page):?>
    <item>
      <title><?=$this->esc($page->title)?></title>
      <link><?=$this->esc($itemLink($page->url))?></link>
      <description><?=$this->raw($escapedItemDescription($page->description))?></description>
      <guid isPermaLink="false"><?=$this->esc($itemGuid($page->url, $page->mtime))?></guid>
      <pubDate><?=$this->esc($formatDate($page->mtime))?></pubDate>
    </item>
<?endforeach?>
  </channel>
</rss>
