=== Plugin Name ===
Contributors: markjaquith
Donate link: http://txfx.net/code/wordpress/
Tags: comments, admin
Requires at least: 2.3
Tested up to: 2.7.1
Stable tag: trunk

Enables a "Comment Inbox" that gives you the power of a moderation queue without having to manually approve every comment.

== Description ==

Comment management in WordPress isn't too hard when you manually approve each comment.  You know which comments you've seen, and which you haven't.  The only downside is that you have to approve the comments before they appear.  Comment Inbox gives you the ease of the moderation queue with the freedom of unmoderated comments.  It's a better way to deal with spam and bacn on your blog.

Here's how it works:

* All comments except caught spam go into moderation (renamed "Comment Inbox")
* All comments in your Comment Inbox immediately show up on the blog -- so conversations don't wait on your moderation skills
* There are three actions that can be performed on comments in your Comment Inbox: Archive (i.e. mark as read), Spam, Delete
* When your Comment Inbox is empty, you can rest easy knowing you've dealt with all your new comments

For the history of this plugin, please see [The Comment Inbox](http://markjaquith.wordpress.com/2008/03/20/the-comment-inbox/).

== Installation ==

1. Download and unzip
2. Upload <code>comment-inbox.php</code> to your <code>/wp-content/plugins/</code> directory.
3. Activate it from within the WordPress admin interface.
4. Recommended: Turn OFF moderation e-mail notification in Options/Settings &raquo; Discussion
5. Done! Now every new incoming comment will go into your Comment Inbox (former known as the moderation queue).  All comments (except spam) will appear on the blog immediately.  Comments in the Comment Inbox can be archived (i.e. marked as "read"), spammed, or deleted.

== Frequently Asked Questions ==

= I can't disable the "An administrator must always approve the comment" option! =

This is intentional... it is how we funnel all comments into the Comment Inbox.  Ignore the wording.  All non-spam comments appear on the blog immediately.

= How do I stop all the "you have a comment awaiting moderation" e-mails? =

Go to Options/Settings > Discussion and uncheck the "[E-mail me whenever...] A comment is held for moderation" box.