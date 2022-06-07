<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('.github')
    ->exclude('bin')
    ->exclude('k8s')
    ->exclude('docker-image')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unneeded_curly_braces' => false,
        'phpdoc_summary' => false,
        'declare_strict_types' => true,
        'array_indentation' => true,
        'fully_qualified_strict_types' => true,
        'class_definition'=> true,
        'class_attributes_separation'=> true
    ])
    ->setFinder($finder)
;
