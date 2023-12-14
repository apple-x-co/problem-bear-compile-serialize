<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

use AppCore\Domain\GenderType;
use AppCore\Infrastructure\Query\CustomerQueryInterface;
use MyVendor\MyProject\Form\ExtendedForm;
use Ray\Di\Di\Inject;
use Ray\WebFormModule\SetAntiCsrfTrait;
use stdClass;

use function preg_match;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class SignUpForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'signUp';

    private CustomerQueryInterface $customerQuery;

    #[Inject]
    public function setCustomerQuery(CustomerQueryInterface $customerQuery): void
    {
        $this->customerQuery = $customerQuery;
    }

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        $genders = [
            GenderType::MALE->value => '男性',
            GenderType::FEMALE->value => '女性',
            GenderType::OTHER->value => 'その他',
        ];

        /** @psalm-suppress UndefinedMethod */
        $this->setField('genderType', 'radio')
            ->setAttribs([
                'autofocus' => '',
                'required' => 'required',
            ])
            ->setOptions($genders);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('genderType')
            ->is('string')
            ->is('inKeys', $genders);
        $this->filter->useFieldMessage('genderType', '性別を選択してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('familyName', 'text')
            ->setAttribs([
                'autocomplete' => 'family-name',
                'placeholder' => '山田',
                'required' => 'required',
                'minlength' => 1,
                'maxlength' => 50,
                'title' => '名前(氏名)を文字で入力してください',
            ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('familyName')
            ->is('string')
            ->is('strlenBetween', 1, 50);
        $this->filter->useFieldMessage('familyName', '名前(氏名)を文字で入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('givenName', 'text')
            ->setAttribs([
                'autocomplete' => 'given-name',
                'placeholder' => '花子',
                'required' => 'required',
                'minlength' => 1,
                'maxlength' => 50,
                'title' => '名前(氏名)を文字で入力してください',
            ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('givenName')
            ->is('string')
            ->is('strlenBetween', 1, 50);
        $this->filter->useFieldMessage('givenName', '名前(氏名)を文字で入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('phoneticFamilyName', 'text')
            ->setAttribs([
                'placeholder' => 'ヤマダ',
                'required' => 'required',
                'minlength' => 1,
                'maxlength' => 50,
                'pattern' => '^[ァ-ヴー]+$',
                'title' => '名前(カナ)をカタカナで入力してください',
            ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('phoneticFamilyName')
            ->is('string')
            ->is('strlenBetween', 1, 50)
            ->is('regex', '/^[ァ-ヴー]+$/u');
        $this->filter->useFieldMessage('phoneticFamilyName', '名前(カナ)をカタカナで入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('phoneticGivenName', 'text')
            ->setAttribs([
                'placeholder' => 'ハナコ',
                'required' => 'required',
                'minlength' => 1,
                'maxlength' => 50,
                'pattern' => '^[ァ-ヴー]+$',
                'title' => '名前(カナ)をカタカナで入力してください',
            ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('phoneticGivenName')
            ->is('string')
            ->is('strlenBetween', 1, 50)
            ->is('regex', '/^[ァ-ヴー]+$/u');
        $this->filter->useFieldMessage('phoneticGivenName', '名前(カナ)をカタカナで入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('phoneNumber', 'tel')
            ->setAttribs([
                'autocomplete' => 'tel',
                'placeholder' => '09012345678',
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
            ->is('strlenBetween', 6, 255)
            ->is('callback', function (stdClass $subject, string $field) {
                $result = $this->customerQuery->countByVerifiedEmail($subject->$field);

                return $result['count'] === 0;
            });
        $this->filter->useFieldMessage('email', '有効なメールアドレスを入力してください');

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
            ->isNot('equalToField', 'username')
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

        $this->setField('agree', 'checkbox')
            ->setAttribs([
                'required' => 'required',
                'value' => 'yes',
                'label' => 'YES',
                'value_unchecked' => 'no',
            ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('agree')
            ->is('string')
            ->is('strictEqualToValue', 'yes');
        $this->filter->useFieldMessage(
            'agree',
            '同意にチェックをしてください',
        );

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
            ->setAttribs(['value' => '確認画面へ']);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('continue')
            ->isBlankOr('string')
            ->isBlankOr('strictEqualToValue', '確認画面へ');
        $this->filter->useFieldMessage(
            'continue',
            '確認ボタンをクリックしてください',
        );

        /** @psalm-suppress UndefinedMethod */
        $this->setField('back', 'submit')
            ->setAttribs(['value' => '前の画面に戻る']);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('back')
            ->isBlankOr('string')
            ->isBlankOr('strictEqualToValue', '前の画面に戻る');
        $this->filter->useFieldMessage(
            'back',
            '前の画面に戻るをクリックしてください',
        );

        /** @psalm-suppress UndefinedMethod */
        $this->setField('complete', 'submit')
            ->setAttribs(['value' => '登録する']);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('complete')
            ->isBlankOr('string')
            ->isBlankOr('strictEqualToValue', '登録する');
        $this->filter->useFieldMessage(
            'complete',
            '登録するボタンをクリックしてください',
        );
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
