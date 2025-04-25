<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\DTO\RegisterRequest;
use App\Factory\UserFactory;
use App\Service\Entity\EntityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private UserFactory $userFactory,
        private EntityService $es,
    ) {}

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function registerForm(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $dto = new RegisterRequest(
                $request->request->get('email'),
                $request->request->get('password')
            );

            $user = $this->userFactory->create($dto);
            $this->es->save($user);

            return $this->redirectToRoute('home');
        }

        return $this->render('register/index.html.twig');
    }
}
