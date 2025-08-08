<?php declare(strict_types=1);
namespace SwagExamplePlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExampleCompilerPass implements CompilerPassInterface
{
public function process(ContainerBuilder $container)
{
if (!$container->has('SwagExamplePlugin\Service\ExampleService')) {
return;
}

$definition = $container->findDefinition('SwagExamplePlugin\Service\ExampleService');

// Add a fake tag to test it's working
$definition->addTag('swag.example_service_tag');

// You can also replace arguments, or log
// $definition->replaceArgument(0, new Reference('some_other_service'));
}
}