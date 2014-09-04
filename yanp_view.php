<?php

/**
 * Pagedata view of Yanp_XH.
 * Copyright (c) 2011-2014 Christoph M. Becker (see license.txt)
 */


// utf-8-marker: äöüß


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


/**
 * Returns the (x)html of the pagedata form.
 *
 * @param  array $page	The page's data.
 * @return string
 */
function yanp_view($page) {
    global $tx, $sn, $su, $pth, $plugin_tx;

    $ptx = $plugin_tx['yanp'];
    $help_icon = tag('image src="'.$pth['folder']['plugins'].'yanp/images/help.png" alt="help"');
    $htm = '<form id="yanp" action="'.$sn.'?'.$su.'" method="post" onsubmit="return true">'."\n"
	    .'<p><strong>'.$ptx['tab_form_label'].'</strong></p>'."\n";
    $htm .= tag('input type="hidden" name="yanp_timestamp" value="'.time().'"');
    $htm .= '<a class="pl_tooltip" href="javascript:return false">'.$help_icon.'<span>'.$ptx['tab_description_info'].'</span></a>&nbsp;'
	.'<label for="yanp_description"><span>'.$ptx['tab_description_label'].'</span></label>'.tag('br')."\n";
    $htm .= '<textarea id="yanp_description" name="yanp_description" cols="40" row="10">'
	    .$page['yanp_description'].'</textarea>'."\n";

    $htm .= tag('input type="hidden" name="save_page_data"')."\n"
	    .'<div style="text-align: right">'."\n"
	    .tag('input type="submit" value="'.ucfirst($tx['action']['save']).'"')."\n"
	    .'</div></form>'."\n";
    return $htm;
}

?>
