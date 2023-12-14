<?php

declare(strict_types=1);

namespace AppCore\Exception;

use LogicException as PhpLogicException;

/**
 * 例外の発生がバグによるものでコードの修正が必要な場合の例外
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
final class LogicException extends PhpLogicException
{
}
