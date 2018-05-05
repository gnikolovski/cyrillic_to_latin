# Cyrillic to Latin

## CONTENTS OF THIS FILE

  * Introduction
  * Requirements
  * Installation
  * Configuration
  * Author

## INTRODUCTION

Cyrillic to Latin module will convert strings that are passed through Drupal t() 
function and string/text field values. If you are using the Address module, 
country name will also be converted to latin.

## REQUIREMENTS

No special requirements.

## INSTALLATION

Install as you would normally install a contributed Drupal module. See: 
https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules 
for further information.

The recommended way to install the module is via Composer:

```
composer require drupal/cyrillic_to_latin
```

and then enable it with Drush:

```
drush en cyrillic_to_latin -y
```

## CONFIGURATION

You can enable/disable converting here:

```
'/admin/config/regional/cyrillic-to-latin'
```

### AUTHOR

Goran Nikolovski  
Website: http://www.gorannikolovski.com  
Drupal.org: https://www.drupal.org/u/gnikolovski  
Email: nikolovski84@gmail.com  

Company: Studio Present, Subotica, Serbia  
Website: http://www.studiopresent.com  
Drupal: https://www.drupal.org/studio-present  
Email: info@studiopresent.com  
