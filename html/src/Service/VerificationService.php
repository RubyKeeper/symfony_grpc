<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\VerificationCodeRepository;

class VerificationService
{
    private $userRepository;
    private $verificationCodeRepository;

    public function __construct(UserRepository $userRepository, VerificationCodeRepository $verificationCodeRepository)
    {
        $this->userRepository = $userRepository;
        $this->verificationCodeRepository = $verificationCodeRepository;
    }

    public function requestVerificationCode(string $phone)
    {
        // логика для генерации и отправки кода, проверки блокировки, сохранения кода
    }

    public function verifyCode(string $phone, string $code)
    {
        // логика для проверки кода, регистрации/авторизации пользователя
    }
}