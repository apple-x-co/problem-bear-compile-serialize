<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use Aura\Html\Helper\Input\AbstractInput;
use MyVendor\MyProject\Form\ExtendedFieldset;
use MyVendor\MyProject\Form\ExtendedForm;
use Qiq\Helper\Html\HtmlHelpers;

use function array_merge;
use function sprintf;

final class QiqSalonHelpers extends HtmlHelpers
{
    /** @param array<string, string> $attribs */
    public function textBox(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function checkBox(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function submit(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset|null $fieldset = null,
        array $attribs = [],
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        if (isset($attribs['value'])) {
            $spec['value'] = $attribs['value'];
            unset($attribs['value']);
        }

        $defaultAttribs = ['value' => 'Submit', 'class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }

    /** @param array<string, string> $attribs */
    public function errorMessage(ExtendedForm $form, string $input, string $tag = 'span', array $attribs = []): string
    {
        $message = $form->error($input);
        if ($message === '') {
            return '';
        }

        $defaultAttribs = ['class' => ''];

        return sprintf(
            '<%s %s>%s</%s>',
            $tag,
            $this->a(array_merge($defaultAttribs, $attribs)),
            $this->h($message),
            $tag,
        );
    }
}
