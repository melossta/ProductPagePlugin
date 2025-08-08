<?php declare(strict_types=1);

namespace SwagExamplePlugin\Controller;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\System\StateMachine\Transition;
use SwagExamplePlugin\Service\ExampleServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Core\Framework\Context;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Shopware\Core\System\StateMachine\StateMachineRegistry;
use Shopware\Core\Checkout\Order\OrderDefinition; // for order state machine

#[Route(defaults: ['_routeScope' => ['storefront']])]
class ExampleController extends StorefrontController
{
    private $productRepository;
    private ?ExampleServiceInterface $exampleService = null;
    private StateMachineRegistry $stateMachineRegistry;
    private EntityRepository $exampleRepository;

    public function __construct(
        StateMachineRegistry $stateMachineRegistry,
        EntityRepository $productRepository,
        EntityRepository $exampleRepository,
        ExampleServiceInterface $exampleService
    ) {
        $this->stateMachineRegistry = $stateMachineRegistry;
        $this->productRepository = $productRepository;
        $this->exampleService = $exampleService;
        $this->exampleRepository = $exampleRepository;
    }
//    public function setDependencies(EntityRepository $productRepository, ExampleServiceInterface $exampleService): void
//    {
//        $this->productRepository = $productRepository;
//
//        $hour = (int) date('H');
//        $minute = (int) date('i');
//
//        // Inject exampleService only after 12:41
//        if ($hour > 8 || ($hour === 8 && $minute >= 57)) {
//            $this->exampleService = $exampleService;
//        } else {
//            $this->exampleService = null; // explicitly clear it before allowed time
//        }
//    }


    #[Route('/swag/example', name: 'frontend.swag.example', methods: ['GET'])]
    public function showPage(Request $request, Context $context): Response
    {
        if ($this->exampleService === null) {
            // Service is not available, show maintenance page
            return $this->render('@SwagExamplePlugin/storefront/page/maintenance.html.twig');
        }

        // Proceed as normal
        $criteria = new Criteria();
        $criteria->setLimit(3131);
        $criteria->addAssociation('prices');
        $criteria->addAssociation('cover.media');
        $criteria->addFilter(new NotFilter(NotFilter::CONNECTION_AND, [new EqualsFilter('name', '')]));
        $criteria->addAssociation('translations'); // make sure translations get loaded


// For demo, get first example entity or null
        $products = $this->productRepository->search($criteria, Context::createDefaultContext());
        $testOutput = $this->exampleService->filter('foo and bar are here');

        return $this->render('@SwagExamplePlugin/storefront/page/showProducts.html.twig', [
            'products' => $products,
            'filteredString' => $testOutput,
        ]);
    }
    #[Route('/swag/state-test', name: 'frontend.swag.state_test', methods: ['GET'])]
    public function testStateMachine(Request $request, Context $context): Response
    {
        $orderId = $request->query->get('orderId');

        if (!$orderId) {
            return new Response('Missing orderId in query string', 400);
        }

        try {
            $this->stateMachineRegistry->transition(
                new Transition(
                    OrderDefinition::ENTITY_NAME,
                    $orderId,
                    'complete', // state transition name
                    'stateId'  // technical name of the state field
                ),
                $context
            );
            return new Response('Order state changed to ""');
        } catch (\Throwable $e) {
            return new Response('Error: ' . $e->getMessage(), 500);
        }
    }

}
