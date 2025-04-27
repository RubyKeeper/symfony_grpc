<?php

namespace App\Factory;

use App\Entity\VerificationCode;
use Random\RandomException;

class VerificationCodeFactory
{
    /**
     * @throws RandomException
     */
    public static function fromArray(array $data): VerificationCode
    {
        $code = new VerificationCode($data['phone']);
        $code->setCode($data['code']);
        $code->setToken($data['token']);
        return $code;
    }

    /**
     * @throws RandomException
     */
    public static function fromPhone(string $phone): VerificationCode
    {
        return new VerificationCode($phone);
    }
}