<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Input\Fieldset;

/** @psalm-suppress PropertyNotSetInConstructor */
class ExtendedFieldset extends Fieldset
{
    /** @var array<array-key, array<string>>|null */
    private array|null $errorMessages = null;

    /** @return array<string> */
    public function error(string $name): array
    {
        if ($this->errorMessages === null) {
            $this->errorMessages = $this->getMessages();
        }

        return $this->errorMessages[$name] ?? [];
    }
}
