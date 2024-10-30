=== Books & Papers ===
Contributors: frier, engraver
Donate link: none
Tags: academic, publication, paper, article, conference, proceedings, bibliography, management, auto-fill, autofill
Requires at least: 5.1.1
Tested up to: 5.9
Stable tag: 0.20220219
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin provides a simple bibliography management tool for collecting and 
displaying lists of publications, suitable for scientists, writers and 
anyone who manages collections of publications.

== Description ==

Books & Papers plugin provides you with simple tools to manage your lists of publications (like journal articles, books, scientific papers, conference proceedings, et cetera) and related authors.
You can use the plugin to display your bibliography using a flexible shortcode placed anywhere on the pages of your site.
The plugin contains a constructor which allows you to immitate any citation style.
Publication data can be filled manually as well as imported using [DOI](http://dx.doi.org/) or [BibTeX](http://www.bibtex.org/) database.

=== Publications ===
The plugin distincs three types of publications:
* Article - mostly refers to journal articles;
* Conference - associated with conference proceedings like bibliography;
* Book - seperately published book or some chapters of one.

Each type has a few distinctive fields which can be then displayed on the list.
Beside this, you can further distinguish selected publications by applying a custom style characteristic.

=== Authors ===
If you have a noticible number of publications of one author, you can create database record for this person and assign an identifier, which will later allow you to display only the publications associated with the author by typing that identifier in the shortcode.

Also there's an additional functionally that provides you with ability to create links to the author's exclusive pages replacing the name of mentioned author.

=== Settings ===
You can configure some of the plugin functionallity:
* Set database prefix for tables that store author and publication data.
* Customize the publication list view.
* Customize citation style.
* Create custom style characteristics.
* Toggle on/off some of the plugin functionallity to optimize your site preformance.

=== Usage ===
Let suppose, you've added an author named John Doe with johndoe identifier. 
There are 10 Article records, 3 Book records and 8 Conference records are related with John Doe. One Article is marked as preprint.
The following shortcodes are now accessible:
* [publications auth=johndoe subj=articles prep=all] will show all 10 Articles including preprint.
* [publications auth=johndoe subj=articles prep=only] will show only 1 record (preprint).
* [publications auth=johndoe subj=articles prep=ex] will show 9 Articles without preprint.
* [publications auth=johndoe subj=books] will show 3 books.
* [publications auth=johndoe subj=conferences] will show 8 conference records.
* [publications auth=all subj=all year=2018] will show all publication records of year 2018.
* [publications auth=all subj=all tag=sample] will show all publication records that have the 'sample' tag.

Settings menu provides such fields for customization:
* Headers for articles, conferences and books sections.
* Substitution list for flexible records output format. For example, command 
[authors] <i>[title]</i>, [journal] {{Vol. <b>[volume]</b>}} [pages] ([year]) will result in a list of 
authors, italic title of the article, journal title, bold volume number, pages number and article year in parentheses. 
Note, that {{...}} syntax means that this part of record will not be displayed if [volume] is not provided.

== Installation ==

1. Install the plugin usind WordPress plugins screen or upload the plugin and copy its content to the `/wp-content/plugins/` directory.
1. Activate the plugin on the 'Plugins' screen in WordPress.
1. Go to Settings->Books and Papers screen and configure the plugin.

== Frequently Asked Questions ==

= How can I access the plugin? =

Plugin menus are located on the left admin panel.

= Plugin menu doesn't show up. =

Plugin must be enabled first. Go to 'Plugins'->'Installed Plugins' and enable the plugin.

= How can the list of publications be shown on the site? =

Use the following tag: [publications auth=author subj=publication], where 'author' should be replaced with author slug or 'all' for everyone, and 'publication' replaced with paper  type (articles, conferences or books) or 'all' for any type. For example: [publications auth=all subj=articles] will shown articles of every author.

= No publications are shown, while tag is correct. =

Possible reasons:
* Plugin is not enabled.
* No publications were entered. Go to 'Manage Works' submenu to check. It should have a list of all publications.
* Publications are not public. Go to 'Manage Works' submenu, find the needed publication, click 'Modify', 'Paper is public' should be checked to allow this publication to be displayed on site web page.

= I want to emphasise some publications on the list. =

You can set up custom style characteristic on the plugin settings page: name it and enter its style. Then modify the publication by checking the checkbox near previously entered characteristic name.

= What I should enter as custom characteristic's style? =

The style field should contain style description similar to HTML style attribute. For example, to make white text on black background, enter "color:white,background-color:black" (without quotes). Find more options online, searching for "style attribute properties".

= I experience poor site performance running the plugin =

Go to the plugin settings page and find performance section. There you can disable some of the plugin functionality to increase the page loading speed.

== Screenshots ==

1. Publication list as auto-fill result on site page.

2. Plugin's author management admin menu.

3. Plugin's works management admin menu.

4. Plugin's settings page.

== Changelog ==

= 0.20220219 =
* Fixed input fields vulnerability.

= 0.20210223 =
* Fixed bug with incorrect representation of several authors with the same surnames.
* Added feature preview: obtaining publication abstract by DOI.

= 0.20210205 =
* Added list element stylew option to the settings.
* Added new attributes to publication list tags: target, rel, download and type - for <a> tag; style, class - for every tag.

= 0.20210204 =
* Added <br> and <p> tags to publication lists.

= 0.20210201 =
* Fixed publication data fields on DOI import.

= 0.20210128 =
* Added tag input on DOI import.

= 0.20210125 =
* Fixed bug breaking tag shortcode option usage for conferences and books.
* Added btitle shortcode option.

= 0.20210119 =
* Fixed bug with author specific article list.
* Fixed bug with quotes in titles on manage papers page.

= 0.20210102 =
* Completely reworked backend for publication shortcode replacement.
* Added options for mixed list and sorting (new system only).
* Slight interface improvements.

= 0.20201008 =
* Fixed database prefix change.

= 0.20200929 =
* Rewritten DOI import functionality.

= 0.20200927 =
* Added performance options to settings.

= 0.20200925 =
* DOI import fix.

= 0.20200924 =
* Performance improvement.

= 0.20200917 =
* Possible fix to incorrect database queries.

= 0.20200410 =
* Fixed editors not appearing after shortcode replacement.

= 0.20200303 =
* Fixed numerical tags issue.

= 0.20200302 =
* Added shortcode to display publications by id.

= 0.20200228 =
* Fixed DOI import.

= 0.20200206 =
* Added tags and its functionality.
* Added some addirional compatibility options.

= 0.20190622 =
* Fixed issue preventing from inability to apply new journal title on manage work page.
* Fixed issue when conditional formatting with [journal] tag didn't work properly.

= 0.20190616 =
* Integer values like year, volume or issue, if equal to zero, are now considered as null in conditional formatting.
* Reduced required fields of publications to title only (Journal and Year are no longer required).

= 0.20190526 =
* Added list sorting by title. Added Citation styles examples.

= 0.20190522 =
* Fixed issue displaying all publications if there are none for specified year.

= 0.20190521 =
* Added optional field to tag that allows displaying publications of specific year.

= 0.20190418 =
* Fixed class and options name.

= 0.20190413 =
* Initial release.

== Upgrade Notice ==

= 0.20220219 =
* Fixed input fields vulnerability.

= 0.20210223 =
* Fixed bug with incorrect representation of several authors with the same surnames.
* Added feature preview: obtaining publication abstract by DOI.

= 0.20210205 =
* Added list element stylew option to the settings.
* Added new attributes to publication list tags: target, rel, download and type - for <a> tag; style, class - for every tag.

= 0.20210204 =
* Added <br> and <p> tags to publication lists.

= 0.20210201 =
* Fixed publication data fields on DOI import.

= 0.20210128 =
* Added tag input on DOI import.

= 0.20210125 =
* Fixed bug breaking tag shortcode option usage for conferences and books.
* Added btitle shortcode option.

= 0.20210119 =
* Fixed bug with author specific article list.
* Fixed bug with quotes in titles on manage papers page.

= 0.20210102 =
* Completely reworked backend for publication shortcode replacement.
* Added options for mixed list and sorting (new system only).
* Slight interface improvements.

= 0.20201008 =
* Fixed database prefix change.

= 0.20200929 =
* Rewritten DOI import functionality.

= 0.20200927 =
* Added performance options to settings.

= 0.20200925 =
* DOI import fix.

= 0.20200924 =
* Performance improvement.

= 0.20200917 =
* Possible fix to incorrect database queries.

= 0.20200410 =
* Fixed editors not appearing after shortcode replacement.

= 0.20200303 =
* Fixed numerical tags issue.

= 0.20200302 =
* Added shortcode to display publications by id.

= 0.20200228 =
* Fixed DOI import.

= 0.20200206 =
* Added tags and its functionality.
* Added some addirional compatibility options.

= 0.20190622 =
* Fixed issue preventing from inability to apply new journal title on manage work page.
* Fixed issue when conditional formatting with [journal] tag didn't work properly.

= 0.20190616 =
* Integer values like year, volume or issue, if equal to zero, are now considered as null in conditional formatting.
* Reduced required fields of publications to title only (Journal and Year are no longer required).

= 0.20190526 =
* Added list sorting by title. Added Citation styles examples.

= 0.20190522 =
* Fixed issue displaying all publications if there are none for specified year.

= 0.20190521 =
* Added optional field to tag that allows displaying publications of specific year.

= 0.20190418 =
* Fixed class and options name.

= 0.20190413 =
* Initial release.
