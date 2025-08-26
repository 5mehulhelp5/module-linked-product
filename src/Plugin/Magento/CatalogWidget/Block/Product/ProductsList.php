<?php
/**
 * @author    Sutunam
 * @copyright Copyright (c) 2024 Sutunam (http://www.sutunam.com/)
 */

declare(strict_types=1);

namespace Sutunam\LinkedProduct\Plugin\Magento\CatalogWidget\Block\Product;

use Magento\Store\Model\StoreManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Sutunam\LinkedProduct\ViewModel\ProductList;
use Magento\CatalogWidget\Block\Product\ProductsList as BaseProductsList;
use Sutunam\LinkedProduct\Model\Config as ConfigData;

class ProductsList
{
    /**
     * @var ProductList
     */
    protected $productList;

    /**
     * @var StoreManagerInterface
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
     * After create collection
     *
     * @param BaseProductsList $subject
     * @param Collection $result
     * @return Collection
     */
    public function afterCreateCollection(
        BaseProductsList $subject,
        Collection $result
    ): Collection {
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

        foreach (array_values($result->getItems()) as $i => $product) {
            if (array_key_exists($product->getId(), $linkedProducts)) {
                $product->setData('linked_products', $linkedProducts[$product->getId()]);
            }
        }

        return $result;
    }
}
