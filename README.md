# Yanp\_XH

YANP is the acronym for Yet Another News Plugin.
It facilitates semi-automatic handling of the news of a CMSimple\_XH website,
that could be shown as newsbox and made available as RSS feed.
Consider it an alternative to creating and maintaining newsboxes manually
or using a fully automated solution, such as WhatsNew or RSS Feed.
If you have further demands, consider using an advanced solution,
such as [News](https://davidstutz.de/projects/cmsimple-plugins/?News#news)
or [Realblog\_XH](https://github.com/cmb69/realblog_xh).

## Table of Contents

- [Requirements](#requirements)
- [Download](#download)
- [Installation](#installation)
- [Settings](#settings)
- [Usage](#usage)
- [Limitations](#limitations)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Requirements

Yanp\_XH is a plugin for CMSimple\_XH ≥ 1.7.0.
It requires PHP ≥ 5.4.0.

## Download

The [lastest release](https://github.com/cmb69/yanp_xh/releases/latest)
is available for download on Github.

## Installation

The installation is done as with many other CMSimple\_XH plugins. See the
[CMSimple\_XH wiki](https://wiki.cmsimple-xh.org/doku.php/installation)
for further details.

1.  Backup the data on your server.
2.  Unzip the distribution on your computer.
3.  Upload the whole directory `yanp/` to your server into
    the `plugins/` directory of CMSimple\_XH.
4.  Set write permissions for the subdirectories `config/`, `css/` and
    `languages/`.
5.  Navigate to `Plugins` → `Yanp` in the back-end to check if all
    requirements are fulfilled.

## Settings

The configuration of the plugin is done as with many other
CMSimple\_XH plugins in the back-end of the website.
Select `Plugins` → `Yanp`.

You can change the default settings of Yanp\_XH under `Config`.
Hints for the options will be displayed
when hovering over the help icon with your mouse.

Localization is done under `Language`.
You can translate the character strings to your own language
if there is no appropriate language file available,
or customize them according to your needs.
Particularly have a look at the entries in the `Feed` and `News` sections.
The possible format characters for `News` → `Date Format` are described in the
[PHP manual](https://www.php.net/manual/en/datetime.format.php).

The look of Yanp\_XH can be customized under `Stylesheet`,
or alternatively in the stylesheet of your template,
as the newsbox and the feed link are tied to that very closely.

## Usage

The news of Yanp\_XH are related to CMSimple\_XH pages.
Each page can have an entry in the news.
To control this, just switch to the tab `News` above the editor.
If you enter any text as description, the page will be added to the news.
If you delete the description, the page will be removed from the news.
The timestamp of the news is used for ordering these
(latest news will be on top).
The timestamp will be updated when you save the tab,
but it will not be more recent than the timestamp
of the last edit of the according page.
So, if you have made a typo in the page and correct it later,
the timestamp of the news will not be affected.
On the other hand, if you change the news later,
the timestamp will not be affected,
as long as you do not save the page.

### Displaying the newsbox

To display the newsbox you have to edit your template;
replace an already existing `newsbox()` call with:

````
<?=Yanp_newsbox()?>
````

or insert it, perhaps in addition to already existing `newsbox()` calls.

Furthermore it is possible to display the newsbox on a CMSimple\_XH page
by inserting the plugin call:

````
{{{PLUGIN:Yanp_newsbox();}}}
````

### Making the RSS feed available

The RSS feed is made available to many modern browsers automatically,
because a `<link rel="alternate">` tag is inserted
into the `<head>` of the pages of your website by Yanp\_XH.
To additionally display the RSS icon with a link to the RSS feed,
you have to insert the following into your template:

````
<?=Yanp_feedlink()?>
````

This basically works like `mailformlink()`.
If you want to display another feed icon,
just put it into the `images/` folder of your template,
and give its filename as parameter:

````
<?=Yanp_feedlink('filename.png')?>
````

In any case, you should [validate](https://www.rssboard.org/rss-validator/)
the RSS feed to be informed about possible problems.

## Limitations

When the website can be requested with www and without it
(e.g. `www.example.com` and `example.com`)
without redirecting one to the other,
the RSS feed may not link correctly back to itself.
It is generally recommended that you establish a 301 redirect
from `www.example.com` to `example.com` or vice versa.

## Troubleshooting
Report bugs and ask for support either on
[Github](https://github.com/cmb69/yanp_xh/issues)
or in the [CMSimple\_XH Forum](https://cmsimpleforum.com/).

## License

Yanp\_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Yanp\_XH is distributed in the hope that it will be useful,
but *without any warranty*; without even the implied warranty of
*merchantibility* or *fitness for a particular purpose*. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Yanp\_XH.  If not, see <https://www.gnu.org/licenses/>.

© 2011-2021 Christoph M. Becker

Danish translation © 2011-2012 Jens Maegard  
Slovak translation © 2011-2012 Dr. Martin Sereday  
Czech translation © 2012 Josef Němec

## Credits

The plugin logo was designed by
[cemagraphics](http://cemagraphics.deviantart.com/#/d28bkte).
This plugin uses feed icons from
[Perishable Press](https://perishablepress.com/press/2006/08/20/a-nice-collection-of-feed-icons/)
and Free Application icons from [Aha-Soft](https://www.aha-soft.com/).
Many thanks for publishing these icons as freeware.

Many thanks to the community at the [CMSimple\_XH forum](https://www.cmsimpleforum.com/)
for tips, suggestions and testing.

Last but not least many thanks to
[Peter Harteg](https://www.harteg.dk/), the “father” of CMSimple,
and all developers of [CMSimple\_XH](http://www.cmsimple-xh.org/)
without whom this amazing CMS would not exist.
