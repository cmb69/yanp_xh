<?php

/**
 * Back-end of Yanp_XH.
 * Copyright (c) 2011-2012 Christoph M. Becker (see license.txt)
 */
 

// utf-8 marker: äöüß


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


/**
 * Returns plugin version information.
 *
 * @return string
 */
function yanp_version() {
    global $pth;

    return '<h1>Yanp_XH</h1>'."\n"
	    .tag('img src="'.$pth['folder']['plugins'].'yanp/yanp.png" class="yanp_plugin_icon"')
	    .'<p>Version: '.YANP_VERSION.'</p>'."\n"
	    .'<p>Copyright &copy; 2011-2012 <a href="http://3-magi.net">Christoph M. Becker</a></p>'."\n"
	    .'<p class="yanp_license">This program is free software: you can redistribute it and/or modify'
	    .' it under the terms of the GNU General Public License as published by'
	    .' the Free Software Foundation, either version 3 of the License, or'
	    .' (at your option) any later version.</p>'."\n"
	    .'<p class="yanp_license">This program is distributed in the hope that it will be useful,'
	    .' but WITHOUT ANY WARRANTY; without even the implied warranty of'
	    .' MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the'
	    .' GNU General Public License for more details.</p>'."\n"
	    .'<p class="yanp_license">You should have received a copy of the GNU General Public License'
	    .' along with this program.  If not, see'
	    .' <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>'."\n";
}


/**
 * Returns requirements information.
 *
 * @return string
 */
function yanp_system_check() { // RELEASE-TODO
    global $pth, $tx, $plugin_tx, $plugin_cf;

    define('YANP_PHP_VERSION', '4.0.7');
    $ptx = $plugin_tx['yanp'];
    $imgdir = $pth['folder']['plugins'].'yanp/images/';
    $ok = tag('img src="'.$imgdir.'ok.png" alt="ok"');
    $warn = tag('img src="'.$imgdir.'warn.png" alt="warning"');
    $fail = tag('img src="'.$imgdir.'fail.png" alt="failure"');
    $htm = tag('hr').'<h4>'.$ptx['syscheck_title'].'</h4>'
	    .(version_compare(PHP_VERSION, YANP_PHP_VERSION) >= 0 ? $ok : $fail)
	    .'&nbsp;&nbsp;'.sprintf($ptx['syscheck_phpversion'], YANP_PHP_VERSION)
	    .tag('br').tag('br')."\n";
    foreach (array('date') as $ext) {
	$htm .= (extension_loaded($ext) ? $ok : $fail)
		.'&nbsp;&nbsp;'.sprintf($ptx['syscheck_extension'], $ext).tag('br')."\n";
    }
    $htm .= tag('br').(strtoupper($tx['meta']['codepage']) == 'UTF-8' ? $ok : $warn)
	    .'&nbsp;&nbsp;'.$ptx['syscheck_encoding'].tag('br')."\n";
    $htm .= (!get_magic_quotes_runtime() ? $ok : $warn)
	    .'&nbsp;&nbsp;'.$ptx['syscheck_magic_quotes'].tag('br').tag('br')."\n";
    foreach (array('config/', 'css/', 'languages/') as $folder) {
	$folders[] = $pth['folder']['plugins'].'yanp/'.$folder;
    }
    $folders[] = yanp_data_folder();
    foreach ($folders as $folder) {
	$htm .= (is_writable($folder) ? $ok : $warn)
		.'&nbsp;&nbsp;'.sprintf($ptx['syscheck_writable'], $folder).tag('br')."\n";
    }
    return $htm;
}


/**
 * Returns the xml for a single feed item.
 *
 * @param int $id  The number of the page.
 * @return string
 */
function yanp_rss_item($id) {
    global $pd_router, $sn, $u, $h, $c;
    
    $pd = $pd_router->find_page($id);
    $link = 'http://'.$_SERVER['SERVER_NAME'].$sn.'?'.$u[$id];
    $xml = '  <item>'."\n"
            .'    <title>'.$h[$id].'</title>'."\n"
            .'    <link>'.$link.'</link>'."\n"
            .'    <description>'.$pd['yanp_description'].'</description>'."\n"
            .'    <guid>'.$link.'</guid>'."\n"
            .'    <pubDate>'.date('r', yanp_timestamp($pd)).'</pubDate>'."\n"
	    .'  </item>'."\n";
    return $xml;
}


/**
 * Returns the xml of the complete rss feed.
 *
 * @return string
 */
function yanp_rss() {
    global $pth, $sn, $cf, $sl, $txc, $plugin_cf, $plugin_tx;
    
    $pcf =& $plugin_cf['yanp'];
    $ptx =& $plugin_tx['yanp'];
    $link = 'http://'.$_SERVER['SERVER_NAME'].$sn;
    $title = $ptx['feed_title'] == ''
	    ? (isset($txc['site']['title']) ? $txc['site']['title'] : $cf['site']['title'])
	    : $ptx['feed_title'];
    $desc = $ptx['feed_description'] == ''
	    ? (isset($txc['meta']['description']) ? $txc['meta']['description'] : $cf['meta']['description'])
	    : $ptx['feed_description'];
    $feed = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
	    .'<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'."\n"
	    .'<channel>'."\n"
	    .'  <title>'.$title.'</title>'."\n"
	    .'  <link>'.$link.'</link>'."\n"
	    .'  <description>'.$desc.'</description>'."\n"
	    .'  <language>'.$sl.'</language>'."\n"
	    .(!empty($ptx['feed_copyright']) ? '  <copyright>'.$ptx['feed_copyright'].'</copyright>'."\n" : '')
	    .'  <pubDate>'.date('r', filemtime($pth['file']['content'])).'</pubDate>'."\n"
	    .'  <generator>'.CMSIMPLE_XH_VERSION.' – Yanp_XH '.YANP_VERSION.'</generator>'."\n"
	    .'  <atom:link href="http://'.yanp_absolute_url(yanp_feed_filename()).'" rel="self" type="application/rss+xml"/>'."\n";
    if ($pcf['feed_image'] != '') {
	chkfile($pth['folder']['images'].$pcf['feed_image'], FALSE);
	$feed .= '  <image>'."\n"
	    .'    <url>'.$link.$cf['folders']['images'].$pcf['feed_image'].'</url>'."\n"
	    .'    <title>'.$title.'</title>'."\n"
	    .'    <link>'.$link.'</link>'."\n"
	    .'  </image>'."\n";
    }
    $feed .= yanp_items('yanp_rss_item')
	    .'</channel>'."\n"
	    .'</rss>'."\n";
    return $feed;
}


/**
 * Writes the rss feed to a file.
 *
 * @return void
 */
function yanp_write_rss() {
    global $pth, $sl, $plugin_cf;
    
    $pcf =& $plugin_cf['yanp'];
    $fn = yanp_data_folder().'feed-'.$sl.'.'.$pcf['feed_extension'];
    if (($fh = fopen($fn, 'w')) === FALSE || fwrite($fh, yanp_rss()) === FALSE) {
	e('cntwriteto', 'file', $fn);
    }
    if ($fh !== FALSE) {
	fclose($fh);
    }
}


/**
 * The pagedata hook
 */
$pd_router->add_interest('yanp_timestamp');
$pd_router->add_interest('yanp_description');
$pd_router->add_tab($plugin_tx['yanp']['tab_label'], $pth['folder']['plugins'].'yanp/yanp_view.php');


/**
 * Write the rss file.
 */
if ($plugin_cf['yanp']['feed_enabled']
	&& ($function == 'save'					// changes from the editor
	|| isset($menumanager) && $action == 'saverearranged'	// changes from menumanager
	|| isset($pagemanager) && $action == 'plugin_save'	// changes from pagemanager
	|| $s >= 0 && isset($_POST['save_page_data']))) {	// changes to pagedata
    yanp_write_rss();
}


/**
 * Plugin administration
 */
if (!empty($yanp)) {
    initvar('admin');
    initvar('action');
    
    $o .= print_plugin_admin('off');
    
    switch ($admin) {
	case '':
	    $o .= yanp_version().yanp_system_check();
	    break;
	default:
	    $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
