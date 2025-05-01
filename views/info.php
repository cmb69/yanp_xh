<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $version
 * @var list<object{state:string,key:string,param:string}> $checks
 */
?>

<!-- Yanp_XH info -->
<h1>Yanp <?=$this->esc($version)?></h1>
<h2><?=$this->text('syscheck_title')?></h2>
<?foreach ($checks as $check):?>
  <p class="xh_<?=$this->esc($check->state)?>"><?=$this->text($check->key, $check->param)?></p>
<?endforeach?>
