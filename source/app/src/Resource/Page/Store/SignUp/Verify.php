<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Store\SignUp;

use AppCore\Application\Store\VerifyJoinCustomerInputData;
use AppCore\Application\Store\VerifyJoinCustomerUseCase;
use BEAR\Resource\Code;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use MyVendor\MyProject\Resource\Page\BaseStorePage;
use Ray\AuraSqlModule\Annotation\Transactional;

/** @SuppressWarnings(PHPMD.LongVariable) */
class Verify extends BaseStorePage
{
    public function __construct(private readonly VerifyJoinCustomerUseCase $verifyJoinCustomerUseCase)
    {
    }

    public function onGet(string $signature): static
    {
        $this->body['signature'] = $signature;

        return $this;
    }

    /** @Transactional() */
    public function onPost(string $signature): static
    {
        $this->verifyJoinCustomerUseCase->execute(new VerifyJoinCustomerInputData($signature));

        $this->renderer = new NullRenderer();
        $this->code = Code::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/sign-up/verified'];

        return $this;
    }
}
