<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('public')
    ->exclude('node_modules');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer'=> true,
        '@Symfony' => true,
        'single_line_empty_body' => true,
        'semicolon_after_instruction' => true,
        'single_line_comment_style' => false,
    ])
    ->setFinder($finder);
