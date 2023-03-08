# v-wordpress-plugin-update-api
WordPress mu-plugin and PHP api to update plugins from your own server. 

The mu-plugin runs though all installed plugins and sends domain, plugin, and version. 

The API compares domain to a list in HOSTS file , if domain exists it checks plugins against files in /plugins format plugin-slug_1.0.0.zip and updates if a new version is available.



Added web admin to api

There is no major security yet. Its basic. Would love anyhelp making this better.

To-Do
1) Make this more secure


Note: There is a webhook trigger. for security remove that section of code. its just there for testing.

the plugin slug is the main plugin file before .php
## WordPress mu-plugin and PHP API for Updating Plugins

This is a WordPress mu-plugin (must-use plugin) along with a PHP API that allows you to update your WordPress plugins from your own server. The mu-plugin iterates through all installed plugins and sends the domain, plugin, and version to the API.

The API compares the domain to a list in the HOSTS file. If the domain exists, it checks the plugins against files in the /plugins format, for example, `plugin-slug_1.0.0.zip`, and updates them if a new version is available. We have also added a web admin to the API for better user experience.

Please note that the security of the plugin is basic and there is no major security in place yet. We would appreciate any help in making this better. 

### To-Do List

- Improve security measures

- Remove webhook trigger section of the code for security purposes

### Plugin Slug

The plugin slug refers to the main plugin file before `.php 



## WordPress mu-plugin and PHP API for Updating Plugins

This is a WordPress mu-plugin (must-use plugin) along with a PHP API that allows you to update your WordPress plugins from your own server. The mu-plugin iterates through all installed plugins and sends the domain, plugin, and version to the API.

The API compares the domain to a list in the HOSTS file. If the domain exists, it checks the plugins against files in the /plugins format, for example, `plugin-slug_1.0.0.zip`, and updates them if a new version is available. We have also added a web admin to the API for better user experience.

Please note that the security of the plugin is basic and there is no major security in place yet. We would appreciate any help in making this better. 



### How To Name Files

The plugin slug refers to the main plugin file before `.php`. 

The version is in standard format 1.1.1

Take the plug-in folder and add it to a zip archive with the name and the following format plugin-slug_1.2.1.zip


### To-Do List

- Improve security measures

- Remove webhook trigger section of the code for security purposes
