<?php

declare(strict_types=1);

namespace AppCore\Exception;

use RuntimeException as PhpRuntimeException;

/**
 * 入力のミスで起こった例外は、コード自身には問題がない。
 * このような実行時になって判明する例外
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class RuntimeException extends PhpRuntimeException
{
}
