# WordPress Meta Fields Builder
A robust, flexible, and easy-to-use PHP library to quickly create, register, and reuse meta fields and meta boxes configurations, and keep them in your source code repository.

## Install
Use composer to install:
```
composer require ivanyankov/meta-fields-builder
```
If your project isn't using composer, you can require the `autoload.php` file.

## Usage
This should give you a comprehensive view of how to create meta boxes with fields, where you can specify not only the fields themselves but also where these meta boxes should appear.

### Simple Example
```php
$builder = new MetaBoxBuilder();
$builder->setId('custom_meta_box')
        ->setTitle('Custom Meta Box')
        ->setLocation('page', $post_id = null)
        ->addField(new TextField('text_field', 'Text Field'));

$builder->build();
```
