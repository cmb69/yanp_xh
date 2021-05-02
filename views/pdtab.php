<?php
if (!isset($this)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
?>

<!-- Yanp_XH pdtab -->
<form id="yanp" action="<?=$this->actionUrl()?>" method="post" onsubmit="return true">
    <p><strong><?=$this->text('tab_form_label')?></strong></p>
    <input type="hidden" name="yanp_timestamp" value="<?=$this->timestamp()?>">
    <p>
        <?=$this->icon()?>
        <label for="yanp_description"><?=$this->text('tab_description_label')?></label><br>
        <textarea id="yanp_description" name="yanp_description" cols="40"
                  row="10"><?=$this->description()?></textarea>
    </p>
    <p style="text-align: right">
        <input type="submit" name="save_page_data" value="<?=$this->text('tab_button')?>">
    </p>
</form>
