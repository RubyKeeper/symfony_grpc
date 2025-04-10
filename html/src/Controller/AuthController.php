<?php

namespace App\Controller;

use App\Service\VerificationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
    private $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * @Route("/api/request-code", methods={"POST"})
     */
    public function requestCode(Request $request)
    {
        $phone = $request->request->get('phone');
        return new JsonResponse($this->verificationService->requestVerificationCode($phone));
    }

    /**
     * @Route("/api/verify-code", methods={"POST"})
     */
    public function verifyCode(Request $request)
    {
        $phone = $request->request->get('phone');
        $code = $request->request->get('code');
        return new JsonResponse($this->verificationService->verifyCode($phone, $code));
    }
}