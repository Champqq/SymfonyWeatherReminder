<?php

declare(strict_types=1);

namespace App\Controller\Subscription;

use App\DTO\SubscriptionRequest;
use App\Entity\Subscription;
use App\Service\Entity\EntityService;
use App\Service\Request\RequestHandler;
use App\Service\Subscription\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SubscriptionApiController extends AbstractController implements SubscriptionControllerInterface
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private EntityService $es,
        private RequestHandler $requestHandler,
    ) {}

    private function getValidatedDto(Request $request): SubscriptionRequest
    {
        return $this->requestHandler->handle($request, SubscriptionRequest::class);
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/subscriptions', name: 'create_subscription', methods: ['POST'])]
    public function create(Request $request, #[CurrentUser] ?UserInterface $user): JsonResponse
    {
        $dto = $this->getValidatedDto($request);

        $subscription = new Subscription();
        $subscription->setUser($user);

        $this->subscriptionService->createOrUpdate($subscription, $dto);

        $this->es->save($subscription);

        return $this->json(['message' => 'Subscription created']);
    }

    #[Route('/api/subscriptions', name: 'list_subscriptions', methods: ['GET'])]
    public function list(#[CurrentUser] ?UserInterface $user): JsonResponse
    {
        $subscriptions = $this->subscriptionService->getSubscriptions($user);

        $data = array_map(
            function (Subscription $subscription) {
                return [
                    'id' => $subscription->getId(),
                    'city' => $subscription->getCity(),
                    'time' => $subscription->getTime()->format('H:i'),
                    'enabled' => $subscription->isEnabled(),
                ];
            }, $subscriptions
        );

        return $this->json($data);
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/subscriptions/{id}', name: 'update_subscription', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $dto = $this->getValidatedDto($request);

        $subscription = $this->subscriptionService->getSubscription($id);

        $this->subscriptionService->createOrUpdate($subscription, $dto);

        $this->es->save($subscription);

        return $this->json(['message' => 'Subscription updated']);
    }

    #[Route('/api/subscriptions/{id}', name: 'delete_subscription', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $subscription = $this->subscriptionService->getSubscription($id);

        $this->es->delete($subscription);

        return $this->json(['message' => 'Subscription deleted']);
    }
}
