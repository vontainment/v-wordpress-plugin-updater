![Header](./png_20230308_211110_0000.png)

# v-wordpress-plugin-update-api
WordPress MU-Plugin, PHP api and Web GUI to update plugins from your own server.


## Who Is This For
There are several great usage cases for this system. Our self-hosted API/Web GUI + MU-Plugin offering unique way of updating WordPress plugins without using the official repository.

One of the main benefits here is you do not have to edit any code in a plugin. Unlike the other ones available. This is great for a compatibility and ease of use.

This system is mostly geared to web developers and web designers who may want to push out updates to custom plugins without sharing them in the official repository.

This can also allow you to edit existing plugins from the official repository and customize them. And push out a separate release once you've implemented or changes after updates to the official plugin.

Anyone who's using a plugin not from the official repository and who doesn't want to update each site manually when new releases arrive.

For anyone worried about supply chain attacks. Or just wants to intercept and test updates without having to then manually update many sites.

But most of all it's just much easier than any other option. You don't need to change code or anything just the name of the zip file upload it and you're done.


## What Is New
- Added web admin to api
- Changed wpgetremote to curl


## To Install
- Drop in the MU-Plugin from the MU-Plugins directory to your sites. Set a key for security in the file. Also update api.php address.
- Upload the api-web-root directories contents to your webserver. Open index.php and set username and password.


## Change Log
3/10/23: Added download.php. This is the beginning of implementing security. The point will be to move the hosts file and the plugin directory outside of the webroot and have downloaded.php route the file requests after validating with the hosts file.

## WordPress mu-plugin, PHP API and Web GUI for Updating Plugins
This is a WordPress mu-plugin (must-use plugin) along with a PHP API and Web GUI that allows you to update your WordPress plugins from your own server. The mu-plugin iterates through all installed plugins and sends the domain, plugin, and version to the API.

The API compares the domain to a list in the HOSTS file. If the domain exists, it checks the plugins against files in the /plugins format, for example, `plugin-slug_1.0.0.zip`, and updates them if a new version is available. We have also added a web admin to the API for better user experience.

Please note that the security of the plugin is basic and there is no major security in place yet. We would appreciate any help in making this better.


### Using Web GUI
The Web GUI can have login name and password set in the index.php file.

The Web GUI is set into 2 sections. the first lets you add a host and key. The host is 'domain.com' & the key is 'anything'. You can add, edit or delete hosts and keys here.

The second section is plugins. This lets you upload plugins or delete them. As this is currently setup if you are adding a new update you must remove the old one for this to work. The plugins zip file should be the same as it would in the offical repo except for the naming. The plugin slug refers to the main plugin file before `.php`. The version is in standard format 1.1.1 Take the plug-in folder and add it to a zip archive with the name and the following format plugin-slug_1.2.1.zip.


### To-Do List
- Improve security measures

- Remove webhook trigger section of the code for security purposes (Ment for testing)

- Polish GUI ( Needs some tweaks)

- Protect plugin zips (Rigt now I made an htaccess file that should only get sites listed in the API hosts file to access but still testing.)
