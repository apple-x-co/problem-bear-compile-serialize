<?php

declare(strict_types=1);

namespace AppCore\Domain\ProductShopStock;

enum ProductShopStockStatus: string
{
    case OUT_OF_STOCK = 'out_of_stock';
}
