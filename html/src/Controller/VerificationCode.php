<?php

namespace App\Controller;

use App\Service\VerificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VerificationCode extends AbstractController
{
    public function __construct(
        private readonly VerificationService $verificationService,
    ) {}

    /**
     * Генерация кода
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Random\RandomException
     */
    #[Route('/api/request-code', methods: ['POST'])]
    public function requestCode(Request $request): JsonResponse
    {
        $phone = $request->request->get('phone');
        try {
            $verificationCode
                = $this->verificationService->requestVerificationCode(
                $phone,
            );
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()],
                $exception->getCode());
        }

        return new JsonResponse(
            [
                'phone' => $verificationCode->getPhone(),
                'code'  => $verificationCode->getCode(),
                'token' => $verificationCode->getToken(),
            ],
        );
    }

    /**
     * Верификация кода
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/api/verify-code', methods: ['POST'])]
    public function verifyCode(Request $request): JsonResponse
    {
        $token = $request->request->get('token');
        $code = $request->request->get('code');

        try {
            $verificationCode = $this->verificationService->verifyCode(
                $token,
                $code,
            );
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()],
                $exception->getCode());
        }

        return new JsonResponse($verificationCode);
    }
}