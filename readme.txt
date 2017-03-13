=== AffiliateWP - Allowed Products ===
Contributors: sumobi, mordauk, drewapicture
Tags: AffiliateWP, affiliate, Pippin Williamson, Andrew Munro, mordauk, pippinsplugins, sumobi, ecommerce, e-commerce, e commerce, selling, referrals, easy digital downloads, digital downloads, woocommerce, woo, products, product, allowed, affiliate marketing
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows only specific products to generate commission in AffiliateWP.

== Description ==

> This plugin requires [AffiliateWP](http://affiliatewp.com/ "AffiliateWP") in order to function.

This plugin allows you to choose which products on your site should earn commission for your affiliates. Although AffiliateWP allows you to disable commission on products (for supported integrations), it can be cumbersome to disable commission on every single one, especially if you have hundreds of products. Install and activate this plugin, enter some product IDs, and only these products will generate commission when purchased via a referral URL.

**What is AffiliateWP?**

[AffiliateWP](https://affiliatewp.com/ "AffiliateWP") provides a complete affiliate management system for your WordPress website that seamlessly integrates with all major WordPress e-commerce and membership platforms. It aims to provide everything you need in a simple, clean, easy to use system that you will love to use.

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin

OR you can just install it with WordPress by going to Plugins &rarr; Add New &rarr; and type this plugin's name

Go to Affiliates &rarr; Settings &rarr; Integrations and enter the IDs of the products you want to allow commission for.

== Screenshots ==

1. The input field to enter the product IDs that should receive commission.

== Changelog ==

= 1.1.1 =

* Fix: Referrals being recorded with an amount of 0.00

= 1.1 =
* Fix: Admin notice is not dismissible in some circumstances
* Fix: All commission rates set to 0 when used with non-supported integrations
* Tweak: Encapsulate the plugin loader and activation into a class to avoid errors

= 1.0.2 =
* New: Dismissable admin notice shown in admin when no product IDs have been entered

= 1.0.1 =
* Fix: prevent affiliate referral notifications from being sent if the product is not allowed to earn a commission

= 1.0 =
* Initial release
