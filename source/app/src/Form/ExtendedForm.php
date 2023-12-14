<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Html\Helper\Input\AbstractInput;
use Aura\Input\Collection;
use Aura\Input\Fieldset;
use Ray\WebFormModule\AbstractForm;
use Ray\WebFormModule\AntiCsrf;
use Ray\WebFormModule\SubmitInterface;

use function array_merge;
use function array_merge_recursive;
use function assert;
use function is_array;

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
abstract class ExtendedForm extends AbstractForm implements SubmitInterface
{
    abstract protected function getFormName(): string;

    /** @return array<string, mixed> */
    public function getData(): array
    {
        return $this->getValue();
    }

    /**
     * @param array<string, mixed> $spec
     * @param array<string, mixed> $attribs
     */
    public function widget(array $spec, array $attribs = []): AbstractInput
    {
        $spec['attribs'] = array_merge($spec['attribs'] ?? [], $attribs);

        if ($spec['type'] === 'file' && is_array($spec['value']) && isset($spec['value']['tmp_name'])) {
            $spec['value'] = null;
        }

        /** @phpstan-ignore-next-line */
        return $this->helper->input($spec);
    }

    /** @param array<array-key, mixed> $data */
    public function apply(array $data): bool
    {
        $isValid = parent::apply($data);

        foreach ($this->inputs as $input) {
            if ($input instanceof Fieldset) {
                $input->filter();
                continue;
            }

            if (! ($input instanceof Collection)) {
                continue;
            }

            $fieldsetName = $input->name;
            foreach ($this->$fieldsetName as $fieldset) {
                assert($fieldset instanceof Fieldset);
                $fieldset->filter();
            }
        }

        return $isValid;
    }

    /**
     * @return array<string, mixed>
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function submit(): array
    {
        $formName = $this->getFormName();

        /** @var array<string, mixed> $posts */
        $posts = $_POST[$formName] ?? [];

        if (isset($_POST[AntiCsrf::TOKEN_KEY])) {
            /** @psalm-suppress InvalidArrayOffset */
            $posts[AntiCsrf::TOKEN_KEY] = $_POST[AntiCsrf::TOKEN_KEY];
        }

        if (isset($_FILES[$formName])) {
            $files = (new NormalizeFiles())($_FILES);
            if (! empty($files)) {
                $posts = array_merge_recursive($posts, $files[$formName]);
            }
        }

        return $posts;
    }
}
