# v-wordpress-plugin-update-api
WordPress mu-plugin and PHP api to update plugins from your own server. The mu-plugin runs though all installed plugins and sends domain, plugin, and version. The API compares domain to a list in HOSTS file , if domain exists it checks plugins against files in /plugins format plugin-slug_1.0.0.zip and updates if a new version is available.  

There is no major security yet. Its basic. Would love anyhelp making this better.

To-Do
1) Make this more secure


Note: There is a webhook trigger. for security remove that section of code. its just there for testing.
