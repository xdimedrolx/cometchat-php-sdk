<?php

$finder = PhpCsFixer\Finder::create()
    ->in('src')
    ->in('tests');


return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'no_empty_phpdoc' => false,
        'no_superfluous_phpdoc_tags' => false,
        'phpdoc_separation' => false,
        'no_unneeded_final_method' => false, # prevent phpstan divergence
    ])
    ->setFinder($finder)
    ;