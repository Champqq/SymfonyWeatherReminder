<?php

namespace App\Controller\Profile;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProfileController extends AbstractController
{
    private SubscriptionRepository $subscriptionRepository;
    private EntityManagerInterface $em;

    public function __construct(SubscriptionRepository $subscriptionRepository, EntityManagerInterface $em)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->em = $em;
    }

    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function webProfile(#[CurrentUser] ?UserInterface $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($request->isMethod('POST')) {
            $user->setDefaultCity($request->request->get('default_city'));
            $user->setPhoneNumber($request->request->get('phone_number'));
            $this->em->persist($user);
            $this->em->flush();

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
