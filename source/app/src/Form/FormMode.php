<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

enum FormMode
{
    case Input;
    case Confirm;
    case Complete;
}
