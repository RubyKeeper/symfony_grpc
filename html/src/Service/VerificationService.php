<?php

namespace App\Service;

use App\Entity\BlockPhone;
use App\Entity\User;
use App\Entity\VerificationCode;
use App\Repository\BlockPhoneRepository;
use App\Repository\UserRepository;
use App\Repository\VerificationCodeRepository;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VerificationService
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly UserRepository $userRepository,
        private readonly VerificationCodeRepository $verificationCodeRepository,
        private readonly BlockPhoneRepository $blockPhoneRepository,
    ) {}

    /**
     * @throws RandomException
     */
    public function requestVerificationCode(string $phone): VerificationCode
    {
        $this->isBlocked($phone);

        if (3 < $this->verificationCodeRepository->getCount($phone)) {
            $this->blockPhoneRepository->insert(new BlockPhone($phone));
        }

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
    }

    private function isBlocked(string $phone): void
    {
        if ($block = $this->blockPhoneRepository->isBlocked($phone)) {
            $data = $block->getBlockAt()->format('Y-m-d H:i:s');
            throw new \Exception(
                "Вы заблокированы до {$data}",
                Response::HTTP_LOCKED,
            );
        }
    }

    public function verifyCode(?string $token, ?string $code): array
    {
        $constraints = new Constraints\Collection([
            'code'  => [
                new Constraints\NotBlank(message: 'Код не должен быть пустым'),
                new Constraints\Length(
                    4,
                    exactMessage: 'Код должен быть 4 значным',
                ),
            ],
            'token' => [
                new Constraints\NotBlank(message: 'Вы не передали токен'),
            ],
        ]);

        $errors = $this->validator->validate(
            ['code' => $code, 'token' => $token],
            $constraints,
        );

        if (count($errors) > 0) {
            throw new \Exception(
                $errors[0]->getMessage(),
                Response::HTTP_BAD_REQUEST,
            );
        }

        if ($v = $this->verificationCodeRepository->findByTokenAndCode(
            $token,
            $code,
        )
        ) {
            if ($user = $this->userRepository->findByPhone($v['phone'])) {
                $id = $user->getId();
                $return = [
                    'message' => 'Вы успешно авторизовались',
                    'id'      => $id,
                ];
            } else {
                $user = new User($v['phone']);
                $id = $this->userRepository->insert($user)->getId();
                $return = [
                    'message' => 'Вы успешно зарегистрировались',
                    'id'      => $id,
                ];
            }
//            $this->verificationCodeRepository->delete($v['id']);

        } else {
            throw new \Exception(
                'Введенный код неверен',
                Response::HTTP_UNAUTHORIZED,
            );
        }

        return $return;
    }
}