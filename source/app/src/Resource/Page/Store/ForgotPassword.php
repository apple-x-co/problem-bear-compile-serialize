<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store;

use AppCore\Application\Store\ForgotCustomerPasswordInputData;
use AppCore\Application\Store\ForgotCustomerPasswordUseCase;
use BEAR\Resource\Code;
use Koriym\HttpConstants\ResponseHeader;
use MyVendor\MyProject\Annotation\GoogleRecaptchaV2;
use MyVendor\MyProject\Captcha\RecaptchaException;
use MyVendor\MyProject\Form\Customer\ForgotPasswordInput;
use MyVendor\MyProject\Resource\Page\BaseStorePage;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

/** @SuppressWarnings(PHPMD.LongVariable) */
class ForgotPassword extends BaseStorePage
{
    public function __construct(
        protected readonly ForgotCustomerPasswordUseCase $forgotCustomerPasswordUseCase,
        #[Named('customer_forgot_password_form')]
        protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        return $this;
    }

    /** @FormValidation */
    #[GoogleRecaptchaV2]
    public function onPost(ForgotPasswordInput $forgotPassword): static
    {
        $this->forgotCustomerPasswordUseCase->execute(
            new ForgotCustomerPasswordInputData($forgotPassword->email),
        );

        $this->code = Code::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/forgot-password/complete'];

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
