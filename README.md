# Novactive Form Builder

| Branch   | Travis build status |
|:--------:|:-------------------:|
| master   | [![Build Status](https://travis-ci.org/Novactive/NovaFormBuilderBundle.svg?branch=master)](https://travis-ci.org/Novactive/NovaFormBuilderBundle)


A bundle to create dynamic symfony form.

This bundle aims to provide a _lib_ to help generating dynamic form in a symfony app.

It provides 2 bundles:

- `bundle` the symfony bundle
- `ezbundle` the bridge to use bundle in eZ Platforn

> Note that eZ Platform is a pure symfony app then the bridge is just about wiring the IHM

<br>
<hr/>
<br>

# Installation

### Requirements

* eZ Platform 2+
* PHP 7.1+
* MySQL 5.7.8+ / Maria DB 10.1+

### Installation steps

Run `composer require novactive/formbuilder` to install the bundle and its dependencies:

### Register the bundles

Activate the bundle in `app\AppKernel.php` file.

```php
// app\AppKernel.php

public function registerBundles()
{
   ...
   $bundles = array(
        new FrameworkBundle(),
        ...
        // FormBuilder bundles
        new Novactive\Bundle\FormBuilderBundle\FormBuilderBundle(),
        new Novactive\Bundle\eZFormBuilderBundle\NovaeZFormBuilderBundle()
   );
   ...
}
```

### Add routes

```yaml
_novaezmailing_routes:
    resource: '@NovaeZMailingBundle/Resources/config/routing.yml'
```

### Install the database schema

```bash
bin/console novaformbuilder:install
```

### Troubleshooting

If the bundle web assets (css, js etc.) are missing in the public directory it can be fixed by running the following commands:
```bash
bin/console assets:install --symlink --relative
bin/console assetic:dump
```
That will install bundles web assets under a public directory and dump them to the filesystem.

Also if the **translations** are not loaded at once clearing the Symfony cache folder must help. 

<br>
<hr/>
<br>

# Migrate DB from Ez Survey

The database of the old **Ez Survey Bundle** can be migrated to this **Novactive Form Builder Bundle**.
To do that run the following commands inside _ezplatform_ folder:

    php bin/console novaformbuilder:migrate --export
    php bin/console novaformbuilder:migrate --import

The first one exports the data from the old database to json files.
The second one imports the data from json files to the new database.
After that the dumped data is still in the json files inside web/var/site/storage/files/forms folder. 
They can be removed manually if they are not needed anymore.

What the migration script does is:
It takes all surveys to convert them into forms. Each of them is related to particular _Ez content_. 
If more than one record have the same content_id the script takes the latest one due to the _ID_ value.
Then it takes all questions to convert into fields and results + questions results to convert into submissions.
 

There is also the option to truncate the current **Novactive Form Builder Bundle** 
tables in the database:

    php bin/console novaformbuilder:migrate --clean


After running the Migrate scripts you might need to clear the **Redis Cache** 
if it's used on the project to apply the changes that have been made to the database.
<br>
<hr/>
<br>

Contributing
----------------

[Contributing](CONTRIBUTING.md)


Change and License
------------------

[License](LICENSE)


----
Made with <3 by novactive.
