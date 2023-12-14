<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

enum RecipientType: string
{
    case To = 'to';
    case Cc = 'cc';
    case Bcc = 'bcc';
    case ReplayTo = 'replay-to';
}
