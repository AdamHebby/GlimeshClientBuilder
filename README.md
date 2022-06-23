![Coverage](.github/coverage.svg)

# GlimeshClientBuilder
Builds Object models based on glimesh.tv GraphQL API Schema


This is primarily used as part of [AdamHebby/GlimeshClient](AdamHebby/GlimeshClient).

The models / Objects built from the Glimesh.tv GrapQL API are used to map request responses to objects dynamically. I decided to build a code builder to automatically build these objects whenever the API changes.

## Usage

### CLI Usage

Example:
```
php vendor/bin/glimbuild --schema path/to/schema.json --output src/Glimesh/ --namespace YourProjectNamespace --doc-author "Your Name <youremail@example.org>"
```

Doc:
```
php vendor/bin/glimbuild --help

Usage:
    php bin/glimbuild --schema=<filepath.json> --output=<path/to/directory/> --namespace=<NameSpace>

Options:
    --help                         Show this help message
    --schema=<filepath.json>       The schema to build from
    --output=<path/to/directory/>  The output directory to write to
    --namespace=<NameSpace>        The output namespace to map classes to
    --doc-<name>=<value>           Set a custom class-level doc value. Allows one of:
                                   author, category, copyright, deprecated, example,
                                   ignore, internal, license, link, method, package,
                                   property, property-read, property-write, see, since,
                                   source, subpackage, todo, uses, version
```

### OOP Usage

Example:
```php
$config = (new BuilderConfig())
    ->setApiJsonFilePath('path/to/schema.json')
    ->setOutputDirectory('src/Glimesh/')
    ->setNamespace('YourProjectNamespace')
    ->setStandardDocBlock([
        ' * @author Your Name <youremail@example.org>',
        ' * @package GlimeshClient',
        ' * @copyright YourCompany Ltd 2022'
    ]);

(new Builder($config))->build();
```
