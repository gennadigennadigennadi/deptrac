<?php

declare(strict_types=1);

namespace Qossmic\Deptrac\Supportive\DependencyInjection;

use LogicException;
use Qossmic\Deptrac\Contract\Config\EmitterType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

use function getcwd;

/**
 * @psalm-suppress UndefinedDocblockClass
 * @psalm-suppress MixedArgument
 */
class DeptracExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $configs);

        $container->setParameter('paths', $configs['paths']);
        $container->setParameter('exclude_files', $configs['exclude_files']);
        $container->setParameter('layers', $configs['layers']);
        $container->setParameter('ruleset', $configs['ruleset']);
        $container->setParameter('skip_violations', $configs['skip_violations']);
        $container->setParameter('formatters', $configs['formatters'] ?? []);
        $container->setParameter('analyser', $configs['analyser']);
        $container->setParameter('ignore_uncovered_internal_classes', $configs['ignore_uncovered_internal_classes']);

        if (!$container->hasParameter('cache_file') && isset($configs['cache_file'])) {
            $container->setParameter('cache_file', $configs['cache_file']);
        }
    }

    /**
     * @throws ParameterNotFoundException
     * @throws LogicException
     */
    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('projectDirectory')) {
            $container->setParameter('projectDirectory', getcwd());
        }
        if (!$container->hasParameter('currentWorkingDirectory')) {
            $container->setParameter('currentWorkingDirectory', getcwd());
        }
        if (!$container->hasParameter('paths')) {
            $container->setParameter('paths', []);
        }
        if (!$container->hasParameter('exclude_files')) {
            $container->setParameter('exclude_files', []);
        }
        if (!$container->hasParameter('layers')) {
            $container->setParameter('layers', []);
        }
        if (!$container->hasParameter('ruleset')) {
            $container->setParameter('ruleset', []);
        }
        if (!$container->hasParameter('skip_violations')) {
            $container->setParameter('skip_violations', []);
        }
        if (!$container->hasParameter('formatters')) {
            $container->setParameter('formatters', []);
        }
        if (!$container->hasParameter('analyser')) {
            $container->setParameter('analyser', ['types' => [EmitterType::CLASS_TOKEN->value, EmitterType::FUNCTION_TOKEN->value]]);
        }
        if (!$container->hasParameter('ignore_uncovered_internal_classes')) {
            $container->setParameter('ignore_uncovered_internal_classes', true);
        }

        $projectDirectory = $container->getParameter('currentWorkingDirectory');

        if (!is_string($projectDirectory)) {
            throw new LogicException('"projectDirectory" has to be a string!');
        }

        $container->setParameter('cache_file', sprintf('%s/%s', $projectDirectory, '.deptrac.cache'));
    }
}
