# WP Staging A/B testing 

Initial version of simple A/B testing of variants on a website.

Although the plugin is functional, it is not yet ready for production use. It is a work in progress.

Although this is a WordPress plugin and can be activated with one click, 
it's not intended to add complex WordPress core functionality to this plugin for performance purposes.

- Don't use wpdb.
- Use the PDO library.
- Don't make excessive use of WordPress hooks and use them rarely.
- Don't use the WordPress options table for storing data and store data in a separate table.

## Installation

- Open Database.php and add your credentials there. @todo use wp-config.php values instead or an .env file.
- Install the plugin as you would any other WordPress plugin.
- Adjust the JS based tests in the folder `assets/js/` file.
- Copy each JS file to create a new separate test.