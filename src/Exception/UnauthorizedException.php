<?php

declare(strict_types=1);

namespace Qbhy\HyperfAuth\Exception;

use Qbhy\HyperfAuth\AuthGuard;
use Throwable;

class UnauthorizedException extends AuthException
{
    protected ?AuthGuard $guard;

    protected int $statusCode = 401;

    public function __construct(string $message, ?AuthGuard $guard = null, ?Throwable $previous = null)
    {
        parent::__construct($message, 401, $previous);
        $this->guard = $guard;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
