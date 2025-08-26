<?php
/**
 * @author    Sutunam
 * @copyright Copyright (c) 2024 Sutunam (http://www.sutunam.com/)
 */

declare(strict_types=1);

namespace Sutunam\LinkedProduct\Plugin\Magento\Catalog\Block\Product;

use Magento\Catalog\Model\Product\Visibility;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Store\Model\StoreManager;
use Sutunam\LinkedProduct\ViewModel\ProductList;
use Sutunam\LinkedProduct\Model\Config as ConfigData;

class ListProduct
{
    /**
     * @var ProductList
     */
    protected $productList;

    /**
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * @var ConfigData
     */
    protected $configData;

    /**
     * @param ProductList $productList
     * @param StoreManager $storeManager
     * @param ConfigData $configData
     */
    public function __construct(
        ProductList $productList,
        StoreManager $storeManager,
        ConfigData $configData
    ) {
        $this->productList = $productList;
        $this->storeManager = $storeManager;
        $this->configData = $configData;
    }

    /**
     * After get loaded product collection
     *
     * @param \Magento\Catalog\Block\Product\ListProduct $subject
     * @param AbstractCollection $result
     * @return AbstractCollection
     */
    public function afterGetLoadedProductCollection(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        AbstractCollection $result
    ): AbstractCollection {
        if (!$this->configData->isEnable() || !$this->configData->isShowOnProductListing()) {
            return $result;
        }

        if ($this->configData->isShowAvailableProductsCount()) {
            $linkedItemsSize = $this->productList->getLinkedItemsSize($result->getItems());

            if (count($linkedItemsSize) === 0) {
                return $result;
            }

            foreach (array_values($result->getItems()) as $i => $product) {
                if (array_key_exists($product->getId(), $linkedItemsSize)) {
                    // +1 for parent product
                    $product->setData('available_products_count', $linkedItemsSize[$product->getId()] + 1);
                }
            }

            return $result;
        }

        $this->productList->addFilter('website_id', $this->storeManager->getStore()->getWebsiteId());
        $this->productList->addFilter('visibility', [
            Visibility::VISIBILITY_IN_CATALOG,
            Visibility::VISIBILITY_BOTH,
        ], 'in');

        $linkedCollection = $this->productList->getLinkedCollection($result->getItems());
        $linkedIds = $this->productList->getLinkedIds($result->getItems());

        $linkedProducts = [];

        foreach ($linkedIds as $item) {
            $productId = $item['product_id'];
            $linkedProductId = $item['linked_product_id'];
            $linkedProduct = $linkedCollection->getItemById($linkedProductId);
            if ($linkedProduct) {
                $linkedProducts[$productId][] = $linkedCollection->getItemById($linkedProductId);
            }
        }

        foreach ($result->getItems() as $product) {
            if (array_key_exists($product->getId(), $linkedProducts)) {
                $product->setData('linked_products', $linkedProducts[$product->getId()]);
            }
        }

        return $result;
    }
}
