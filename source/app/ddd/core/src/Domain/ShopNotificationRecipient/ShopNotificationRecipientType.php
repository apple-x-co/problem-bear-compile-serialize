<?php

declare(strict_types=1);

namespace AppCore\Domain\ShopNotificationRecipient;

enum ShopNotificationRecipientType: string
{
    case ORDER = 'order';
}
