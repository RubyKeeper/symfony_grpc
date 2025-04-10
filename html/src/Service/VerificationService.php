<?php

namespace App\Service;

use App\Entity\VerificationCode;
use App\Repository\UserRepository;
use App\Repository\VerificationCodeRepository;
use Random\RandomException;

class VerificationService
{
    private $userRepository;
    private $verificationCodeRepository;

    public function __construct(UserRepository $userRepository, VerificationCodeRepository $verificationCodeRepository)
    {
        $this->userRepository = $userRepository;
        $this->verificationCodeRepository = $verificationCodeRepository;
    }

    /**
     * @throws RandomException
     */
    public function requestVerificationCode(string $phone)
    {
        if ($v = $this->verificationCodeRepository->findByPhone($phone)) {
            $code = new VerificationCode($v['phone']);
            $code->setCode($v['code']);
            $code->setToken($v['token']);
            $this->verificationCodeRepository->updateCount($v['id']);
            return $code;
        }
        $verificationCode = new VerificationCode($phone);
        $this->verificationCodeRepository->save($verificationCode);
        return $verificationCode;
        // логика для генерации и отправки кода, проверки блокировки, сохранения кода
    }

    public function verifyCode(string $phone, string $code)
    {
        // логика для проверки кода, регистрации/авторизации пользователя
    }
}