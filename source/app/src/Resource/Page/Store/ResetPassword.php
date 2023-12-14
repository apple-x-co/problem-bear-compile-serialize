<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store;

use AppCore\Application\Store\ResetCustomerPasswordInputData;
use AppCore\Application\Store\ResetCustomerPasswordUseCase;
use BEAR\Resource\Code;
use Koriym\HttpConstants\ResponseHeader;
use MyVendor\MyProject\Annotation\GoogleRecaptchaV2;
use MyVendor\MyProject\Captcha\RecaptchaException;
use MyVendor\MyProject\Form\Customer\ResetPasswordInput;
use MyVendor\MyProject\Form\ExtendedForm;
use MyVendor\MyProject\Resource\Page\BaseStorePage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

use function assert;

/** @SuppressWarnings(PHPMD.LongVariable) */
class ResetPassword extends BaseStorePage
{
    public function __construct(
        #[Named('customer_reset_password_form')]
        protected readonly FormInterface $form,
        protected readonly ResetCustomerPasswordUseCase $resetCustomerPasswordUseCase,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(string $signature): static
    {
        assert($this->form instanceof ExtendedForm);
        $this->form->fill(['signature' => $signature]);

        return $this;
    }

    /**
     * @FormValidation
     * @Transactional()
     */
    #[GoogleRecaptchaV2]
    public function onPost(ResetPasswordInput $resetPassword): static
    {
        $this->resetCustomerPasswordUseCase->execute(
            new ResetCustomerPasswordInputData(
                $resetPassword->phoneNumber,
                $resetPassword->password,
                $resetPassword->signature,
            ),
        );

        $this->code = Code::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/reset-password/complete'];

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }

    /** @param array<RecaptchaException> $recaptchaExceptions */
    public function onPostGoogleRecaptchaV2Failed(array $recaptchaExceptions): static
    {
        $this->body['recaptchaError'] = ! empty($recaptchaExceptions);

        return $this;
    }
}
