<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

use DateTimeImmutable;

class Email
{
    private AddressInterface|null $from = null;

    /** @var array<AddressInterface> */
    private array $to = [];

    /** @var array<AddressInterface> */
    private array $replayTo = [];

    /** @var array<AddressInterface> */
    private array $cc = [];

    /** @var array<AddressInterface> */
    private array $bcc = [];
    private string|null $subject = null;
    private string|null $text = null;
    private string|null $html = null;
    private string|null $templateId = null;

    /** @var array<string, mixed> */
    private array $templateVars = [];
    private Format $emailFormat = Format::Both;
    private DateTimeImmutable|null $scheduleAt = null;

    public function getFrom(): AddressInterface|null
    {
        return $this->from;
    }

    /** @return array<AddressInterface> */
    public function getTo(): array
    {
        return $this->to;
    }

    /** @return array<AddressInterface> */
    public function getReplayTo(): array
    {
        return $this->replayTo;
    }

    /** @return array<AddressInterface> */
    public function getCc(): array
    {
        return $this->cc;
    }

    /** @return array<AddressInterface> */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function getSubject(): string|null
    {
        return $this->subject;
    }

    public function getText(): string|null
    {
        return $this->text;
    }

    public function getHtml(): string|null
    {
        return $this->html;
    }

    public function getTemplateId(): string|null
    {
        return $this->templateId;
    }

    /** @return array<string, mixed> */
    public function getTemplateVars(): array
    {
        return $this->templateVars;
    }

    public function getEmailFormat(): Format
    {
        return $this->emailFormat;
    }

    public function getScheduleAt(): DateTimeImmutable|null
    {
        return $this->scheduleAt;
    }

    public function setFrom(AddressInterface|null $from): self
    {
        $clone = clone $this;
        $clone->from = $from;

        return $clone;
    }

    /** @param array<AddressInterface> $to */
    public function setTo(array $to): self
    {
        $clone = clone $this;
        $clone->to = $to;

        return $clone;
    }

    /** @param array<AddressInterface> $replayTo */
    public function setReplayTo(array $replayTo): self
    {
        $clone = clone $this;
        $clone->replayTo = $replayTo;

        return $clone;
    }

    /** @param array<AddressInterface> $cc */
    public function setCc(array $cc): self
    {
        $clone = clone $this;
        $clone->cc = $cc;

        return $clone;
    }

    /** @param array<AddressInterface> $bcc */
    public function setBcc(array $bcc): self
    {
        $clone = clone $this;
        $clone->bcc = $bcc;

        return $clone;
    }

    public function setSubject(string|null $subject): self
    {
        $clone = clone $this;
        $clone->subject = $subject;

        return $clone;
    }

    public function setText(string|null $text): self
    {
        $clone = clone $this;
        $clone->text = $text;

        return $clone;
    }

    public function setHtml(string|null $html): self
    {
        $clone = clone $this;
        $clone->html = $html;

        return $clone;
    }

    public function setTemplateId(string|null $templateId): self
    {
        $clone = clone $this;
        $clone->templateId = $templateId;

        return $clone;
    }

    /** @param array<string, mixed> $templateVars */
    public function setTemplateVars(array $templateVars): self
    {
        $clone = clone $this;
        $clone->templateVars = $templateVars;

        return $clone;
    }

    public function setEmailFormat(Format $emailFormat): self
    {
        $clone = clone $this;
        $clone->emailFormat = $emailFormat;

        return $clone;
    }

    public function setScheduleAt(DateTimeImmutable|null $scheduleAt): self
    {
        $clone = clone $this;
        $clone->scheduleAt = $scheduleAt;

        return $clone;
    }
}
