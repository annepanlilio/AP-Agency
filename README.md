# RB Agency
Model and Talent Agency CRM management and scheduling tool.

### Current Version 2.4.3

## Installation

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page. `[vimeo http://vimeo.com/27752740]`


## Change Log

### 2.4.6
* added the ability to hide expanded model details (age and state) on selected profile or profiles
* fixed - not all folders are getting renamed when switched to auto-generated ID settings
* fixed - not all folders are getting created after import for auto-generated ID settings
* fixed - uploaded polaroid not showing in manage profile (admin crm)
* fixed - thumbnails in casting cart received via email are broken
* fixed - one profile is excluded when exporting profile database
* fixed - download pdf in profile view not working
* created shortcodes to display age and state which can be added in pages. e.g. [profile_list show_age_year="true" * show_age_month="true" show_age_day="true" show_state="true"]
* fixed - no value entered but showing January 01, 1970
* fixed - bulk delete of photos not working
* fixed - conflict between new imports and existing profiles, photos missing on existing profiles when imported profiles are scanned.
* fixed - mass email (admin crm)
* fixed - custom field “date” is not getting imported
* created new custom field type “link”
* fixed - profiles in casting cart link is not in alphabetical order
* fixed - custom fields without value still shows labels
* added the ability to add any custom fields to the Sort Filter in profile listing, created a setting where user can enable/disable
* drag and drop re-order of photos
* added new search setting “birthdate”
* logo for pdfs and print are now pulled from the link entered in the settings
* added the ability to create custom fields for casting jobs
* added the ability to create custom fields for casting registration

### 2.4.3
* Added Custom URL's to Profiles
* Show hidden fields in Quick Print

### 2.4
* Easy updating!  No more need to download from GitHub, once upgraded to 2.4 you can upgrade from WordPress easily.
* Bug Fixes

### 2.3
* Optimized SQL queries to increase site speed!
* Updated Profile Layout types
* Large refactor to plugin structure
* Added Registration shortcode (Interact)
* Resolved WooCommerce conflict

### 2.2
* Added Multi-Select Dropdown as new custom field type
* Upgraded to support WordPress 4.0 changes
* Restored Featured Profile widget
* Bug fixes


### 2.1
* New custom field "Date".  Ability to search & sort by Date
* User settings enhancement
* Search optimization & enhancement
* PDF download bugfixes
* Bulk password generator enhancement
* Added "SoundCloud" as media option
* Change image shown in casting cart (other than default)


### 2.0.9
* Added override to be able to turn on-off redirect on login.  Agency > Settings > Interact Settings
* Added ability to manage media dropdown values (currently “Headshot, Comp Card, Video Slate, etc”) now can be managed in Agency > Settings > Media Categories (/wp-admin/admin.php?page=rb_agency_settings&ConfigID=6)
* Fixed Manage Media types( added Link Type and File Type. In-progress manage profile with the custom media types)
* Removed notices
* Changed mysql-* to wpdb and added wpdb prepare queries
* Fixed Dummy generator bugs
* Fixed Export and Import Profiles
* Fixed trailing slashes issues in custom fields
* Removed notices and warnings

### 2.0.8
* Importer Bugfix
* Rename Folders Issue
* Video Embed Updates
* Sidebar Widget
* Custom Fields: Value intact after changing type
* Misc. Minor bugfixes & Code standardization

### 2.0.3
* Major Overhaul of PHP classes
* Add notice if upgrade is available
* Code Cleanup

### 2.0.2
* Minor Updates
