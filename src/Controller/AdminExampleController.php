<?php declare(strict_types=1);

namespace SwagExamplePlugin\Controller;
use OpenApi\Annotations as OA;
use Shopware\Core\Framework\Context;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


/**
 * @OA\Get(
 *     path="/api/v1/action/swag/example",
 *     summary="My Example Test Endpoint",
 *     description="Returns simple test data",
 *     operationId="getSwagExample",
 *     tags={"Custom"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="foo", type="string", example="bar")
 *         )
 *     )
 * )
 */
#[Route(defaults: ['_routeScope' => ['api']])]
class AdminExampleController extends AbstractController
{
    #[Route(
        path: '/api/v{version}/action/swag/example',
        name: 'api.action.swag.example',
        methods: ['GET']
    )]
    public function example(Request $request, Context $context):JsonResponse
    {
        return new JsonResponse([
            'foo' => 'bar',
        ]);
    }
}
