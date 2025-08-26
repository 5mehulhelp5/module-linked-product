<?php
/**
 * @author    Sutunam
 * @copyright Copyright (c) 2024 Sutunam (http://www.sutunam.com/)
 */

declare(strict_types=1);

namespace Sutunam\LinkedProduct\Model\ProductLink\CollectionProvider;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductLink\CollectionProviderInterface;
use Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection as ProductCollection;
use Sutunam\LinkedProduct\Model\Product\Link;

class Linked implements CollectionProviderInterface
{
    public const KEY_LINKED_PRODUCTS = 'linked_products';

    /**
     * @inheritdoc
     */
    public function getLinkedProducts(Product $product): array
    {
        return $this->getCustomLinkedProducts($product);
    }

    /**
     * Retrieve array of linked products
     *
     * @param Product $product
     * @return array
     */
    public function getCustomLinkedProducts(Product $product): array
    {
        if (!$product->hasData(self::KEY_LINKED_PRODUCTS)) {
            $products = $this->getCustomLinkedProductCollection($product)->getItems();
            $product->setData(self::KEY_LINKED_PRODUCTS, $products);
        }
        return $product->getData(self::KEY_LINKED_PRODUCTS);
    }

    /**
     * Retrieve collection linked product
     *
     * @param Product $product
     * @return ProductCollection
     */
    public function getCustomLinkedProductCollection(Product $product): ProductCollection
    {
        return $product->getLinkInstance()
            ->setLinkTypeId(Link::LINK_TYPE_LINKED)
            ->getProductCollection()
            ->setIsStrongMode()
            ->setProduct($product);
    }
}
