# Adobe Commerce / Magento 2 Performance optimisation module for avoid entity specific page cache bloat

This module provides configurable control over entity-specific page layout caching to prevent cache bloat in big stores.

## Problem

In Adobe Commerce / Magento 2, when `addPageLayoutHandles()` is called with `entitySpecific = true`, it creates
individual cache entries for each entity id or sku (catalog_category_view_id_123.xml or catalog_product_view_id_123.xml or catalog_product_view_sku_test.xml).
In large store setups, this can lead to massive cache growth.

## Solution

This module provides two possible configuration:
 1. Allow Entity-Specific Layout Handles (default: false) - when disabled, blocks all entity-specific layouts like `*_id_*.xml`
 2. Use Only Specific Validators (default: true) - When enabled, the entity-specific layout will be added only for validated requests

### Composer Installation

```bash
composer require hryvinskyi/magento2-page-layout-manager
php bin/magento module:enable Hryvinskyi_PageLayoutManager
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:clean
```

### Manual Installation

1. Create directory `app/code/Hryvinskyi/PageLayoutManager`
2. Download and extract module contents to this directory
3. Enable the module:

```bash
bin/magento module:enable Hryvinskyi_PageLayoutManager
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:clean
```

## Features

### CMS Page Layout Management

The module automatically disables entity-specific page layouts for CMS pages to prevent cache bloat:

- **Admin Panel**: The page layout field is disabled (grayed out) in the CMS page edit form with an informative message
- **Frontend**: Entity-specific page layouts are not applied to CMS pages - only global/theme-level layouts are used
- **Cache Optimization**: Prevents creation of separate cache entries for each CMS page layout variation

This ensures consistent layout handling across all CMS pages while significantly reducing cache storage requirements.

## Usage

### Creating Custom Validators

#### 1. Implement the Validator Interface

```php
<?php
namespace MyModule\Model\Validator;

use Hryvinskyi\PageLayoutManager\Api\RequestValidatorInterface;

class MyCustomValidator implements RequestValidatorInterface
{
    public function isRequestAllowed(
        array $parameters = [],
        ?string $defaultHandle = null,
        array $context = []
    ): bool {
        // Your validation logic here
        // Return true to allow entity-specific caching
        // Return false to block it

        // Example: Allow only specific product pages
        if ($defaultHandle === 'catalog_product_view') {
            $productId = $parameters['id'] ?? null;
            return in_array($productId, [1, 2, 3]); // Only these products
        }

        return false;
    }
}
```

#### 2. Create Parameter Modifier (Optional)

```php
<?php
namespace MyModule\Model\Modifier;

use Hryvinskyi\PageLayoutManager\Api\ModificationResultInterface;
use Hryvinskyi\PageLayoutManager\Api\ParameterModifierInterface;
use Hryvinskyi\PageLayoutManager\Model\ModificationResult;

class MyCustomParameterModifier implements ParameterModifierInterface
{
    public function modifyParameters(
        array $parameters,
        ?string $defaultHandle,
        array $context = []
    ): ModificationResultInterface {
        // Modify parameters when validator allows request
        $modifiedParameters = $parameters;
        $modifiedHandle = $defaultHandle;

        // Example: Add cache tags for better cache management
        if ($defaultHandle === 'catalog_product_view') {
            $modifiedParameters['cache_tags'] = ['product_cache_tag'];
            $modifiedHandle = 'catalog_product_view_optimized';
        }

        return new ModificationResult(
            $modifiedParameters,
            $modifiedHandle,
            $parameters, // original for comparison
            $defaultHandle // original for comparison
        );
    }
}
```

#### 3. Register in DI Configuration

```xml
<!-- etc/di.xml -->
<type name="Hryvinskyi\PageLayoutManager\Model\Strategy\ValidatorStrategy">
    <arguments>
        <argument name="requestValidators" xsi:type="array">
            <item name="my_custom_validator" xsi:type="object">MyModule\Model\Validator\MyCustomValidator</item>
        </argument>
        <argument name="parameterModifiers" xsi:type="array">
            <item name="my_custom_validator" xsi:type="object">MyModule\Model\Modifier\MyCustomParameterModifier</item>
        </argument>
    </arguments>
</type>
```

### Available Context Data

The `$context` array contains:
- `entity_specific`: boolean indicating if this is an entity-specific request
- Additional data can be passed by the calling code

### Available Parameters

The `$parameters` array contains the same data passed to `addPageLayoutHandles()`:
- Layout handle parameters like `['type' => 'some_type', 'id' => 123]`


## Contributing

Contributions are welcome! Please ensure:
- All tests pass
- New features include tests
- Code follows SOLID principles
- Documentation is updated

## License

Copyright © 2025 Volodymyr Hryvinskyi. All rights reserved.