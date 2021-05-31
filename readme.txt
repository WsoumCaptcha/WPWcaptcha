=== Plugin Name ===
Contributors: wsoum
Donate link: https://wsoum.ml
Tags: comments, spam, anti-spam, comment, prevent, wsoum, captcha, protection, arabic
Requires at least: 3.0.0
Tested up to: 3.3
Stable tag: 1.0.1

A simple comment captcha protection.

== Description ==

A simple comment captcha protection based on [Wsoum API](https://captcha.wsoum.ml/) (support arabic & english).

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `wsoum-captcha` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If the used theme use a custom comment form, place `<?php echo apply_filters( 'comment_form_field_comment', '' ); ?>` just before submit button of the comment form (usually in the comment.php file).

== Screenshots ==

1. Admin settings page
2. Test page

== Changelog ==

= 1.0 =
* First version.

== About the plugin ==

This plugin use the great [Wsoum API](https://captcha.wsoum.ml/), and is a [wsoum](https://wsoum.ml/) concept.