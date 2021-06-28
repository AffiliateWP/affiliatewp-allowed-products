# AffiliateWP Allowed Products

This plugin requires [AffiliateWP](http://affiliatewp.com/ "AffiliateWP") in order to function.

This plugin allows you to choose which products on your site should earn commission for your affiliates. Although AffiliateWP allows you to disable commission on products (for supported integrations), it can be cumbersome to disable commission on every single one, especially if you have hundreds of products. Install and activate this plugin, enter some product IDs, and only these products will generate commission when purchased via a referral URL.

## Changelog

### 1.2
* New: Enforce minimum dependency requirements checking
* New: Requires PHP 5.6 minimum
* New: Requires WordPress 5.0 minimum
* New: Requires AffiliateWP 2.6 minimum

### 1.1.3

* Improved: Removes notice dismiss functionality from no product warning.
* Improved: Tested for WordPress version 5.5

### 1.1.2

* Fix: EDD and WooCommerce integrations not working properly.

### 1.1.1

* Fix: Referrals being recorded with an amount of 0.00

### 1.1

* Fix: Admin notice is not dismissible in some circumstances
* Fix: All commission rates set to 0 when used with non-supported integrations
* Tweak: Encapsulate the plugin loader and activation into a class to avoid errors

### 1.0.2

* New: Dismissable admin notice shown in admin when no product IDs have been entered

### 1.0.1

* Fix: prevent affiliate referral notifications from being sent if the product is not allowed to earn a commission

### 1.0

* Initial release
