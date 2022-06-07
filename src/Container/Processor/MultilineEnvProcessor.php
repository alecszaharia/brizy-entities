<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Container\Processor;

use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

/**
 * Class MultilineEnvProcessor.
 *
 * This will replace the "\n" string with real new lines.
 * Not sure why but thee AWS does not support multiline environment variables on ECS.
 */
class MultilineEnvProcessor implements EnvVarProcessorInterface
{
    /**
     * @param string $prefix
     * @param string $name
     *
     * @return mixed|string
     */
    public function getEnv($prefix, $name, \Closure $getEnv)
    {
        return str_replace(["\n", '\n'], "\n", (string) $getEnv($name));
    }

    /**
     * @return string[]
     */
    public static function getProvidedTypes()
    {
        return [
            'multiline' => 'string',
        ];
    }
}
