# RB Agency
Model and Talent Agency CRM management and scheduling tool.

### Current Version 2.4.8

## Installation

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page. `[vimeo http://vimeo.com/27752740]`


## Change Log

### 2.4.8
* new feature - create your own custom media fields and add custom icons
* new feature - added new preset countries
* fixed - custom layout 9 - the setting is "display most recent uploaded file" but in profile view, the first photo that shows up is the first one uploaded.
* fixed - broken thumbnail images in search results page
* fixed - old videos embedded no longer compatible with latest version.
* new feature - added a star rating feature
* new feature - add a checkbox for bulk delete option for media & links section.
* new feature - added ability for admin to rename the demos individually on the back end to show on the front end instead of just "Listen to Demo"
* new feature - layout 03 - now showong video label instead of generic Video Slate, Demo Reel, Video Monologue
* fixed - admin cannot update profile's status manually.
* fixed - restore preset custom fields not working correctly.
* fixed - the search facility of the plugin is throwing a MySQL error when trying to search
* new feature - ability to set a custom field's value as expanded model detail under their names/thumbnails
* fixed - pagination in search results page is not working
* new feature - added a new section called Box/DVD Covers which can be use to feature magazines or dvd covers of the model/talent

### 2.4.7
* new image setting - if enabled, it will show filenames as photo caption of each photo in profile view.
* fixed - show thumbs slide setting. all photos slide when hovered if this option is enabled.
* fixed - Restore Preset Custom Fields
* fixed - Sort List and Sort Options
* added Gender as one of the Sort Options
* fixed - data for Date custom field is not getting loaded
* fixed - broken images when setting primary photo using the exporter tool
* fixed - date is not populated when using importer tool
* fixed - the email notification received by admin when someone registers contains html.
* fixed - i saved 2 profiles in casting  cart but only 1 is showing in preview.
* fixed - casting agent is not receiving the casting cart email
* fixed - age is not showing in frontend full profile view when using layout 00.
* fixed - dropdown multi-select is showing even if no value.
* fixed - profile should not be accessible by non-logged in users if setting is "Must be logged in to view model list, profile information, and search forms."
* added the Set this Profile to Private function; added another column in manage profile. even if profile listing is set to public, admin can set specific profiles to private.
* new feature - create e-cards wher admin can select upto 4 photos for each profile  and download as pdf
* created a setting so admin can replace the icon to whatever icon they want.
* new profile listing layout - lightbox and voice over (no image)
* shortcode created - ability to use 2 list layout at the same time for different pages.
* added the ability to redirect deleted profiles to custom url
* new download model card option with 2 layout options
* expiry notification settings
* fixed - when admin is editing the profile, the status changes back to pending for approval, admin must be able to change status to inactive, active but not visible, and archive.
* fixed - sort by date (joined)
* video title and caption are now editable by admin
* fixed - html codes in email notifications
* fixed - when sorting e.g. by age, the thumbnail layout is off
* fixed - profile per row settings not working
* fixed - name over image is not updating
* fixed - name under image with color is not updating
* fixed - if a profile is added by admin in the Talents Shortlisted by Admin section or in the Client's Casting Cart the profile should not get automatically be emailed of the job, unless the admin clicks on the “Resend notifcation to selected shortlisted talents” checkbox
* fixed - search issue - when searching for asian, other ethnicities shows up e.g. caucasian, african american, etc.
* fixed - maximum records returned in backend search is not working
* fixed - Layout 01 - Print Photos > PDF Download
* Removed "Short Order Tag" Server Dependency

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
* fixed - conflict between new imports and existing profiles, photos missing on existing profiles when imported profiles are scanned
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
