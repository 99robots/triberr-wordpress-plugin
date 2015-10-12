=== Plugin Name ===
Contributors: Dan Cristo, Andres Hernandez, Dino Dogan
Tags: triberr, twitter, remote publishing, blogging, guest post, guest posting, blog syndication, comment system, reBlog, social media, guest post
Requires at least: 3.0.0
Tested up to: 4.0.0
Stable tag: 4.0.0

What does Triberr Plugin do?

Sends your post immediately to Triberr without needing to crawl your RSS feed. 
Allows tribemates to view your post in their Tribal Stream thereby saving them time and increasing the likelihood of them sharing your post.
Allows you to send and receive guest posts to your blog through Triberr (reBlog/syndication function)
Pulls Bio/pic/social information from your Triberr profile and ensures every reBlog has proper attribution. 
Installs a unified view of all comments made across every instance of syndicated content. 
Displays Tribal Stream in Wordpress Dashboard

== Description ==

Triberr
This is an all-in-one plugin for members of Triberr.com. This is meant to seamlessly integrate your blog with Triberr so that it can send and receive posts, comments and guest posts.
**More info:**

* [Triberr](http://triberr.com/blog.php?post=24560)

== Installation ==

1. Upload the contents of triberr.zip to your plugins directory.
2. Activate the plugin
3. There will be a "Triberr" option in your Wordpress Settings menu.
4. Login to Triberr.com and go to your Account Settings -> Blog Settings page (http://triberr.com/pages/settings.php?tab=blogs). Click on "Show Blog Token". Copy the 35 digit string of characters and numbers.
5. Paste the Blog Token you copied from your Triberr Blog Settings page and paste it in the plugins page within Wordpress admin. Save.
4. That's all. When you publish or update a post, it will automatically be sent to Triberr. You can pause or delete any unwanted posts by logging into Triberr and going to your Sent Stream.

== Frequently Asked Questions ==

= Will I be able to select certain categories to send to Triberr?

Right now all posts go to Triberr, but you can delete them from you Sent Stream. Future versions will give you control over which posts you send.

= If I make a change to a post, will Triberr import it again?

Triberr will check to see if we already know about the post in our database. If so, we will not import it again. 

= Can I also import older posts into Triberr using this plugin?

No, Triberr will only accept posts published within the last week.

= If my post gets syndicated to other blogs, will I get the credit.

Yes. Every reBlog results in the Author info pulled along with the content, so regardless of where your post is published you will be prominently featured as the Author. 

== Screenshots ==

== Changelog ==
= 4.0.0 =
Improved plugin performance reducing plugin load time contribution from 75% to under 1%
Removed Triberr comment system
Removed reblogging functionality

= 3.0.1 =
Fixed an issue where other commenting systems were not showing

= 3.0.0 =
Scheduled posts are now properly imported into Triberr
Tribal Stream appears in Wordpress dashboard
Support for v1 Triberr API
Support for endorsement options
Triberr comment plugin is now placed in the default comment section of the blog as opposed to being appended to the bottom of the post
Rebloged posts no longer show the author bio twice

= 2.0.3 =
Saving the plugin version number
Fixed an issue with the default setting for global comment system from "Yes" to "On"

= 2.0.2 =
Saved the Triberr ID to the Wordpress Meta Data

= 2.0.1 =
Fixed a function conflict that crashed the plugin

= 2.0 =
Triberr Global Comment System

= 1.0 =
Beta release