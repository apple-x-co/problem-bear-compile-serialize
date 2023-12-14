<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Salon;

use MyVendor\MyProject\Annotation\SalonGuard;
use MyVendor\MyProject\Annotation\SalonVerifyPassword;
use MyVendor\MyProject\Auth\AuthenticationException;
use MyVendor\MyProject\Form\Salon\PasswordInput;
use MyVendor\MyProject\Resource\Page\BaseSalonPage;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

class PasswordConfirm extends BaseSalonPage
{
    public function __construct(
        #[Named('salon_password_form')]
        protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    #[SalonGuard]
    public function onGet(): static
    {
        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @FormValidation()
     */
    #[SalonGuard]
    #[SalonVerifyPassword]
    public function onPost(PasswordInput $password): static
    {
        // password confirm success !!

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
}
