<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Salon;

use MyVendor\MyProject\Form\ExtendedForm;
use Ray\WebFormModule\SetAntiCsrfTrait;

/** @psalm-suppress PropertyNotSetInConstructor */
class LoginForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'login';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('username', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'username',
                 'placeholder' => 'email',
                 'required' => 'required',
                 'title' => '有効なEメールアドレスを入力してください',
             ]);
        $this->filter->validate('username')->is('email');
        $this->filter->useFieldMessage('username', '有効なEメールアドレスを入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'current-password',
                 'placeholder' => 'password',
                 'required' => 'required',
                 'title' => '有効なパスワードを入力してください',
             ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('password')
            ->is('string')
            ->is('regex', '/^[A-Za-z0-9!@#$%^&*]+$/i');
        $this->filter->useFieldMessage('password', '有効なパスワードを入力してください');

        $this->setField('remember', 'checkbox')
             ->setAttribs([
                 'value' => 'yes',
                 'label' => 'YES',
                 'value_unchecked' => 'no',
             ]);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('login', 'submit');
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
