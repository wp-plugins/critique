=== Critique ===
Contributors: Phillip.Gooch
Tags: reviews, scores, grades
Requires at least: 3.4
Tested up to: 4.1
Stable tag: 1.2.3
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin for making either single score or multiple break down reviews on posts and/or pages.

== Description ==

Critique is a simple review plugin that can add single or multi sectioned reviews to either pages and/or posts. After installation and initial setup a new post meta box, "Critique Review Score", is added to the right of the post and/or page edit screens depending on your settings. If you do not see the metabox go to your screen options and make sure it is enabled. When posting, simply fill out the form using either the inputs or by clicking the score line, once you save the posting your critique score(s) will be saved and displayed on the front end as well. If you leave a section blank it will not be displayed on the front end nor will it be used to calculate the overall average score.

The front end display has numerous classes added to various elements and should be easy to adjust to match your specific sites theme. The default styles are basic and may work well with your existing theme. 

Since version 1.2.3 you can place the critique score box anywhere using the shortcode [critique_score], additional details on the settings page.

_Note: If in the future you change your scale it will not retroactively effect your old critiques, so if for the first year you were using a 5 star system, then switch to a # out of 100 system, the 5 star reviews will be grandfathered in._

== Upgrade Notice ==
If you are upgrading from a version prior to 1.2.3 you will need to re-select your "Display Options" setting. 

== Installation ==

1. Upload the `critique` directory to your `/wp-content/plugins/` directory.
2. Active the plugin.
3. Go to the new "Critique Setting" page and review/adjust the settings as desired.

The settings page has notes on their functions and should be self explanatory, but here are the highlights.

- **Active Post Types** determines where the critique metabox is displayed, either in posts and/or pages as desired.

- **Display Options** controls what is shown in a multi-item page (like the blog homepage). You have the options of displaying the entire review, just the overall, or nothing (by leaving both boxes unchecked).

- **Review Settings** set what the review scale you want to use it and, if desired, allows you to review based on multiple sections. If you are using multiple review sections you can also enable an automatically calculated "Overall Average" that will appear at the bottom of the section list when displayed on site.

== Frequently Asked Questions ==

= I got this great idea, can you implement it? = 

Probably, let me know and I'll see if I can work it in there.

== Screenshots ==

1. The Critique settings page.
2. The 5-Star Metabox.
3. The 5-Star Display.
4. The #/100 Metabox.
5. The Default #/100 display.

== Changelog ==

#### 1.2.3
 + Added support for shortcodes to display the critique score box and the option to disable the default score box placement.
#### 1.1.3
 + Fixed a bug that would cause extra backslashes in the style and script paths (although style and scripts still loaded properly).
 + Added pointer styles to the admin side review selection boxes to make it more appeant you can click on them to select a score.
 + _There is currently an issue with the admin scripts not loading while using this script with my "auto-seo" script, this issue is caused by auto-seo and an update will be pushed to that plugin fixing the issue soon._
#### 1.1.2
 + Fixed a bug that would cause HTML to show on page if the short post had to more link.
#### 1.1.1
 + Added the ability to have critique activate on any post_type with a UI enabled.
 + Added a nowrap on the metabox score cell.
 + Fixed a bug that would cause "0" to appear as the review section when no sections were set.
 + Fixed a bug where removing a section from the list would prevent it from being edited on older reviews.
 + Removed the version names since the WP site no longer seems to handle them properly.
#### 1.0.0
 + Initial Release