<?php

if (!isset($this)) {http_reponse_code(403); exit;}

?>

<!-- Yanp_XH info -->
<h1>Yanp <?=$this->version()?></h1>
<h2><?=$this->text('syscheck_title')?></h2>
<?php foreach ($this->checks as $check):?>
    <p class="xh_<?=$this->escape($check->state)?>"><?=$this->text($check->key, $check->param)?></p>
<?php endforeach?>
