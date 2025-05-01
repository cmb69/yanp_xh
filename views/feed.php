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
 * @var list<int> $pageIds
 * @var callable $itemHeading
 * @var callable $itemLink
 * @var callable $itemDescription
 * @var callable $itemGuid
 * @var callable $itemPubDate
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
    <copyright><?$this->text('feed_copyright')?></copyright>
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
<?foreach ($pageIds as $pageId):?>
    <item>
      <title><?=$this->raw($itemHeading($pageId))?></title>
      <link><?=$this->esc($itemLink($pageId))?></link>
      <description><?=$this->raw($itemDescription($pageId))?></description>
      <guid isPermaLink="false"><?=$this->esc($itemGuid($pageId))?></guid>
      <pubDate><?=$this->esc($itemPubDate($pageId))?></pubDate>
    </item>
<?endforeach?>
  </channel>
</rss>
