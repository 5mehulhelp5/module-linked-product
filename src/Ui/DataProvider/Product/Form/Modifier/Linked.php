<?php
/**
 * @author    Sutunam
 * @copyright Copyright (c) 2024 Sutunam (http://www.sutunam.com/)
 */

declare(strict_types=1);

namespace Sutunam\LinkedProduct\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Related;
use Magento\Ui\Component\Form\Fieldset;

class Linked extends Related
{
    public const DATA_SCOPE_LINKED = 'linked';

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta): array
    {
        $meta[static::GROUP_RELATED]['children'][$this->scopePrefix . static::DATA_SCOPE_LINKED] =
            $this->getLinkedProductsFieldset();

        $meta[static::GROUP_RELATED]['arguments']['data']['config']['label'] =
            __('Related Products, Up-Sells, Cross-Sells and Linked Products');

        return $meta;
    }

    /**
     * @inheritdoc
     */
    protected function getDataScopes(): array
    {
        return [
            static::DATA_SCOPE_LINKED
        ];
    }

    /**
     * Prepares config for the linked products fieldset
     */
    protected function getLinkedProductsFieldset(): array
    {
        $content = __('Products that are similar in style but differ in size, color, etc.');

        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    $content,
                    __('Add Linked Products'),
                    $this->scopePrefix . static::DATA_SCOPE_LINKED
                ),
                'modal' => $this->getGenericModal(
                    __('Add Linked Products'),
                    $this->scopePrefix . static::DATA_SCOPE_LINKED
                ),
                static::DATA_SCOPE_LINKED => $this->getGrid($this->scopePrefix . static::DATA_SCOPE_LINKED),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Linked Products'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 40,
                    ],
                ],
            ]
        ];
    }
}
