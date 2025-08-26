<?php
/**
 * @author    Sutunam
 * @copyright Copyright (c) 2024 Sutunam (http://www.sutunam.com/)
 */

declare(strict_types=1);

namespace Sutunam\LinkedProduct\Model\Resolver\Batch;

use Magento\RelatedProductGraphQl\Model\Resolver\Batch\AbstractLikedProducts;
use Sutunam\LinkedProduct\Model\Product\Link;

class LinkedProducts extends AbstractLikedProducts
{
    /**
     * @inheritdoc
     */
    protected function getNode(): string
    {
        return 'linked_products';
    }

    /**
     * @inheritdoc
     */
    protected function getLinkType(): int
    {
        return Link::LINK_TYPE_LINKED;
    }
}
