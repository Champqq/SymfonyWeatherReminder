<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Repository\SubscriptionRepository;
use App\Service\Entity\EntityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProfileController extends AbstractController
{
    public function __construct(
        private SubscriptionRepository $subscriptionRepository,
        private EntityService $es,
    ) {
    }

    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function webProfile(#[CurrentUser] ?UserInterface $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($request->isMethod('POST')) {
            $user->setDefaultCity($request->request->get('default_city'));
            $user->setPhoneNumber($request->request->get('phone_number'));
            $this->es->save($user);

            return $this->redirectToRoute('app_profile');
        }

        $subscriptions = $this->subscriptionRepository->findBy(['user' => $user]);

        return $this->render(
            'profile/index.html.twig', [
                'user' => $user,
                'subscriptions' => $subscriptions,
            ]
        );
    }

    #[Route('/api/profile', name: 'api_profile', methods: ['GET'])]
    public function profile(#[CurrentUser] ?UserInterface $user): JsonResponse
    {
        return $this->json(
            [
                'email' => $user->getUserIdentifier(),
                'roles' => $user->getRoles(),
            ]
        );
    }
}
