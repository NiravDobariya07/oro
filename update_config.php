<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ErrorHandler\Debug;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env-app');

$kernel = new AppKernel($_SERVER['ORO_ENV'] ?? 'prod', (bool) ($_SERVER['ORO_DEBUG'] ?? false));
$request = Request::createFromGlobals();
$kernel->boot();

$container = $kernel->getContainer();
$configManager = $container->get('oro_entity_config.config_manager');

$className = 'Acme\Bundle\CustomerNotesBundle\Entity\CustomerNote';
$securityConfig = $configManager->getProvider('security')->getConfig($className);
$ownershipConfig = $configManager->getProvider('ownership')->getConfig($className);

echo "Current ownership type: " . $ownershipConfig->get('owner_type') . "\n";

$ownershipConfig->set('owner_type', 'USER');
$ownershipConfig->set('owner_field_name', 'owner');
$ownershipConfig->set('owner_column_name', 'owner_id');
$ownershipConfig->set('organization_field_name', 'organization');
$ownershipConfig->set('organization_column_name', 'organization_id');

$configManager->persist($ownershipConfig);
$configManager->flush();

echo "Updated ownership type: " . $ownershipConfig->get('owner_type') . "\n";

echo "Clearing cache...\n";
$configManager->clearCache();

echo "Done.\n";
