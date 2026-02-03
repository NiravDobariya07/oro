<?php

namespace Acme\Bundle\CustomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Default Controller for Acme Custom Bundle
 */
class DefaultController extends AbstractController
{
    #[Route('/hello', name: 'acme_custom_hello', methods: ['GET'])]
    public function helloAction(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Hello from Acme Custom Bundle!',
            'bundle' => 'AcmeCustomBundle',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    #[Route('/info', name: 'acme_custom_info', methods: ['GET'])]
    public function infoAction(): JsonResponse
    {
        return new JsonResponse([
            'bundle_name' => 'AcmeCustomBundle',
            'namespace' => 'Acme\Bundle\CustomBundle',
            'description' => 'Custom bundle for OroCommerce',
            'version' => '1.0.0'
        ]);
    }
}
