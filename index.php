<?php

/**
 * Front-end of Yanp_XH.
 * Copyright (c) 2011-2013 Christoph M. Becker (see license.txt)
 */


// utf-8 marker: äöüß


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


define('YANP_VERSION', '1pl3');


/**
 * Returns the absolute URL.
 *
 * @param string $url  A relative URL.
 * @return string
 */
function yanp_absolute_url($url) {
    global $sn;

    $parts = explode('/', $sn.$url);
    $i = 0;
    while ($i < count($parts)) {
	switch ($parts[$i]) {
	    case '.':
		array_splice($parts, $i, 1);
		break;
	    case '..':
		array_splice($parts, $i-1, 2);
		$i--;
		break;
	    default:
		$i++;
	}
    }
    return $_SERVER['SERVER_NAME'].implode('/', $parts);
}


/**
 * Returns the data folder.
 *
 * @return string
 */
function yanp_data_folder() {
    global $pth, $plugin_cf;

    $pcf = $plugin_cf['yanp'];

    $fn = $pcf['folder_data'] == ''
	    ? $pth['folder']['plugins'].'yanp/data/'
	    : $pth['folder']['base'].$pcf['folder_data'];
    if (substr($fn, -1) != '/') {
	$fn .= '/';
    }
    if (file_exists($fn)) {
	if (!is_dir($fn)) {
	    e('cntopen', 'folder', $fn);
	}
    } else {
	if (!mkdir($fn, 0777, TRUE)) {
	    e('cntwriteto', 'folder', $fn);
	}
    }
    return $fn;
}


/**
 * Returns the relative file name of the feed file.
 *
 * @return string
 */
function yanp_feed_filename() {
    global $sl, $plugin_cf;

    return yanp_data_folder().'feed-'.$sl.'.'.$plugin_cf['yanp']['feed_extension'];
}


/**
 * Returns the timestamp of the news of the given pagedata page $pd.
 *
 * @param array $pd
 * @return int
 */
function yanp_timestamp($pd) {
    return empty($pd['last_edit']) ? (empty($pd['yanp_timestamp']) ? 0 : $pd['yanp_timestamp'])
	    : min($pd['yanp_timestamp'], $pd['last_edit']);
}


/**
 * Inserts the alternate link for the RSS feed into the head.
 *
 * @return void
 */
function yanp_head_link() {
    global $hjs, $plugin_cf, $plugin_tx;

    $fn = yanp_feed_filename();
    if ($plugin_cf['yanp']['feed_enabled']
	    && file_exists($fn)) {
	$hjs .= tag('link rel="alternate" type="application/rss+xml"'
		.' title="'.$plugin_tx['yanp']['feed_link_title'].'"'
		.' href="http://'.yanp_absolute_url($fn).'"')."\n";
    }
}


/**
 * Returns the (x)html for the display of the page news.
 *
 * @param int $id  The page number.
 * @return string
 */
function yanp_newsbox_item($id) {
    global $h, $u, $cf, $sn, $pd_router, $plugin_cf, $plugin_tx;

    $pcf = $plugin_cf['yanp'];
    $ptx = $plugin_tx['yanp'];
    $pd = $pd_router->find_page($id);
    $lvl = min($cf['menu']['levels'] + 1, 6);
    $desc = $pd['yanp_description'];
    if (!$pcf['html_markup']) {
	$desc = htmlspecialchars($desc, ENT_COMPAT, 'UTF-8');
    }
    $htm = '<div class="yanp-news">'."\n"
	    .'<h'.$lvl.'>'.$h[$id].'</h'.$lvl.'>'."\n"
	    .'<p><em>'.date($ptx['news_date_format'], yanp_timestamp($pd)).'</em></p>'."\n"
	    .'<p>'.$desc
	    .' <span class="read-more"><a href="'.$sn.'?'.$u[$id].'" title="'.$h[$id].'">'
	    .$ptx['news_read_more'].'</a></span></p>'."\n"
	    .'</div>'."\n";
    return $htm;
}


/**
 * Returns the accumulated result of calling $func on all news.
 *
 * @param string $func  The callback function.
 * @return string
 */
function yanp_items($func) {
    global $pd_router;

    $pd = $pd_router->find_all();
    $ids = array_keys($pd);
    $dates = array_map(create_function('$elt', 'return yanp_timestamp($elt);'), $pd);
    array_multisort($dates, SORT_DESC, $ids);
    $res = '';
    foreach ($ids as $id) {
	if (!empty($pd[$id]['yanp_description'])) {
	    $res .= call_user_func($func, $id);
	}
    }
    return $res;
}


/**
 * Returns the (x)html for the display of the complete newsbox.
 *
 * @access public
 * @return string
 */
function yanp_newsbox() {
    return yanp_items('yanp_newsbox_item');
}


/**
 * Returns the (x)html of the rss link.
 *
 * @access public
 * @param string $icon
 * @return string
 */
function yanp_feedlink($icon = NULL) {
    global $pth, $plugin_cf, $plugin_tx, $sl;

    $pcf = $plugin_cf['yanp'];
    $ptx = $plugin_tx['yanp'];
    $icon = isset($icon)
	    ? $pth['folder']['templateimages'].$icon
	    : $pth['folder']['plugins'].'yanp/images/feed.png';
    $fn = yanp_feed_filename();
    if (file_exists($fn)) {
	return '<a href="'.$fn.'">'
		.tag('img src="'.$icon.'"'
		.' alt="'.$ptx['feed_link_title'].'" title="'.$ptx['feed_link_title'].'"').'</a>';
    } else {
	return '';
    }
}


/**
 * Insert rss <link> to <head>.
 */
yanp_head_link();

?>
