<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use Aura\Html\Helper\Input\AbstractInput;
use BEAR\Sunday\Extension\Router\RouterInterface;
use MyVendor\MyProject\Annotation\Qualifier\GoogleRecaptchaSiteKey;
use MyVendor\MyProject\Form\ExtendedForm;
use Qiq\Helper\Html\HtmlHelpers;

use function is_string;
use function sprintf;

/** @SuppressWarnings(PHPMD.LongVariable) */
class QiqCustomHelpers extends HtmlHelpers
{
    public function __construct(
        private readonly QiqAdminHelpers $adminHelpers,
        #[GoogleRecaptchaSiteKey]
        private readonly string $googleRecaptchaSiteKey,
        private readonly RouterInterface $router,
        private readonly QiqSalonHelpers $salonHelpers,
        private readonly QiqStoreHelpers $storeHelpers,
    ) {
        parent::__construct(null);
    }

    public function adminHelpers(): QiqAdminHelpers
    {
        return $this->adminHelpers;
    }

    public function storeHelpers(): QiqStoreHelpers
    {
        return $this->storeHelpers;
    }

    public function salonHelpers(): QiqSalonHelpers
    {
        return $this->salonHelpers;
    }

    public function googleRecaptchaV2(
        string $size = 'normal',
        string|null $checked = null,
        string|null $expired = null,
        string|null $error = null,
    ): string {
        $attribs = [
            'class' => 'g-recaptcha',
            'data-sitekey' => $this->googleRecaptchaSiteKey,
            'data-size' => $size,
        ];

        if (is_string($checked)) {
            $attribs['data-callback'] = $checked;
        }

        if (is_string($expired)) {
            $attribs['data-expired-callback'] = $expired;
        }

        if (is_string($error)) {
            $attribs['data-error-callback'] = $error;
        }

        return sprintf('<div %s></div>', $this->a($attribs));
    }

    public function csrfTokenField(ExtendedForm $form): AbstractInput
    {
        return $form->widget($form->get('__csrf_token'));
    }

    /** @param array<string, mixed> $data */
    public function route(string $name, array $data = []): string
    {
        $path = $this->router->generate($name, $data);

        return $path === false ? '' : $path;
    }
}
