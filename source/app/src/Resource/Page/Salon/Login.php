<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Salon;

use MyVendor\MyProject\Annotation\GoogleRecaptchaV2;
use MyVendor\MyProject\Annotation\SalonLogin;
use MyVendor\MyProject\Auth\AuthenticationException;
use MyVendor\MyProject\Captcha\RecaptchaException;
use MyVendor\MyProject\Form\Salon\LoginInput;
use MyVendor\MyProject\Resource\Page\BaseSalonPage;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

class Login extends BaseSalonPage
{
    public function __construct(
        #[Named('salon_login_form')]
        protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @FormValidation()
     */
    #[GoogleRecaptchaV2]
    #[SalonLogin]
    public function onPost(LoginInput $login): static
    {
        // login success !!

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function onPostAuthenticationFailed(AuthenticationException $authException): static
    {
        $this->body['authError'] = true;

        return $this;
    }

    /** @param array<RecaptchaException> $recaptchaExceptions */
    public function onPostGoogleRecaptchaV2Failed(array $recaptchaExceptions): static
    {
        $this->body['recaptchaError'] = ! empty($recaptchaExceptions);

        return $this;
    }
}
