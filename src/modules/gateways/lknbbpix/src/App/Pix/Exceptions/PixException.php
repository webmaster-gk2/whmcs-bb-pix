<?php

namespace Lkn\BBPix\App\Pix\Exceptions;

use Exception;

final class PixException extends Exception
{
    public readonly PixExceptionCodes $exceptionCode;

    public function __construct(PixExceptionCodes $pixExceptionCode)
    {
        $this->exceptionCode = $pixExceptionCode;

        $msg = $pixExceptionCode->label();

        parent::__construct($msg, $pixExceptionCode->value, null);
    }
}
