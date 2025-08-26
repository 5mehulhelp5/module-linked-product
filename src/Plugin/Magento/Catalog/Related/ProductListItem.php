<?php
/**
 * @author    Sutunam
 * @copyright Copyright (c) 2024 Sutunam (http://www.sutunam.com/)
 */

declare(strict_types=1);

namespace Sutunam\LinkedProduct\Plugin\Magento\Catalog\Related;

use Magento\Store\Model\StoreManager;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\Exception\NoSuchEntityException;
use Sutunam\LinkedProduct\Model\Config as ConfigData;
use Sutunam\LinkedProduct\ViewModel\ProductList;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

class ProductListItem
{
    /**
     * @var ProductList
     */
    protected ProductList $productList;

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
     * After get items
     *
     * @param \Magento\Catalog\Block\Product\ProductList\Related $subject
     * @param Collection $result
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function afterGetItems(
        \Magento\Catalog\Block\Product\ProductList\Related $subject,
        $result
    ): object {
        if (!is_array($result->getItems()) ||
            empty($result->getItems()) ||
            !array_values($result->getItems())[0] instanceof \Magento\Catalog\Model\Product ||
            !$this->configData->isEnable() ||
            !$this->configData->isShowOnProductListing()
        ) {
            return $result;
        }
        $items = $result->getItems();
        if ($this->configData->isShowAvailableProductsCount()) {
            $linkedItemsSize = $this->productList->getLinkedItemsSize($items);

            if (count($linkedItemsSize) === 0) {
                return $result;
            }

            foreach (array_values($items) as $i => $product) {
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

        $linkedCollection = $this->productList->getLinkedCollection($items);
        $linkedIds = $this->productList->getLinkedIds($items);

        $linkedProducts = [];

        foreach ($linkedIds as $item) {
            $linkedProduct = $linkedCollection->getItemById($item['linked_product_id']);
            if ($linkedProduct) {
                $linkedProducts[$item['product_id']][] =
                    $linkedCollection->getItemById($item['linked_product_id']);
            }
        }

        foreach ($items as $item) {
            if (array_key_exists($item->getId(), $linkedProducts)) {
                $item->setData('linked_products', $linkedProducts[$item->getId()]);
            }
        }

        return $result;
    }
}
