Post To Moderation Queue [by [Gabriel Zanetti][author]]
=======================================================

Description
-----------

Post To Moderation queue is a [Question2Answer][Q2A] plugin that sends potential SPAM posts to the moderation queue.

Features
--------

 * When a user creates or edits a question, answer or comment, if it is suspected to be SPAM, it will be sent to the moderation queue
 * Potential SPAM detection is based on the presence of:
    * Emails
    * Phone numbers
    * URLs (in text form)
    * HTML Links
 * A configurable minimum amount of points can be used to exclude users from these checks
 * Internationalization support
 * No need for core hacks or plugin overrides
 * Simple installation

Requirements
------------

 * Q2A version 1.8.0+
 * PHP 7.0.0+

Installation instructions
-------------------------

 1. Copy the plugin directory into the `qa-plugin` directory
 1. Enable the plugin from the *Admin -> Plugins* menu option
 1. Click on the `Save` button

Support
-------

If you have found a bug then create a ticket in the [Issues][issues] section.

Get the plugin
--------------

The plugin can be downloaded from [this link][download]. You can say thanks [donating using PayPal][paypal].

[Q2A]: https://www.question2answer.org
[author]: https://question2answer.org/qa/user/pupi1985
[download]: https://github.com/pupi1985/q2a-pupi-ptmq/archive/master.zip
[issues]: https://github.com/pupi1985/q2a-pupi-ptmq/issues
[paypal]: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Y7LUM6ML4UV9L
