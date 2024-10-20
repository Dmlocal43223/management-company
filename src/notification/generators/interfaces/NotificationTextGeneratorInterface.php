<?php

declare(strict_types=1);

namespace src\notification\generators\interfaces;

interface NotificationTextGeneratorInterface
{
    public function generateTitle(): string;
    public function generateBody(): string;
}