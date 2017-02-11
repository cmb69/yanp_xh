<?php foreach ($this->pageIds as $pageId):?>
<div class="yanp-news">
    <<?=$this->headingTag?>><?=$this->heading($pageId)?></<?=$this->headingTag?>>
    <p><em><?=$this->date($pageId)?></em></p>
    <p>
        <?=$this->description($pageId)?>
        <span class="read-more">
            <a href="<?=$this->url($pageId)?>" title="<?=$this->heading($pageId)?>"><?=$this->text('news_read_more')?></a>
        </span>
    </p>
</div>
<?php endforeach?>
