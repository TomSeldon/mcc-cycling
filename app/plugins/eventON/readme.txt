=== EventON ===
Contributors: Ashan Jay
Plugin Name: EventON
Author URI: http://ashanjay.com/
Tags: calendar, event calendar, event posts
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 2.1.9

Event calendar plugin for wordpress that utilizes WP's custom post type.  

== Description ==
Event calendar plugin for wordpress that utilizes WP's custom post type. This plugin integrate eventbrite API to create paid events, add limited capacity to events, and accept payments for paid events or allow registration for free events. This plugin will add an AJAX driven calendar with month-view of events to front-end of your website. Events on front-end can be sorted by date or title. You can easily add events with multiple attributes and customize the calendar layout or build your own calendar using event post meta data. (directions to build your custom calendar in documentation) 


== Installation ==

1. Unzip the download zip file
1. Upload `ajde_evcal` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
=2.1.9 [2-13-5-6] =
FIXED: error on call to undefined function date_parse_from_format() for those running php 5.2
FIXED: Template error that cause entire site layout for some
FIXED: Widget title not appearing

=2.1.8 [2013-5-1]=
ADDED: basic single event page support and "../events/" url slug can be used to show calendar now - which is coming from a new page called "Events" in WP admin pages. 
ADDED: more/less custom language support
FIXED: new events not showing on calendar
FIXED: issue with EventON widget messing other widgets
FIXED: incorrect day name on multi-day event
FIXED: license version to update to current version after an update
FIXED: weird download issue with autoupdate
FIXED: incorrect date saving for non-american time format

=2.1.7 [2013-4-30]=
FIXED: event start date going to 1st of month error
FIXED: addons not showing issue
FIXED: error you get when saving styles
FIXED: array_merge error for addons

= 2.1.6 [2013-4-28]=
ADDED: ability to get automatic new updates
ADDED: new and exciting license management tab to myEventON settings
ADDED: new plugin update notifications 
ADDED: event date picker date format is now based off your site's date format
UPDATED: Event card got little jazzed up now
UPDATED: Main settings page - removed some junk
UPDATED: in-window pop up box, added new loading animation and notifications
UPDATED: EventON widgets UI
UPDATED: improved event generator class for faster loading
FIXED: issue with event close button not working for new months
FIXED: upcoming events list shortcode
FIXED: event time default value to 00
FIXED: minor style and functionality issues on eventON widget

= 2.1.5 [2013-4-18] =
ADDED: visible event type IDs to event types category page
ADDED: ability to duplicate events 
ADDED: more useful pluggable hooks into base plugin
ADDED: ability to disable google gmaps api partly and fully
ADDED: ability to set google maps zoom level
ADDED: close button at the bottom of each event details
UPDATED: frontend styles
UPDATED: backend settings tabs, better UI for language tab
UPDATED: event repeating UI
FIXED: issue with calendar font settings not working properly
FIXED: external event links not opening
FIXED: php template tag not working correctly

= 2.1.4 [2013-4-8] =
ADDED: a new shortcode popup box for better user experience

= 2.1.3 [2013-4-7] =
* Added support to open learn more links in new window
* Improvements to addon handling
* Few more minor bugs distroyed for good

= 2.1.2 [2013-4-5] =
* Minor bugs fixed
* Added the ability to disable google maps API
* Fix custom event type names on events column in backend
* Improvements on addon handling

= 2.1.1 [2013-3-28] =
* Fixed small bugs
* Added auto plugin update notifier for eventon
* Added upcoming events list support to widget

= 2.1 [2013-3-28] =
* Implemented hooks and filters for extensions and further customization
* You can now add addons to extend features of the calendar
* Fixed bunch more bugs
* Changed the name and a whole new shi-bang now
* Quick shortcode button on Page text editor

= 2.0.8 [2013-3-23]=
* Fixed bugs

= 2.0.7 [2013-3-17]=
* Fixed shortcode upcoming list issue
* Added the ability to hide empty months in upcoming list

= 2.0.6 [2013-2-28]=
* fixed minor error with usort array

= 2.0.5 [2013-2-25] =
* Added repeat events capability for monthly and weekly events
* Reconstructed the event computations system to support future expansions
* Now you can hide the sort bar from backend options
* Event card icons can be changed easily from backend now
* Added the template tag support for upcoming events list format
* Primary font for the calendar can also be changed from the backend options

= 2.0.4 [2013-2-11]=
* Added the ability to add an extra custom taxonomy for event sorting
* Custom taxonomies can be given custom names now
* Better control over front-end event sorting options
* Further minimalized the sort bar present on front-end calendar
* Fixed bugs on eventbrite and meetup api
* Added a learn more event link option
* Fixed event redirect when external link is empty
* Added 2 more different google map display types

= 2.0.3 [2013-1-13] =
* Fixed the bug with google map images

= 2.0.2 [2012-12-28] =
* Calendar arrow nav issue fixed in some themes

= 2.0.1 [2012-12-24] =
* Added the ability to create calendars with different starting months.

= 2.0 [2012-12-21] =
* Squished bugs in the code with data save and bunch of other stuff...
* Added Meetup API support to connect to meetup events and get event data in an interactive way.
* Updated eventbrite API to a more interactive event data-bridge setup.
* Added event organizer field.
* You can now link events to a url instead of opening event details.
* Event Calendar now support featured images for events right in the "event card".
* Added more animated effects to frontend of the calendar.
* Ditched the default skin to nail down some of the CSS issues with skins on "Slick"
* Updated event option saving method to streamline load time.
* Added TON of more customizable options

= 1.9 [2012-11- ]=
* Fixed saved dates and other custom event data dissapearing after auto event save in WP
* Improved custom style appending method
* Added Paypal direct link to event ticket payment
* Added easy color picker

= 1.8 [2012-10-23]=
* Added widget support
* UI Update to backend
* Existing skins update
* Improvements to algorithm

= 1.7 [2012-10-16]=
* Updated back-end UI
* Better hidden past event management
* Ability to disable month scrolling on front-end
* Added responsiveness to skins

= 1.6 [2012-5-31] =
* Multiple calendars in one page
* Calendar to show only certain event types with shortcode or template tags
* custom language for "no events"
* "Slick" new skin added
* Correct several CSS issues with parent CSS styles

= 1.5 [2012-5-1] =
* Improvement to code for faster loading
* Added smoother month transitions
* "Event Type" support for events
* Apply multiple colors to events and allow sorting by color
* Added "all day event" support
* Default wordpress main text editor is now used for event description box
* Better event data management

= 1.4 [2012-4-5] =
* CSS issues fixed
* Multiple Skin support 

= 1.3 [2012-1-31] =
* Minor changes to Interface design 
* New Loading spinner on AJAX calls
* Added auto Google Map API integration based on event location address
* Added control over past events display on the calendar
* Improvements to events algorithm for faster load time
* Bug fixed (End month and start month date issue)
* Bug fixed (Month filtering issues)

= 1.2 [2012-1-12] =
* Minor bugged fixed
* Back-end Internationalization
* Added plugin data cleanup upon deactivation

= 1.1 [2012-1-4] =
* Added custom language support

= 1.0 [2011-12-21] =
* Initial release