=== Genealogy ===
Tags: genealogy, family tree, family, family history, pedigree, people, personal, family members
Requires at least: 3.1
Tested up to: 4.6
Stable tag: trunk
Contributors: suiteplugins
Donate link: https://suiteplugins.com/donate/

"Being revamped" Map out your family relationships using the Genealogy plugin. 

== Description ==

This plugin has been adapted by  [SuitePlugins](https://profiles.wordpress.org/suiteplugins) and is currently in BETA as we work to resolve open issues and improve this amazing plugin.

###The Genealogy plugin for WordPress allows you to map your entire family's relationships.

Use the plugin to create a Member for each person in your family. Assign Members parents from the people you have added.

####Add information for each Member, including:

* Photographs
* Date and location of birth
* Parents
* Spouses
* Date, location, and reason of death
* Up to 20 wives, husbands, professions, schools, religions & middle names

####The plugin has support for:

* Featured images - Upload photos to the family member and set them as Featured Image to have their picture added to the family member information table
* Multiple wives and husbands, including wedding time span
* Multiple professions, schools, religions, locations, and life events
* Calendar-based date picker to make it easy to select a date (as far back as 1411!)

### Easy to use!
<strong>To add a list of family members</strong> to a post or page: add `[genealogy]` to the content

<strong>To add a single family member</strong>, add `[genealogy id=#]` where `id` is the post ID of the Family Member (such as `231`). You can also use `[genealogy slug="john-smith"]` where the URL of the family member is `.../family/john-smith/`

== Screenshots ==

1. The screen for adding family members
2. The Genealogy plugin settings page

== Frequently Asked Questions ==

= How do I insert family members into a post or page? =

To add a list of family members to a post or page: add `[genealogy]` to the content

To add a single family member, add `[genealogy id=#]` where `id` is the post ID of the Family Member (such as `231`). You can also use `[genealogy slug="john-smith"]` where the URL of the family member is `.../family/john-smith/`

= What's the license? =
This plugin is released under a GPL license.

= Does this plugin support GEDCOM? =
The plugin does not currently support importing or exporting GEDCOM. Future versions should feature GEDCOM export.

= Where are the options for the plugin? =
* Whether to show thumbnails for family member relationships (siblings, children, etc)
* Choose to show a record's "last modified" date to everyone, admins, or no one (default)
* Birth date options: show the birth date of all family members (default),  deceased people only, or no birth dates
* Give credit to the plugin with a link below a family member's details

== Changelog ==
= 1.2.2 =
* Fixed JS error and WP Editor not working / JS Error preventing featured images being set
* Updated readme.txt
* Fixed 404 on single family member details

= 1.2.1 =
* Added check for the `get_the_post_thumbnail()` function support to fix <a href="http://wordpress.org/support/topic/fatal-error-genealogy-plugin" rel="nofollow">this reported bug</a>

= 1.2 =
* Added `genealogy_thumbnail_size` filter for image thumbs
* Fixed fatal errors on Family Member pages
* Hid empty Father and Mother fields when no family members yet exist
* Fixed issue when clicking the + button to clone fields wasn't working
* Added contact detail options - Phone, Email, Address
* Improved instructions in ReadMe

= 1.1 =
* Added settings page
* Fixes lots of warnings that appear when in WP_DEBUG mode.
	- Notice: Undefined index: family_member_data_noncename on line 177
	- Notice: Undefined index: screen on line 178
	- Notice: Trying to get property of non-object on line 146
	- Undefined variable notices
	- Not an object notices

= 1.0 =
* Launched plugin

== Upgrade Notice ==

= 1.2.1 =
* Added check for the `get_the_post_thumbnail()` function support

= 1.2 =
* Added `genealogy_thumbnail_size` filter for image thumbs
* Fixed fatal errors on Family Member pages
* Hid empty Father and Mother fields when no family members yet exist
* Fixed issue when clicking the + button to clone fields wasn't working
* Added contact detail options - Phone, Email, Address
* Improved instructions in ReadMe

= 1.1 =
* Added settings page
* Fixes lots of warnings that appear when in WP_DEBUG mode.
	- Notice: Undefined index: family_member_data_noncename on line 177
	- Notice: Undefined index: screen on line 178
	- Notice: Trying to get property of non-object on line 146
	- Undefined variable notices
	- Not an object notices
= 1.0 =
* Launch

== Installation ==

1. Upload this plugin to your blog and Activate it
2. You will see a Family link in your administration sidebar. Click it.
3. Add new family members as you would a Post.
4. Under "Member Data" you will enter the information for the person you are adding. As you add additional people, you can select parents and spouses from dropdown menus.
