<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

use MyVendor\MyProject\Form\ExtendedForm;
use Ray\WebFormModule\SetAntiCsrfTrait;
use stdClass;

use function preg_match;

/** @psalm-suppress PropertyNotSetInConstructor */
class ResetPasswordForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'resetPassword';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('phoneNumber', 'tel')
            ->setAttribs([
                'autocomplete' => 'tel',
                'placeholder' => '',
                'required' => 'required',
                'minlength' => 10,
                'maxlength' => 11,
                'title' => '電話番号は10文字以上11文字以下の数字で入力してください',
            ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('phoneNumber')
            ->is('strlenBetween', 10, 11);
        $this->filter->useFieldMessage(
            'phoneNumber',
            '電話番号は10文字以上11文字以下の数字で入力してください',
        );

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
            ->setAttribs([
                'autocomplete' => 'new-password',
                'placeholder' => '',
                'required' => 'required',
                'minlength' => 8,
                'maxlength' => 20,
                'pattern' => '^[\x20-\x7E]+$', // ASCII 文字列
                'title' => '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください',
            ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('password')
            ->is('string')
            ->is('regex', '/^[A-Za-z0-9!@#$%^&*]+$/i') // ASCII 文字列
            ->is('strlenBetween', 8, 20)
            ->is('callback', static function (stdClass $subject, string $field) {
                $value = $subject->$field;
                $numberOfCharTypes = 0;

                if (preg_match('/[a-zA-Z]/', $value)) {
                    ++$numberOfCharTypes;
                }

                if (preg_match('/\d/', $value)) {
                    ++$numberOfCharTypes;
                }

                if (preg_match('/[!@#$%^&*]/', $value)) {
                    ++$numberOfCharTypes;
                }

                return $numberOfCharTypes === 3;
            });
        $this->filter->useFieldMessage(
            'password',
            '新しいパスワードは8文字以上20文字以下の英数字記号で入力してください',
        );

        /** @psalm-suppress UndefinedMethod */
        $this->setField('signature', 'hidden')
            ->setAttribs([
                'required' => 'required',
                'minlength' => 1,
            ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('signature')
            ->is('strlenMin', 1);

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
