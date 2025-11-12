# Linked Product

# Summary
This module allows you to link products that are similar in style but differ in size, color, etc.

# Config
### General
Enable the module go to Sutunam -> Linked Product -> General -> Enable [Yes].<br>
Show product Linked on product List go to Sutunam -> Linked Product -> General -> Show on product listing [Yes].<br>
Show product Linked on product view go to Sutunam -> Linked Product -> General -> Show on product view [Yes].<br>
Show Attribute text go to Sutunam -> Linked Product -> General -> Show attribute text[Yes].<br>
Show available linked products count on product listing instead of the actual products go to Sutunam -> Linked Product -> General -> Show available linked products count on product listing instead of the actual products [Yes].<br>
Show stock status go to Sutunam -> Linked Product -> General -> Show stock status [Yes].<br>
Show stock status text on product listing go to Sutunam -> Linked Product -> General -> Show stock status text on product listing [Yes].<br>
Show stock status text on product view go to Sutunam -> Linked Product -> General -> Show stock status text on product view [Yes].<br>

### Mapping
Sutunam -> Linked Product -> Mapping -> Available products count text.<br>

For example:<br>
Attribute code | Singular | Plural <br>
color | Available in 1 colors | Available in 1 color <br>

# Installation

## Composer

Add Sutunam composer repository:

```json
"repositories": {
"sutunam": {
"type": "composer",
"url": "https://composer.sutunam.com/m2/"
}
```

```bash
composer require sutunam/linked-product
```

Then, execute the following Magento commands:

```bash
bin/magento module:enable Sutunam_LinkedProduct
bin/magento setup:upgrade
bin/magento cache:flush
```

If you are in production mode, also run:

```bash
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
```

# Change log
    1.0.1
        Update Readme.md
    1.0.0
        Init extension
