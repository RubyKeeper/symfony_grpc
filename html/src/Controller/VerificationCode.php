<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PhoneVerification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VerificationCode extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/request-code', name: 'blog_list')]
    public function requestCode(Request $request): JsonResponse
    {
        $phone = $request->request->get('phone');

        // Логика проверки и генерации кода
        // Проверка на количество запросов и генерация кода...

        return new JsonResponse(['code' => 'k']);
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