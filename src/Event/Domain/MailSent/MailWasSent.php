<?php

declare(strict_types=1);

namespace EventHubCraft\Event\Domain\MailSent;

use EventHubCraft\Event\Domain\Bus\Event\Event;
use EventHubCraft\Model\Domain\ValueObject\MailSent\MailSentType;
use EventHubCraft\Model\Domain\ValueObject\UserId;

class MailWasSent extends Event
{
    public function __construct(
        private MailSentType $type,
        private UserId $idUserReceive,
        private ?UserId $idUserSent = null,
        private ?string $objectType = null,
        private ?int $objectId = null,
        private ?string $subject = null,
        private ?string $content = null,
    ) {
        parent::__construct();
    }

    public function type(): MailSentType
    {
        return $this->type;
    }

    public function idUserReceive(): UserId
    {
        return $this->idUserReceive;
    }

    public function idUserSent(): ?UserId
    {
        return $this->idUserSent;
    }

    public function objectType(): ?string
    {
        return $this->objectType;
    }

    public function objectId(): ?int
    {
        return $this->objectId;
    }

    public function subject(): ?string
    {
        return $this->subject;
    }

    public function content(): ?string
    {
        return $this->content;
    }
}
