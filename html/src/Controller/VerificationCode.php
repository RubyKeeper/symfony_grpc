<?php

namespace App\Controller;

use App\Service\VerificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VerificationCode extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        private VerificationService $verificationService
    )
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/request-code', name: 'blog_list')]
    public function requestCode(Request $request): JsonResponse
    {
        $phone = $request->request->get('phone');
        $phone = '+79657584444';
        $verificationCode = $this->verificationService->requestVerificationCode($phone);

        // Логика проверки и генерации кода
        // Проверка на количество запросов и генерация кода...

        return new JsonResponse(['phone' => $verificationCode->getPhone(), 'code' => $verificationCode->getCode(), 'token' => $verificationCode->getToken()]);
    }

    /**
     * @Route("/api/verify-code", methods={"POST"})
     */
    public function verifyCode(Request $request): JsonResponse
    {
        $phone = $request->request->get('phone');
        $code = $request->request->get('code');

        // Логика проверки кода
        // Если код верный, проверяем, есть ли пользователь с таким номером телефона...

        return new JsonResponse(['message' => 'k']);
    }
}