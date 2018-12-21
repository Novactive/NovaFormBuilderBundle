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


## Install

```bash
make installez
make serveez
```

## Migrate DB from Ez Survey

The database of the old **Ez Survey Bundle** can be migrated to this **Novactive Form Builder Bundle**.
To do that run the following commands inside _ezplatform_ folder:

    php bin/console novaformbuilder:migrate --export
    php bin/console novaformbuilder:migrate --import

The first one exports the data from the old database to json files.
The second one imports the data from json files to the new database.
After that the dumped data is still in the json files inside web/var/site/storage/files/forms folder. 
They can be removed manually if they are not needed anymore.

There is also the option for both cases to truncate the current **Novactive Form Builder Bundle** 
tables in the database:

    php bin/console novaformbuilder:migrate --clean

Contributing
----------------

[Contributing](CONTRIBUTING.md)


Change and License
------------------

[License](LICENSE)


----
Made with <3 by novactive.
