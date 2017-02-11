<h1>Yanp</h1>
<img src="<?=$this->logo?>" class="yanp_plugin_icon" alt="<?=$this->text('alt_logo')?>">
<p>Version: <?=$this->version?></p>
<p>
    Copyright &copy; 2011-2017 <a href="http://3-magi.net/"
    target="_blank">Christoph M. Becker</a>
</p>
<p class="yanp_license">
    This program is free software: you can redistribute it and/or modify it
    under the terms of the GNU General Public License as published by the Free
    Software Foundation, either version 3 of the License, or (at your option)
    any later version.
</p>
<p class="yanp_license">
    This program is distributed in the hope that it will be useful, but
    <em>without any warranty</em>; without even the implied warranty of
    <em>merchantability</em> or <em>fitness for a particular purpose</em>. See
    the GNU General Public License for more details.
</p>
<p class="yanp_license">
    You should have received a copy of the GNU General Public License along with
    this program. If not, see <a href="http://www.gnu.org/licenses/"
    target="_blank"> http://www.gnu.org/licenses/</a>.
</p>
<h4><?=$this->text('syscheck_title')?></h4>
<ul class="yanp_syscheck">
<?php foreach ($this->checks as $check):?>
    <li>
        <img src="<?=$this->stateIcon($check->state)?>" alt="<?=$this->text("syscheck_{$check->state}")?>" title="<?=$this->text("syscheck_{$check->state}")?>">
        <span><?=$this->text($check->key, $check->param)?></span>
    </li>
<?php endforeach?>
</ul>