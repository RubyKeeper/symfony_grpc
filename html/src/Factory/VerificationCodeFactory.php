<?php

namespace App\Factory;

use App\Entity\VerificationCode;
use Random\RandomException;

class VerificationCodeFactory
{
    /**
     * @throws RandomException
     */
    public function createByArray(array $data): VerificationCode
    {
        $code = new VerificationCode($data['phone']);
        $code->setCode($data['code']);
        $code->setToken($data['token']);
        return $code;
    }

    /**
     * @throws RandomException
     */
    public function createByPhone(string $phone): VerificationCode
    {
        return new VerificationCode($phone);
    }
}