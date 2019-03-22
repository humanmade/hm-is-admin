<table width="100%">
	<tr>
		<td align="left" width="70%">
			<strong>HM Is Admin</strong><br />
			Adds a custom capability and some helper functions to determine if the current user is a privileged user.
		</td>
		<td align="center" width="30%">
			<img src="https://img.shields.io/badge/license-GPLv2-blue.svg" alt="GitHub license" />
		</td>
	</tr>
	<tr>
		<td>
			A <strong><a href="https://hmn.md/">Human Made</a></strong> project. Maintained by @jazzsequence.
		</td>
		<td align="center" width="30%">
			<img src="https://hmn.md/content/themes/hmnmd/assets/images/hm-logo.svg" width="100" />
		</td>
	</tr>
</table>

# `is_hm_admin` #
**Contributors:**      [jazzsequence](https://github.com/jazzsequence), [bradp](https://github.com/bradp), [rmccue](https://github.com/rmccue), [dan-westall](https://github.com/dan-westall)  
**Stable tag:**        1.4.2  
**License:**           GPLv2  
**License URI:**       http://www.gnu.org/licenses/gpl-2.0.html

## Installation ##

You can use Composer to load this into your project but you'll need to add a repository for it:

```json
"require": {
	"humanmade/hm-is-admin": "^1.4.2"
},
"repositories": [
	{
		"type": "vcs",
		"url": "git@github.com:humanmade/hm-is-admin.git"
	}
]
```

## Description ##

Adds a custom capability and some helper functions to determine if the current user is a privileged user.
  
By default, privileged users are any users with `@humanmade.com`, `@humanmade.co.uk` or `@hmn.md` in their email address. Privileged domains can be filtered and HM proxy are given the capability by default.

## Development Process

The development process follows [the standard Human Made development process](http://engineering.hmn.md/how-we-work/process/development/).

Here's a quick summary:

* Assign issues you're working on to yourself.
* Work on a branch per issue, something like `name-of-feature`. One branch per feature/bug, please.
* File a PR early so it can be used for tracking progress.
* When you're finished, mark the PR for review by labelling with "Review &amp; Merge".
* Get someone to review your code, and assign to them; if no one is around, the project lead () can review.

## Changelog ##

### 1.4.2 ###
* bump version in composer file

### 1.4.1 ###
* Added composer install instructions.
* Fixed path to class file.
* Added `use` statement for `WP_User` class.

### 1.4.0 ###
* Added `composer.json` so package can be loaded as a composer library.
* Fixed version constant in main plugin file and updated plugin description.
* Updated plugin to more current coding standards.

### 1.3.1 ###
* Fixed typo in function call for `get_cap_name()` which caused fatals

### 1.3.0 ###
* Regex the domains and check if the current user email is in that list of domains. Domains can be filtered with `hm_is_admin_allowed_domains`.
* Allow a user ID/object to be passed to `is_hm_admin` to check if a specific user is an hm_admin.
* Other less significant changes like more `wds_admin` to `hm_admin` switches.

### 1.2.0 ###
* Adds a check for [hm-proxy-access](https://github.com/humanmade/hm-proxy-access). If a user is proxied in, the check  supercedes the email address check.
* Adds an optional parameter to `is_hm_admin` to manually bypass the proxy check.

### 1.1.0 ###
* Refactored default privileged users to be an array of users with `humanmade.co.uk` or `hmn.md` in their email address.

### 1.0.0 ###
* First release
