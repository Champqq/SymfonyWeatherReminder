<?php

declare(strict_types=1);

namespace App\Controller\Subscription;

use App\DTO\SubscriptionRequest;
use App\Entity\Subscription;
use App\Service\Entity\EntityService;
use App\Service\Request\RequestHandler;
use App\Service\Subscription\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SubscriptionWebController extends AbstractController implements SubscriptionControllerInterface
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private EntityService $es,
        private RequestHandler $requestHandler,
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('/subscription/new', name: 'subscription_new')]
    public function create(Request $request, #[CurrentUser] ?UserInterface $user): Response
    {
        if ($request->isMethod('POST')) {
            $dto = $this->requestHandler->handle($request, SubscriptionRequest::class);

            $subscription = new Subscription();
            $subscription->setUser($user);

            $this->subscriptionService->createOrUpdate($subscription, $dto);

            $this->es->save($subscription);

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('subscription/new.html.twig');
    }

    /**
     * @throws \Exception
     */
    #[Route('/subscription/{id}/edit', name: 'edit_subscription_web')]
    public function update(int $id, Request $request): Response
    {
        $subscription = $this->subscriptionService->getSubscription($id);

        if ($request->isMethod('POST')) {
            $dto = $this->requestHandler->handle($request, SubscriptionRequest::class);

            $this->subscriptionService->createOrUpdate($subscription, $dto);

            $this->es->save($subscription);

            return $this->redirectToRoute('app_profile');
        }

        return $this->render(
            'subscription/edit.html.twig', [
                'subscription' => $subscription,
            ]
        );
    }

    #[Route('/subscription/{id}/delete', name: 'subscription_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $subscription = $this->subscriptionService->getSubscription($id);

        $this->es->delete($subscription);

        return $this->redirectToRoute('app_profile');
    }
}
