<?php

namespace App\Controller\Auth;

use App\DTO\RegisterRequest;
use App\Factory\UserFactory;
use App\Service\Request\RequestHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private UserFactory $userFactory,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, RequestHandler $requestHandler): JsonResponse
    {
        $dto = $requestHandler->handle($request, RegisterRequest::class);

        $user = $this->userFactory->create($dto);

        $this->em->persist($user);
        $this->em->flush();

        return $this->json(['message' => 'User registered successfully']);
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function registerForm(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $dto = new RegisterRequest(
                $request->request->get('email'),
                $request->request->get('password')
            );

            $user = $this->userFactory->create($dto);
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('register/index.html.twig');
    }
}
