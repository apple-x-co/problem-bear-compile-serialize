<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

enum StorePropName: string
{
    case PrimaryColor = 'PrimaryColor';
    case TermText = 'TermText';
    case LegalText = 'LegalText';
    case PrivacyText = 'PrivacyText';
    case ContactText = 'ContactText';
    case PointEnabled = 'PointEnabled';
    case PointRate = 'PointRate';
    case OrderNotificationRecipient = 'OrderNotificationRecipient';
}
