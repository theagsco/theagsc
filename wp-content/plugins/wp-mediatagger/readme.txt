=== WP MediaTagger ===
Contributors: phd38
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WY6KNNHATBS5Q
Tags: widget, plugin, media, library, images, audio, video, mpeg, mp3, pdf, rtf, txt, taxonomy, photos, tags, tagging, bulk tagging, group tagging, gallery, photoblog, search, engine, classification, database, cleanup
Requires at least: 3.0
Tested up to: 4.0.4
Stable tag: 4.0.4



== Changelog ==

= Future possible implementations =
A lot of some enhancements brought to *MediaTagger* were made thanks to your suggestions. Feel free to [contribute](http://www.photos-dauphine.com/ecrire "Suggestions") with your own proposals :

- Check tags associated to medias in the *wp_term_relationships* table. Sometime there are many, although I would expect the only default tag '1' (default category) is there. Possibly cleanup if too mazy.
- Plugin cleanup to make the HTML generated code strict XHTML compliant
- Improved pagination for multipage results, displaying quick access page links
- Log visitors search and make it visible to the administrator
- Plugin internationalization : the `mediatagger.pot` file, required to translate the package to any other language, is provided for volunteers with the plugin files (contact me to make sure the *.pot file part of the package is up-to-date). If you are interested in internationalizing this plugin, I would certainly welcome your help. Simply [let me know](http://www.photos-dauphine.com/ecrire "Any volunteer to push the WP MediaTagger internationalization ?") so that I can push your translation to the repository. If needed I can provide you the methodology, many tools are available to ease this task.


= 4.0.4 =
- Search display mode switchable / result display mode switchable bug fixed.


= 4.0.3 =
- Default search mode bug fixed.


= 4.0.2 =
- Read & write to new table secured with systematic table detection checking.


= 4.0.1 =
- Fix widget parameter loading moving from before 4.0 to 4.x


= 4.0 - Major release, plugin redevelopped using OOP =

- Structural change : plugin ported to Object Oriented Programmation ; this drastically reduces the risk of variable collision with other plugin or WordPress codex itself.
- Compatible with previous plugin releases. 
- Compatible with WordPress 3.8.
- Graphical interface redesigned to make it more simple, although packed with more features :
- Plugin setup now directly accessible from the left side column menu in the WordPress administration panel. 3 submenus :
- Interface 1 : media explorer, to select media and manage tags; functionality widely enhanced to improve user experience. For instance a customer list of media can be built for later tagging. In the tagging view, easier navigation back and forth.
- Interface 2 : player, to interact live with the database you populate while tagging.
- Interface 3 : options ; the presentation is now much lighter.
- Group (or 'bulk') tagging to tag similarly a group of media selected ; flexible media selection.
- Tagging data now stored in *wp_mediatagger* table - not anymore in *wp_term_relationships_img*.
- plugin options are now stored in a serialized option variable in the database to avoid jamming it with too many insertions related to the same plugin.
- code cleaning : deprecated functions replaced with recommended equivalents.
- new short code added, on top of the existing *[mediatagger]* used so far : [mediatagger_count] ; it displays the number of media available.
- spanish version may not cover all the texts with this release - this will be fixed in the next release. This is due to many messages that were changed and not available anymore in the translation.
- finally : this new version was extensively tested on my own database holding 2000 media. A user reported using with more than 40,000. I would rate it as pretty stable, waiting for your feedbacks...


= 3.2.1 =

- The plugin is now available in spanish. It is by default in english, you can now localize it in french or spanish. In order to do so, add to your wp-config.php file : 
- French : *define('WPLANG', 'fr_FR');*
- Spanish : *define('WPLANG', 'es_ES');*

Thanks to [WebHostingHub](http://www.webhostinghub.com/ "WebHostingHub") for the spanish translation.

- In the list view of the media explorer (admin), you can select the first photo to be listed on the page by typing 'start:xxx' in the seach field, xxx being the index of the first photo to be listed on the page. The newer the photo, the higher the index.


= 3.2 =

- Changes made to bring compatibility with WP3.4. Indeed image caption shortcode is not supported anymore starting with 3.4. As a consequence it is not possible anymore to click on the caption to assign a tag to an image, when logged as admin.
- Some opening php achors ("&lt;?") were corrected to the compliant notation ("&lt;?php")


= 3.1.1 =

- Layout change in the search form and result display headers to improve usability
- If there is no tag groups defined, the tags are listed alphabetically instead of by date of tag creation	


= 3.1 =

- New feature : when the media is not an image, the filename is displayed below the icon in gallery mode.
- New feature : if the media is a PDF file and if the server support the thumbnail extraction routines, a thumbnail of the cover page is displayed (PNG format) instead of the generic PDF image. Server prerequisites : Imagemagick and Ghostscript must be properly installed and enabled.
- New feature : the column layout of the tags form can now be forced, keeping the group names on the first line. Insert a blank line before the line defining the tag group. It will be interpreted as a column break when displaying the tag form. This overrides the rule defined by the parameters "number of tags per column" for the tag editor and search form ; these 2 parameters are greyed out in the administration panel and not used by the plugin.
- New feature : an new search method is added, with a free search field. This field can be toggled on and off, as for the cloud and tag form. When triggered through this field, the search is done on the medias names rather than on the tags attached to the medias.


= 3.0.1 =

- Fix : there was a risk of data loss with version 3.0 when upgrading from ImageTagger, if not deactivating the ImageTagger plugin before activating MediaTagger. Tagging stored in the *wp_term_relationships_img* table was reset under certain conditions. This is fixed with version 3.0.1, no more risk of data loss. Anyhow it is still mandatory to deactivate ImageTagger before upgrading, otherwise a fatal error is caused by function names conflict between the 2 plugins.

= 3.0 (first release under "WP MediaTagger" plugin denomination) =

- Formats now supported, on top of the original GIF, JPEG, PNG : TXT, RTF, PDF, MP3
- Administration panel : added selection of media format that are selected for tagging ; by default GIF, JPEG and PNG are preselected
- Result gallery : image resampling quality improved (antialias)
- Search form : "Clear" button added to reset selected tags

= 2.5.6.1 (last release under "WP ImageTagger" plugin denomination) =

**This is the final version of the WP ImageTagger plugin**. This plugin is now obsoleted by WP ImageTagger plugin. 

= 2.5.6 =

- Debug comment remove from the administration panel.
- Added "Settings" link in extension list panel (close to deactivate and modify)
- Strengthened SQL queries error checking for database integrity audit function
- Donation button added at bottom of administration panel ; donation link added in the extensions management page

= 2.5.5.6 =
- XHTML strict : fixed parametrization form fields

= 2.5.5.5 =
- Tag cloud fix : division by zero occuring in some cases

= 2.5.5.4 =
- Minor fix : parameter layout in the plugin admin panel under WP 2.9.2

= 2.5.5.3 =
- Fix : non UTF-8 alphabetical tags ordering in the tag cloud 

= 2.5.5.2 =
- Fix, major : 2.5.5 feature was preventing the form and tag cloud search.

= 2.5.5.1 =
- Fix, minor : no tag selected case through the form or tag cloud, or tags selected not matching any media.

= 2.5.5 =
- A search can now be done directly on the media database without going through the search form or tag cloud. All you have to do is to form URLs like `http://wwww.mysite.com/media_library?tags=car+plane+airport`, forming your tag list using tag slugs. In that example, the page *media_library* is the one set with the search form and/or tag cloud, that will be displayed with the tag cloud and/or search form, clean of any search result if requesting `http://wwww.mysite.com/media_library`. A tag slug is the tag name with no accent and spaces replaced by hyphens. For instance, the tag "l'automne en for&ecirc;t" becomes "l-automne-en-foret".
- By default the result page produced by explicit search URL will not hold any tag cloud or search form. Anyhow you can request to have this capability on top of the search results by forming URL like : `http://wwww.mysite.com/media_library?tags=car+plane+airport&display=cloud`. Possible values for the *display* argument are : *cloud*, *form*, *combined*.
- Direct PHP call to `function wpit_multisort_insert()` was deprecated since 2.5.2 and is now unactivated from 2.5.5. Refer to the [installation guide](http://wordpress.org/extend/plugins/wp-mediatagger/installation/ "How to insert the ImageTagger shortcode") in case you are still using the direct PHP form.

= 2.5.4.4 =
- Cleanup done in the code to eliminate any hard coded reference to the WordPress table names.
- Audit tool translated to french.

= 2.5.4.3 =
New function in the admin panel : database integrity audit and repair tools are now available in the section *Misceallenous*. This new audit functionality will help you to better control what can sometime be a rather anarchic growing of your database. The following audit features are offered along with a repair solution :

- Audit of the *wp_posts* table to detect the needless post revisions. You can optionally clean those entries.
- Audit of the *wp_posts* table to detect attachments having still a proper entry in the table although not being referenced in any of the posts. You can optionally clean those entries.
- Audit of the *wp_term_relationships* table to detect orphean entries : any post associated to tags although the post itself does not appear anymore in the *wp_posts* table. You can optionally clean those entries. This feature will help you better control your post taxonomy growth.
- Audit of the *wp_term_relationships_img* table to detect orphean entries : any media associated to tags although the media itself does not appear anymore in the *wp_posts* table. You can optionally clean those entries. This feature will help you better control your media taxonomy growth.
- Audit of the medias not being yet tagged.

Knowing how sensitive any cleanup of the database could turn to be, I tested extensively under various situations and found this feature pretty cool. Then you know the rule : *before any cleanup of the WordPress database, run a complete backup, including all the database tables*.

= 2.5.4.2 =
- Fix : some "orphean media" cases were not covered and leading to some marginalities. Typically this happens when an media was uploaded as part of a post and the post was later deleted, the media being still a valid entry in the post table.
- Fix : when an media is deleted from the database (through the WordPress media management menu, or manually in the database), the plugin needs to detect it and delete any orphean entry (entry related to an media not existing anymore in the database). This can be detected by comparing the total number of medias in the DB to the sum {medias untagged + medias tagged}, which must be equal. This case differs from above, where the media is still an entry in the "posts" table, but not referenced anymore by a post. *This is an excellent opportunity to underline the risk one takes when trying to manually tweak the database instead of relying on the standard procedures*. If you manage database modifications manually, be prepared to make some recovery ... manual as well.

= 2.5.4.1 =
- New function in the admin panel, taxonomy section : a possibility is now offered to the user to run a batch on the complete posts taxonomy to align it on the media taxonomy. This option should be worth when you want to turn your post taxonomy into the media taxonomy just defined with this plugin after you installed it and properly built this media taxonomy. Then assuming you keep the media taxonomy function active, when you tag any media the taxonomy is automatically applied to the post containing this media. 

Therefore this function should be used once after you defined the media taxonomy. If you want to make sure the plugin is then doing his job you can later track any deviation between the posts taxonomy and the medias one. If everything goes well both should be strictly the same.

= 2.5.4 =
- This release is major in the sense it brings the *media taxonomy* feature into real life. I am glad to come to this achievement, I envisionned it as the objective for this plugin : having the tags you associate to the medias directly controlling the tags associated to the post containing those medias. Very nice for lazy people, as I tend to be sometimes. It should be a nice fit when your site content consists largely in medias. Note that this feature will take control over the standard WordPress tables *wp_term_relationship* and *wp_term_taxonomy*. As such, take some time reading the FAQ before deciding to activate this option and, although it has been extensively tested under various situations ... make a backup of your database before trying it out !

= 2.5.3.3 =
- When linking the result gallery medias to the post containing the media, the permalink is used instead of the http://www.mysite.com/?p=41 notation.
- Rich-text-tags now supported. An media used in the standard WordPress tag result header page will be linked back from the ImageTagger result page (if link parametrized as "link to post") on the tag result page.
- New option in Admin Panel : the ImageTagger tag cloud can be displayed by ascending alphabetical order (default), descending rank order, or random.
- New option in Admin Panel : the credit line displayed at the bottom of the search form in its plain version (not in accordance with the WP Plugins authoring guidelines, I concede) can now be disabled. In this case and assuming you enjoy this plugin, I let to your good willingness the possibility to include anywhere else on your site a credit line linking back to my home site (http://www.photos-dauphine.com). You decide. Be simply aware that this plugin requires that I spend a very significant amount of my spare time to answer the support requests, maintain and manage the evolution with bunch of new features.

= 2.5.3.2 =
- All the ImageTagger supported formats (gif, jpeg, png) now take benefit of the thumbnail transfer optimization. In the previous version only the jpeg files had their transfer optimized.
- Fix : some functions released to be used later (association of media tags to the post containing the medias) were causing servers in PHP 4.3 to fail at plugin init.

= 2.5.3.1 =
- In marginal cases, the server side media resizing introduced in 2.5.3 was found not to provide the expected results. A new option was added in the Admin Panel / Output format /  Optimize thumbnail transfer. By default this option is set to "No", which is the setup that will work in any case. To move to the optimized transfer mode, set this to "Yes", save the ImageTagger options and check that your search result displayed as a gallery properly displays the thumbnails. If not, revert this option setting to "No".

If the GD graphic library is not available on your server (check it in the page footnote), this option will be set to "No" and will not be selectable.

= 2.5.3 =
- Dynamically resizes medias server side before transferring medias for gallery or media list result mode. This provides a much faster gallery display when transferring big number of medias. *This improvement is only made available for sites being hosted on servers having the GD library module enabled. This setup is automatically detected by ImageTagger*. To know if you benefit or not of this feature, check the footnote line in your admin panel : if you read *GD Lib not available*, your  server does not support it. Otherwise, the GD library version is displayed.
- Minor fixes for marginal cases (all medias tagged).

= 2.5.2 =
- The plugin can now be inserted in a page or post using the safer `[mediatagger options...]` shortcode notation. Refer to the  [FAQ](http://wordpress.org/extend/plugins/wp-mediatagger/faq/) for any detail.
- This improved implementation does not require anymore to have PHP execution enabled in your page.
- The direct PHP call to the plugin function `wpit_multisort_insert()` is deprecated and should not be used anymore. It still works with a recommendation message, and will not be supported in future releases.

= 2.5.1 =
- Minor bug fixes
- Added gradient colors to the tag cloud (widget and plain page). The options can be parametrized from the *ImageTagger* admin interface for the plain page version, and from the *ImageTagger* widget control window for the widget implementation.

= 2.5 =
- The plugin is now delivered with a widget interface. This new feature enables the ImageTagger tagcloud in your site sidebar.
- There is currently no error checking on the widget input parameters - Be kind to him !

= 2.4.2 =
- Various minor fixes in the Image Explorer (search functionality)

= 2.4.1 =
- Admin panel : fixed Tag Editor and Image Explorer inconsistencies in specific cases (no media available, all medias tagged, all medias untagged)

= 2.4 =
- Admin panel : expanded tagging control panel. Two views are now available : a first view ("Tag Editor", default) to tag the medias. This view scans the untagged medias and displays those for tagging ; a second view ("Image Explorer") listing all the site medias. This list can be filtered to show only the tagged medias, untagged medias, or all the referenced medias. This approach provides a way to rework tagging for sites themes not displaying captions under the medias. A search field is available to filter the listed medias.
- Admin panel : the options setup panel can now be minimized to avoid screens unnecesarily overloaded with data not of immediate use.

= 2.3.3 =
- various fixes, notably a possible path detection issue for specific twisted server setup cases. **For that reason it is a major upgrade given the possible impact of the issue  - get this one ASAP**. In specific cases, the plugin could not detect medias due to broken media path reconstruction.

= 2.3.2 =
- Fix : .png and .gif formats now supported on top of .jpeg.

= 2.3.1 =
- Fix : various side effects

= 2.3 =
- Images can now be associated not only to tags but as well as to categories defined in your blog. The admin panel allows to define the source for your keywords list : tags, categories, or tags and categories merged together.
- Tags can be gathered by groups through the admin panel. This group display is used for both the admin panel and the search page tags form.

= 2.2 =
- Admin panel : fixed font display problem
- Admin panel : options presentation improved (fields now come aligned for better readability)

= 2.1 =
- Added parametrization of tag cloud, forgotten in 2.0 : min / max font size and number of tags to display.

= 2.0 =
- Tag cloud search representation added to the initial form, with capability to be combined with this form
- Search result : media title list mode added
- Possibility to have the search page address different from the result page address with explicit argument : `<?php wpit_multisort_insert("http://www.mysite.com/medialibrary_result") ?>` ; without argument the results are displayed at the same address as the search page address
- All these new possibilities are parametrized through new admin panel options, plus additional parameters.

= 1.5 =
- Irrelevant tags can be excluded from the tagging panel. The same possibility is offered to filter the list of tags displayed on the search page. This is parametrized through two separate CSV tag lists available in the Admin Panel.

= 1.4 =
- PHP support extended down to PHP4. Any dependency on PHP 5 was removed. Now the requirements are the same as for WordPress (PHP 4.3 or greater, MySQL 4.1.2 or greater), therefore I don't expect anymore issue.

= 1.3 =
- PHP version is checked to ensure the server complies with the minimum required (PHP 5).
- Language localization now provided relying on I18n : english (default), french

= 1.2 =
- Administration panel : input parameters are now validated against expected valid parameter ranges to make sure the plugin will not be shot by funky option settings. In case of improper entry, an explicit message is displayed while highlighting the faulty option entry line.

= 1.1 =
- Admin panel offers now a bunch of settings, like capability to display the list of medias matching the searched tags in a gallery or itemized list mode.
- Result page is paginated. Number  of medias shown is a parameter.
- Most of the messages are translated to English.

= 1.0 =
- First release. ImageTagger concept implemented with the minimum features set enabling root functions. Plugin available in French. English localization on going.




== Description ==

**WP MediaTagger** is an extensively configurable plugin and a superset of the former WP ImageTagger plugin. It provides all the tools required to categorize your WordPress blog medias by associating it to any defined tag or category. Among many features, it comes packed with a tagcloud widget that will make the process straightforward for everyone and will permit a transparent integration. Initially developped to tag medias under the WP ImageTagger denomination, WP MediaTagger now covers a much wider scope and will tag as well most of the medias widely used on the web.

In brief, this plugin extends the concept of *post based taxonomy* natively supported by WordPress toward an *media based taxonomy*.

The following functionalities are available for your blog after installing the *MediaTagger* plugin package :

> - Associate tags to medias from the *Tag Editor*
> - List all the medias of your site and associated tags in one central place to ease the tagging process with the *Media Explorer*
> - Associate tags to medias from any post or page by clicking on your media captions
> - Provide access to deep media search on any media associated to a list of tags, through a *tag cloud*, *tag form* or *combined* search display
> - Select a display mode for your result page : *itemized media list*, *thumbnail gallery*, or *media captions*
> - Any combination of search mode and result display style is possible
> - Seamless integration in your sidebar thanks to the *MediaTagger tagcloud widget*, with direct connection to the media tagging database you just built
> - The *MediaTagger* media taxonomy can optionally supersede the default post taxonomy
> - Database integrity checkers are provided along with the fixing routines


Willing to get a visual flavor of what's in the box ? You can see the *MediaTagger* plugin in action [here](http://www.photos-dauphine.com/phototheque "Check out here the WP MediaTagger plugin in action"), or have a look at screenshots [there](http://wordpress.org/extend/plugins/wp-mediatagger/screenshots/ "WP MediaTagger screenshots"). French reading visitors will get additional insights on this [plugin genesis page](http://www.photos-dauphine.com/wp-mediatagger-plugin/ "WP MediaTagger project page").

 
 
 
 
 
   
== Installation ==

To install the **WP MediaTagger** plugin just follow this simple 10-step recipe.

**CAUTION : if upgrading from WP ImageTagger, backup your database, taking care to include the table wp_term_relationships_img and deactivate the ImageTagger plugin from the extensions administration page before activating WP MediaTagger (CRITICAL !). Refer to instructions at page bottom.**

1. Download the plugin and expand it to an empty directory of your local disk drive.
2. Copy the local *wp-mediatagger* folder created by the unzipper onto your server plugins folder (*wp-content/plugins/*). Make sure you end up with all the PHP files, readme.txt and screenshots in *wp-content/plugins/wp-mediatagger* directory.
3. Login into the WordPress administration area and click on the *Plugins* left menu. Expand the *Installed* view.
4. Locate the *MediaTagger* plugin and click on the *Activate* link.
5. Make sure your blog already holds posts or pages with medias (medias or any other WordPress media).
6. Make sure you already created a list of tags for your blog.
7. Start associating your blog medias with any tag of your blog. Two methods for this purpose :

	> - From your WordPress administration panel, go to *Settings > MediaTagger* and start associating the first media found to any tag in the *Tag Editor*
	> - Or, still from the *MediaTagger* administration area, switch to the *Image Explorer* mode, navigate to the file you want to tag and click on the file to select it back to the *Tag Editor*
	> - Or, assuming your site display captions below each media : from any post or page holding medias, click on the media caption (being administrator) and make the association with any tag from the *Tag Editor* panel.

8. Prepare a **result page** to present the search results :

	> - Create a new page (or use existing one if you want).
	> - From your WordPress editor, enter the *MediaTagger shortcode* : `[mediatagger]` - There are options you will learn later, but this simple form is perfect for a quick start.
	> - Check the result on the page containing this call.
	> - Start playing with the options offered in the MediaTagger admin panel.

9. By default your **search page** is the same as your result page. You can also choose to activate the MediaTagger widget to integrate an media search tagcloud in your sidebar, or to manage the search page at a different address for a specific use.

10. Go ! And if you like it, why not proceeding with a [small donation](href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WY6KNNHATBS5Q "Support WP MediaTagger development")?

If you are interested in seeing the plugin in action, you might wish to go and have a look [here](http://www.photos-dauphine.com/phototheque "Check out here the WP MediaTagger plugin in action").  

More information on this plugin utilization can be found [there](http://www.photos-dauphine.com/wp-mediatagger-plugin "WP MediaTagger Home").

Finally, if you have any questions, please refer to the [FAQ bottom page](http://wordpress.org/extend/plugins/wp-mediatagger/faq/ "If you need some help"), section  - section *Damned, my question is not listed there*.

That's all for today - Enjoy !


**If you are upgrading from WP ImageTagger :**

The tagging made on images will be preserved **IF** you follow these simple instructions:

1. **BACKUP YOUR DATABASE, TAKING CARE TO INCLUDE THE wp_term_relationships_img TABLE** - in case something turns wrong during the upgrade.

2. **Deactivate ImageTagger plugin from the extensions administration page - OTHERWISE YOU RISK TO LOOSE THE TAGGING ALREADY DONE.** 

3. Install and activate MediaTagger plugin

4. On your result page : replace the shortcode [imagetagger] by [mediatagger], keeping the same options if you use some

5. If you were using the ImageTagger widget : go to Appearance > Widgets ; reposition the MediaTagger widget in the sidebar, all the settings formerly made for the ImageTagger plugin will be automatically restored. 

6. You can de-install the WP ImageTagger plugin from the Extensions interface.





== Frequently Asked Questions ==

= What are the prerequisites to run this plugin ? =

- At least one media should be present in your blog. Otherwise the MediaTagger admin panel will inform you that you need to start working on your blog !
- Same comment for the tags declared in your blog.

= How are defined the tags proposed for the media classification ? =

By default the plugin will use the WordPress tags. Anyhow you can decide to use WordPress categories instead, or to combine tags and categories.

= Can I create groups of tags ? =

The tags can be gathered by groups. This grouping does not affect the search, it has only an effect on the tags presentation in the classification panel and in the search form. Grouping the tags by themes provides a more consistent presentation, and makes the appropriate selection faster.

= Which media formats are supported ? =

The formats below can be tagged with WP MediaTagger :

- Images : GIF (image/gif), JPEG (image/jpeg), PNG (image/png)
- Audio / video : MP3 (audio/mpeg)
- Documents : TXT (text/plain), RTF (application/rtf), PDF (application/pdf)

= Are the WordPress gallery themes supported ? =

Yes, the medias managed within WordPress galleries will be available for tagging as any other standalone media. 

= How do I tag the medias of my blog ? =

Having installed the plugin according to the instructions provided on the [installation page](http://wordpress.org/extend/plugins/wp-mediatagger/installation/ "How to install the WP MediaTagger plugin for your site ?"), two alternatives are offered to tag your medias :

- From your WordPress administration panel, go to *Manage > MediaTagger* and start associating the first media found to any tag in the *Tag Editor* ;
- Or, switch to the *Media Explorer* mode, navigate to the file you want to tag and click on the file to select it back to the *Tag Editor* ;
- *** NOT SUPPORTED ANYMORE STARTING 3.4 *** Or, assuming your site display captions below each media : from any post or page holding medias, click on the media caption (being administrator) and make the association with any tag from the *Tag Editor* panel.

= How do I know if a media is tagged or not ? =

Go the the plugin admin panel and switch the view to *Image Explorer* mode. This gives you access to a page listing all your site medias and the associated tags. You are able to get any media tagging status from this page.

Additionally, in case your site theme displays media captions : a tooltip appears if you put your mouse pointer over the media caption in the page or post holding this media. This tooltip displays the tags associated to the media.

= How do I change the tags already associated to a media ? =

Go the the plugin admin panel and switch the view to *Media Explorer* mode. This gives you access to a page listing all your site medias and the associated tags. You are able to get any media tagging status from this page.

Additionally, in case your site theme displays media captions : click on the media caption (being administrator) and make the association with any tag from the Tag Editor panel.

= How do I know the total number of medias my site holds, and that can be tagged ? =
This information is the X value displayed in light grey in the upper right part of the MediaTagger admin panel as X/Y/Z. 

= How do I know the number of medias I already tagged ? =
This information is the Y value displayed in light grey in the upper right part of the MediaTagger admin panel as X/Y/Z. 

= How do I know the number of medias remaining to be tagged ? =
This information is the Z value displayed in light grey in the upper right part of the MediaTagger admin panel as X/Y/Z. Consequently Y+Z=X.

= How do I insert the MediaTagger search form on a page or post ? =

Edit your page or post with the WordPress editor and insert the specific *MediaTagger shortcode* `[mediatagger]`. From this point the plugin will manage the calls to the adequate functions.

= Can I tag medias without having yet inserted the MediaTagger form on my site ? =

Yes, these are two separate processes. On one hand you build your database by tagging the medias, on the other you run queries on this database through to the `[MediaTagger]` shortcode.

= What are the different display modes available to manage the search page ? =

The search can be presented to the visitor under two different representations than can be mixed together into a third one : 

- a **tag cloud display** : the tags available for search are presented WordPress fashion ; this representation is compact but suitable for single tag search only
- a **form display** : the tags are listed in a form ; the search is done by ticking one or more tags. This makes this representation more adapted for advanced, multi-criteria search, although requiring potentially much more room on your page in case of big tag collection.
- a **text field** : to search on the media names rather on the tags associated to those medias.

All these modes can be freely combined.

= Can the visitor switch between the three search display styles ? =

This possibility is offered by default in the option panel. You can anyhow preset a default search display style, not switchable by visitors.

= What are the different display modes available to manage the result page ? =

This can be done using in three different ways :

- *itemized media list* : the results are presented as a vertical list of medias displayed with a title and the post they refer to (if attached). This mode is suitable for getting the maximum information on the search result, but not adequate when the number of results found is too important.
- *thumbnail gallery* : the results are presented in a compact display consisting in a gallery of thumbnails. More information can be obtained rolling the mouse over each thumbnail, although it is not the most adequate for having a direct reading of the media related information.
- *media captions list* : this display mode does not display the media and restrict itself to the text information. It will be preferred by visitors looking for specific text information rather than pictorial impression. 

These three modes are paginated. The number of result per page is an option accessible in the admin panel.

= Can the visitor switch between the three result display styles ? =

This possibility is offered by default in the option panel. You can anyhow preset a default result display style, not switchable by visitors.

= Has the search page to be at the same address as the result page address ? =

Although this possibility is offered, the search panel can redirected to a different page. See below for the implementation details.

= What are the possible implementations of the search and result pages ? =

There are three possible implementations :  

- Single page implementation :  

	> - Create a new page (or use existing one if you want).
	> - From your WordPress editor, enter the *MediaTagger shortcode* : `[mediatagger]`.
	> - Check the result on the page containing this call.
	> - Start playing with the options offered in the *MediaTagger admin panel*.

- Search page different from the result page :  

1. Manage the page embedding the search access :   

	> - Create a new page, for instance *http://www.mysite.com/medialibrary_search*, or use existing one if you want.
	> - From your WordPress editor, enter *MediaTagger shortcode*, with one option : `[mediatagger result_page_url="http://www.mysite.com/medialibrary_result"]`, assuming you want your visitor to be directed and have the results displayed on page *http://www.mysite.com/medialibrary_result*.
	> - Set the proper options in the Admin Panel to control the Search Format as you want.
	> - Check the result on the page containing this call, without running any search yet.

2. Manage the result page :  

	> - Create a new page matching the URL passed inside the *MediaTagger shortcode* let's say *http://www.mysite.com/medialibrary_result* (or use existing one if you want).
	> - From your WordPress editor, enter the *MediaTagger shortcode* : `[mediatagger]`.
	> - Check the result on the page containing this call
	> - Set the proper options in the Admin Panel to control the Result Format as you want.
	> - Launch a search from your page *http://www.mysite.com/medialibrary_search* ; you will be directed to the result page *http://www.mysite.com/medialibrary_result*
	> - Play with the options offered in the MediaTagger admin panel to adjust the search and result format.

- Sidebar tag cloud widget :

	> - In this case, the call to the *MediaTagger* API is directly managed by the widget. Refer to the section below for the activation.
	
= How do I activate the tagcloud widget ? =

Before that you need to have defined your *MediaTagger result page*, as described just above.

Then from your site admin page, go to *Appearance > Widget* and click on *Add* to add the widget to your sidebar. Configure then the widget in the right column clicking on the *Edit* link, followed by *Done*. Do not forget to *Save Changes*. Pay specific attention to the *Result page address* parameter. This parameter must be the address of a  page of your site that you defined as your *MediaTagger result page* according to the section *What are the possible implementations of the search and result pages ?* above.

If you get the error 404 when clicking on the sidebar tag cloud, you likely misconfigured the *Result page* field.
	
= What is the syntax for the MediaTagger shortcode ? =

In the WordPress glossary, a shortcode is a syntaxic expression that will trigger some functions. In our case, the shortcode is built around the `mediatagger` keyword. The shortcode relies on a single scalable function, managing the search aspect as well as the result display under various shapes. It can be called with a variable number of arguments. The shortcode needs to be formatted as follows :

	[mediatagger opt1="val1" opt2="val2" ...]

<p> </p>

The 6 options available are :	
	 
	$result_page_url = URI of the result page, for instance 'http://www.mysite.com/medialibrary_result'. Can be absolute (preferred) or relative to the site root
	$num_tags_displayed = number of displayed tags - If set to 0 (zero), the complete set of tags is displayed
	$font_size_min = minimum font size for the tag cloud (pt)
	$font_size_min = maximum font size for the tag cloud (pt)
	$font_color_min = color that will be used for the least frequently used tags (hex format : 4343f6 for instance)
	$font_color_max = color that will be used for the most frequently used tags (hex format : 4343f6 for instance)
	
<p> </p>

The shortcode works with default arguments. Therefore it can be called without explicitly passing the complete list of arguments. When an argument is omitted, the behaviour is the following :

- *$result_page_url* : when omitted, the result page is the same as the one containing the shortcode
- *$num_tags_displayed* : when ommitted, the number of tags displayed is the one defined in the options set in the plugin admin panel. 
- *$font_size_min* : when ommitted, the minimum font size for the tag cloud is the one defined in the options set in the plugin admin panel
- *$font_size_max* : when ommitted, the maximum font size for the tag cloud is the one defined in the options set in the plugin admin panel
- *$font_color_min* : when ommitted, the color used for the least frequently used tags is the one defined in the options set in the plugin admin panel
- *$font_color_max* : when ommitted, the color used for the most frequently used tags is the one defined in the options set in the plugin admin panel

Some examples :

- Shortcode with no argument :

		[mediatagger]

*Result* : a search panel (form, tag cloud or combined) is displayed on the page holding this call. The search result is displayed on the same page. The number of tags and font sizes are the ones set in the admin panel.

- Shortcode overriding the number of tags in the tag cloud :
 
		[mediatagger num_tags_displayed="15"]
	
*Result* : 	as well, the search panel and results are displayed on this same page. The number of tags is set to 15, independantly from the setup done in the option panel ; other parameters are the ones set in the options panel.

- Explicit passing of all 6 arguments :

		[mediatagger result_page_url="http://www.mysite.com/search_result/" num_tags_displayed="15" font_size_min="8" font_size_max="25" font_color_min="4545fe" font_color_max="111132" ]

*Result* : 	the 6 parameters are forced to the values passed in, and the corresponding options set in the admin panel are ignored.


= Can a search be triggered directly from the URL, not being through the tag cloud, search form or search field? =

Yes, this is possible. A search can now be done directly on the media database without going through the search form or tag cloud. All you have to do is to form URLs like `http://wwww.mysite.com/media_library?tags=car+plane+airport`, forming your tag list using tag slugs. In that example, the page *media_library* is the one set with the search form and/or tag cloud, that will be displayed with the tag cloud and/or search form, clean of any search result if requesting `http://wwww.mysite.com/media_library`. A tag slug is the tag name with no accent and spaces replaced by hyphens. For instance, the tag "l'automne en for&ecirc;t" becomes "l-automne-en-foret".

By default the result page produced by explicit search URL will not hold any tag cloud or search form. Anyhow you can request to have this capability on top of the search results by forming URL like : `http://wwww.mysite.com/media_library?tags=car+plane+airport&display=cloud+form`. Possible values for the *display* argument are : *cloud*, *form*, *field*, which can be combined together.


= What is the Media Taxonomy feature all about ? =

This option is meaningful when you are managing a large collection of medias. The idea is to tag the medias with *MediaTagger*, and let then the plugin automatically associate the relevant tags to the post holding these medias.

- This feature is optional ; by default it is not activated.
- Having this feature disabled, the tags associated to your posts are the ones manually set when editing your post and setting appropriate tags in the dedicated field. The standard WordPress mechanism applies.
- Before enabling the *media taxonomy*, **backup your WordPress database**. Nothing special to be scarred about, but it is a general good practice when you know your database will be affected by a mechanism you did not experience yet.
- How does it work once activated ? When you enable this feature, every time you tag a media, the collection of tags associated to the medias contained in the post (*media taxonomy*) to which belongs the media you tagged will be associated to the post itself. At the time you tag an media, the tags formerly associated to the post containing the media are replaced by this *media taxonomy*.
- The *media taxomonmy* mechanism applies to the *posts* only given that WordPress does not support *page* tagging. Tagging a media contained in a page will not have any other effect than the media tagging by itself.
- What is the interest in having the *post taxonomy* driven by the *media taxonomy* ? It will help you keeping with a coherent post tagging scheme by solely focusing on your media tagging. This is particularly relevant when your site is media-centric.

When the *media taxonomy* is activated, the WordPress database is updated to replace the tags manually associated to the posts by the *media taxonomy* (on top of updating the *wp_term_relationships_img* table which is the basic *MediaTagger* mechanism) :

- the table *wp_term_relationship* is updated to associate the post to the tags provided by the *media taxonomy*. Lines will be automatically added or removed, as your post tagging is extended or reduced (the natural trend is normally the extension ...)
- the table *wp_term_taxonomy* count column is updated to keep the tag counters up-to-date.
 
	
= How is my WordPress database affected by this plugin ? =

- By default, *MediaTagger* does not affect any of the existing WP data tables to avoid any risk of corrupting your database. The association you create between tags and medias are stored in a new table *wp_term_relationships_img*.
- If you decide to have the *media taxonomy* overriding the post taxonomy as explained above, the tables *wp_term_relationships* and *wp_term_taxonomy* (count) will be modified. For that reason, run a full database backup before activating this taxonomy control in the *MediaTagger Admin Panel*.
- MediaTagger options set in the admin panel as stored in the *wp_options* table, in accordance with WordPress plugin development guidelines.

= What should I do to ensure I backup the media tagging information when I am run backups of my WordPress database ? =

Make sure you include the *wp_term_relationships_img* table specifically created by this plugin to keep track of the media tagging information you patiently grew over time. This table should be selected for your backup as well as the standard WordPress database tables, such as *wp_options*, *wp_terms*, *wp_posts*, etc.

= What are the available languages ? =

*English*, *French*, *Spanish* 
... and as much as you can contribute if you are fluent with a language not in this list. More details on the [changelog page bottom](http://wordpress.org/extend/plugins/wp-mediatagger/changelog/ "WP MediaTagger Changelog"), *in project* section.  

= Damned, my question is not listed there !!? =
*Did you make sure you read carefully enough the FAQ I took time and care to build as complete and explanatory as possible ? I do my best to maintain it with the latest questions I got and answers I made to their author.* 

Although this is not my primary job, you might direct your questions to [this page](http://www.photos-dauphine.com/ecrire "Write me here"), I will do my best to timely answer. To help me answering faster, please provide me with the necessary data :

> - copy paste the footnote line you see at the bottom of your plugin administration panel.  
> Ex : *WP MediaTagger 3.0 | PHP 5.2.6-1+lenny4 | MySQL 5.0.32-Debian_7etch8-log | GD Lib 2.0 or higher*
> - indicate step by steps what you did to get the issue
> - describe as specifically as possible the issue you could observe (a screenshot is a plus), with any peripheral aspect
> - information related to your site : specific server setup, redirections, number of medias, any special setup ...

Although I would if I could ... I would not be able to give a hand being only informed that *it does not work*. Thanks for your cooperation.

= Ideas or suggestions ... =
... are truly welcomed given that it will make this plugin even more valuable to the users community. Spin your suggestions [this way](http://www.photos-dauphine.com/ecrire "Suggestions").







== Screenshots ==

1. MediaTagger administration interface
2. MediaTagger media search form implementation




