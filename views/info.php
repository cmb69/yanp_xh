<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Yanp_XH info -->
<h1>Yanp</h1>
<p><?=$this->text('label_version')?>: <?=$this->version?></p>
<h2><?=$this->text('syscheck_title')?></h2>
<ul class="yanp_syscheck">
<?php foreach ($this->checks as $check):?>
    <li>
        <img src="<?=$this->stateIcon($check->state)?>" alt="<?=$this->text("syscheck_{$check->state}")?>" title="<?=$this->text("syscheck_{$check->state}")?>">
        <span><?=$this->text($check->key, $check->param)?></span>
    </li>
<?php endforeach?>
</ul>
