<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store\SignUp;

use AppCore\Application\Store\JoinCustomerInputData;
use AppCore\Application\Store\JoinCustomerUseCase;
use AppCore\Domain\GenderType;
use BEAR\Resource\Code;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use MyVendor\MyProject\Form\Customer\SignUpInput;
use MyVendor\MyProject\Resource\Page\BaseStorePage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

class Confirm extends BaseStorePage
{
    public function __construct(
        #[Named('customer_sign_up_form')]
        protected readonly FormInterface $form,
        protected readonly JoinCustomerUseCase $joinCustomerUseCase,
    ) {
        $this->body['form'] = $this->form;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @FormValidation()
     * @Transactional()
     */
    public function onPost(SignUpInput $signUp): static
    {
        if ($signUp->complete) {
            $this->joinCustomerUseCase->execute(
                new JoinCustomerInputData(
                    $signUp->genderType ?? GenderType::OTHER->value,
                    $signUp->familyName,
                    $signUp->givenName,
                    $signUp->phoneticFamilyName,
                    $signUp->phoneticGivenName,
                    $signUp->phoneNumber,
                    $signUp->email,
                    $signUp->password,
                ),
            );

            $this->renderer = new NullRenderer();
            $this->code = Code::SEE_OTHER;
            $this->headers = [ResponseHeader::LOCATION => '/sign-up/unverified'];

            return $this;
        }

        $this->body['signUp'] = $signUp;

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
