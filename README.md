# v-wordpress-plugin-update-api
WordPress MU-Plugin, PHP api and Web GUI to update plugins from your own server.


## What Is New
- Added web admin to api
- Changed wpgetremote to curl


## To Install
- Drop in the MU-Plugin from the MU-Plugins directory to your sites. Set a key for security in the file. Also update api.php address.
- Upload the api-web-root directories contents to your webserver. Open index.php and set username and password.


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

- Remove webhook trigger section of the code for security purposes

- Polish GUI

- Protect plugin zips