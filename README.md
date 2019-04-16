# plejeune/table-bundle
This bundle is designed to grant functionnalities to create simple table based on entities.

Current available template : 
* [Foundation](https://foundation.zurb.com/)

## Features

* Create a table base on an entity
* Select a custom template for all table
* Select a custom template for specific table

## Installation
First, install dependencies (next chapter)
Simply import it into your project with composer
```bash
composer require plejeune/table-bundle
```

## Dependencies
* [KnpLabs/KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle)

## Configuration 
Configuration is, at the moment, rather basic : 
```yaml
template:
    # Select the template for all fields
    fields: '@PLejeuneTable/foundation/fields.html.twig'
    # Select the template for all table
    table: '@PLejeuneTable/foundation/table.html.twig'
```

## TODO 
* Add another way of displaying search form
* Add template for bootstrap
