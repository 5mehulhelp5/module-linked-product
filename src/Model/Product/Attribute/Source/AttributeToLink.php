<?php
/**
 * @author    Sutunam
 * @copyright Copyright (c) 2024 Sutunam (http://www.sutunam.com/)
 */

declare(strict_types=1);

namespace Sutunam\LinkedProduct\Model\Product\Attribute\Source;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

class AttributeToLink extends AbstractSource implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function getAllOptions()
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $options = [];
        foreach ($collection as $attribute) {
            /** @var Attribute $attribute */
            if (!in_array($attribute->getFrontendInput(), ['select', 'multiselect'])) {
                continue;
            }
            $options[] = [
                'value' => $attribute->getAttributeCode(),
                'label' => $attribute->getDefaultFrontendLabel()
            ];
        }

        usort($options, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        array_unshift($options, ['value' => '', 'label' => __('-- Please Select --')]);

        return $options;
    }
}
