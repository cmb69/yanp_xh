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
<?php foreach ($this->checks as $check):?>
    <p class="xh_<?=$this->escape($check->state)?>"><?=$this->text($check->key, $check->param)?></p>
<?php endforeach?>
