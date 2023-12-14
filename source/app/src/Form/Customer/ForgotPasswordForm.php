<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

use MyVendor\MyProject\Form\ExtendedForm;
use Ray\WebFormModule\SetAntiCsrfTrait;

/** @psalm-suppress PropertyNotSetInConstructor */
class ForgotPasswordForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'forgotPassword';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('email', 'email')
            ->setAttribs([
                'autofocus' => '',
                'autocomplete' => 'email',
                'placeholder' => 'takeout@tk.com',
                'required' => 'required',
                'minlength' => 6,
                'maxlength' => 255,
                'title' => '有効なメールアドレスを入力してください',
            ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('email')
            ->is('email')
            ->is('strlenBetween', 6, 255);
        $this->filter->useFieldMessage('email', '有効なメールアドレスを入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
            ->setAttribs(['value' => '送信']);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('continue')
            ->isBlankOr('string')
            ->isBlankOr('strictEqualToValue', '送信');
        $this->filter->useFieldMessage(
            'continue',
            '送信ボタンをクリックしてください',
        );
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
