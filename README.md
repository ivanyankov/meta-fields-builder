# WordPress Meta Fields Builder
A robust, flexible, and easy-to-use PHP library to quickly create, register, and reuse meta fields and meta boxes configurations, and keep them in your source code repository.

## Install
Use composer to install:
```
composer require ivanyankov/meta-fields-builder
```
If your project isn't using composer, you can require the `autoload.php` file.

## Available Fields

| Field | Class | Description |
|-------|-------|-------------|
| Text | `TextField` | Single-line text input |
| Textarea | `TextareaField` | Multi-line text input with configurable rows |
| URL | `URLField` | URL input with validation |
| Number | `NumberField` | Numeric input with min, max and step constraints |
| Select | `SelectField` | Dropdown from key-value option pairs |
| Checkbox | `CheckboxField` | Single on/off toggle |
| Image | `ImageField` | WordPress media library uploader (stores attachment ID) |

## Usage
This should give you a comprehensive view of how to create meta boxes with fields, where you can specify not only the fields themselves but also where these meta boxes should appear.

### Simple Example
```php
use Yankov\MetaFieldsBuilder\MetaBox\MetaBoxBuilder;
use Yankov\MetaFieldsBuilder\Fields\TextField;

$fields = [
    new TextField('text_field_name', 'Text Field')
];

$metaBox = MetaBoxBuilder::make('custom_meta_box_id', 'Advanced Custom Meta Box', $fields, 'post');
```

### Using Multiple Field Types
```php
use Yankov\MetaFieldsBuilder\MetaBox\MetaBoxBuilder;
use Yankov\MetaFieldsBuilder\Fields\TextField;
use Yankov\MetaFieldsBuilder\Fields\TextareaField;
use Yankov\MetaFieldsBuilder\Fields\URLField;
use Yankov\MetaFieldsBuilder\Fields\NumberField;
use Yankov\MetaFieldsBuilder\Fields\SelectField;
use Yankov\MetaFieldsBuilder\Fields\CheckboxField;
use Yankov\MetaFieldsBuilder\Fields\ImageField;

$fields = [
    new TextField('company_name', 'Company Name'),
    new TextareaField('description', 'Description', rows: 6),
    new URLField('website', 'Website URL'),
    new NumberField('rating', 'Rating', min: 0, max: 5, step: 0.1),
    new SelectField('status', 'Status', [
        'active'   => 'Active',
        'inactive' => 'Inactive',
    ]),
    new CheckboxField('featured', 'Featured'),
    new ImageField('logo', 'Logo'),
];

$metaBox = MetaBoxBuilder::make('company_details', 'Company Details', $fields, 'post');
```

### Restricting to a Specific Page
```php
$metaBox = MetaBoxBuilder::make('page_settings', 'Page Settings', $fields, 'page', $page_id);
```

## Retrieving Data
Get the saved data by calling the WordPress `get_post_meta` function:
```php
$field_value = get_post_meta($post_id, 'text_field_name', true);
```
