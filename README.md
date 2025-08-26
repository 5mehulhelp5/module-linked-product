# Hyvä Linked Product

# Summary
This module allows you to link products that are similar in style but differ in size, color, etc.

# Config


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
    1.0.0
        Init extension
