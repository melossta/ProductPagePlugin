<?php declare(strict_types=1);

namespace SwagExamplePlugin\Controller;

use Shopware\Core\Framework\Context;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class SecondExampleController extends AbstractController
{
    #[Route(
        path: '/example',
        name: 'frontend.swag.json',
        defaults: ["XmlHttpRequest" => true],
        methods: ['GET']
    )]
    public function getData(Request $request, Context $context):JsonResponse
    {
        return new JsonResponse([
            'message' => 'Hello from JSON controller!',
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);
    }
}
