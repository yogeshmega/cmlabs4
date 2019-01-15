=== User Last Visit ===

Contributors: cnhk_systems
Donate link: https://www.elance.com/s/cnhk/
Tags: user, visit, multisite, record, logged in
Requires at least: 3.8
Tested up to: 4.2.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin keeps record on each user last visit time using logged-in status, user ID and user meta data. Multisite compatible.

== Description ==

The plugin keeps record in UNIX timstamp format of the last time each logged in user visits the site. Record for each user is directly
visible on the users list table in the admin panel. The plugin also provides some utility function and hook.

= Function Description = 

**`user_last_visit( $user_id = "current", $verbose = TRUE );`**

 * Parameters :
- mixed `$user_id`, the user ID or "current" for the current user (logged in user). default: "current".
- bool `$verbose`, if `TRUE` the result returned is a literal expression of the last visit time. default: `TRUE`. 

 * Returned value :

If `$verbose` is `TRUE` a string is returned. If `$verbose` is `FALSE` the UNIX timestamp of last visit is returned. When the user is not logged in, or when there is no record yet, the function return `FALSE` if `$verbose` is set to `FALSE`, a string if `$verbose` is `TRUE`.

= Filter Hook = 

**`"ulv-can-record"`** : located in `"./includes/user-last-visit.class.php"` around line #24

Some users or some pages can be excluded for last visit recording. There is an admin page for that. But you can also add some additional filtering via this hook. This filter hook is applied within a `wp_loaded` action. So you must attach your functions before that (typically on `init`, once the user is authenticated). 
You can eventually use the `User_Last_Visit` class separately in your theme/plugin.

= Available Translations =

Français

== Installation ==

= Requirement =
* Nothing special

= Manual Installation =
* Upload the .zip file to wp-content/plugins/
* Activate the plugin

== Frequently Asked Questions ==

= The plugin is installed and activated, but still no last visit time = 

By default, record are disabled for everybody, every pages. Verify the plugin's settings. The admin page is on a sub-menu of the "Settings" main menu.

= It works for some users only =

Records are set only once the plugin is installed and set up. The ***"General record exclusion"*** is evaluated first, then the ***"User role"*** and finally each ***"user ID"*** individually.

After the first installation, there is no record yet until the user visit the site (and passes through all exclusion conditions).

== Changelog ==

= 1.0 =
* minor UI improvement
* admin js en css versionning

= 0.8.2
* fix a bug in non-multisite created by precedent version

= 0.8.1 =

* fixed some multisite issues.

= 0.8 =

* initial release
