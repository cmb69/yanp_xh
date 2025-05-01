<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $actionUrl
 * @var int $timestamp
 * @var string $icon
 * @var string $descripption
 */
?>

<!-- Yanp_XH pdtab -->
<form id="yanp" action="<?=$this->esc($actionUrl)?>" method="post" onsubmit="return true">
  <p><strong><?=$this->text('tab_form_label')?></strong></p>
  <input type="hidden" name="yanp_timestamp" value="<?=$timestamp?>">
  <p>
    <div class="pl_tooltip">
      <img src="<?=$this->esc($icon)?>">
      <div><?=$this->text('tab_description_info')?></div>
    </div>
    <label for="yanp_description"><?=$this->text('tab_description_label')?></label><br>
    <textarea id="yanp_description" name="yanp_description" cols="40" row="10"><?=$this->esc($description)?></textarea>
  </p>
  <p style="text-align: right">
    <input type="submit" name="save_page_data" value="<?=$this->text('tab_button')?>">
  </p>
</form>
