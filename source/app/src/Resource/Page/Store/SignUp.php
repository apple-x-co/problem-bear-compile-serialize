<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store;

use BEAR\Resource\Code;
use Koriym\HttpConstants\ResponseHeader;
use MyVendor\MyProject\Form\Customer\SignUpInput;
use MyVendor\MyProject\Resource\Page\BaseStorePage;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

class SignUp extends BaseStorePage
{
    public function __construct(
        #[Named('customer_sign_up_form')]
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
    public function onPost(SignUpInput $signUp): static
    {
        if ($signUp->continue) {
            $this->code = Code::TEMPORARY_REDIRECT;
            $this->headers = [ResponseHeader::LOCATION => '/sign-up/confirm'];
        }

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
