<rss version="2.0">
    <channel>
        <title><?=$this->title?></title>
        <link><?=$this->link?></link>
        <description><?=$this->description?></description>
        <language><?=$this->language?></language>
<?php if ($this->text('feed_copyright')):?>
        <copyright><?$this->text('feed_copyright')?></copyright>
<?php endif?>
        <pubDate><?=$this->pubDate?></pubDate>
        <generator><?=$this->generator?></generator>
<?php if ($this->hasImage):?>
        <image>
            <url><?=$this->imageUrl?></url>
            <title><?=$this->title?></title>
            <link><?=$this->link?></link>
        </image>
<?php endif?>
<?php foreach ($this->pageIds as $pageId):?>
        <item>
            <title><?=$this->itemHeading($pageId)?></title>
            <link><?=$this->itemLink($pageId)?></link>
            <description><?=$this->itemDescription($pageId)?></description>
            <guid isPermaLink="false"><?=$this->itemGuid($pageId)?></guid>
            <pubDate><?=$this->itemPubDate($pageId)?></pubDate>
        </item>
<?php endforeach?>
    </channel>
</rss>
