# darkanakin41/table-bundle
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
composer require darkanakin41/table-bundle
```

## Dependencies
* [KnpLabs/KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle)

## Configuration 
Configuration is, at the moment, rather basic : 
```yaml
p_lejeune_table:
    template:
        # Select the template for all fields
        fields: '@Darkanakin41Table/foundation/fields.html.twig'
        # Select the template for all table
        table: '@Darkanakin41Table/foundation/table.html.twig'
```

## Usage
In order to use the bundle, you must declare your own Table class which will extends ```Darkanakin41\TableBundle\Definition\AbstractTable```

Then, you'll have to override method ```public function __init__() ``` in order to define the differents columns you need

Next, you'll need to pass the table to the view and in the template, use a twig extension from the bundle to display it : 
```twig
{{ table_render(table) }}
```

## TODO 
* Add another way of displaying search form
* Add template for bootstrap
