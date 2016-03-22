<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__ . '/../src')
    ->in(__DIR__ . '/../tests')
;

return Symfony\CS\Config\Config::create()
    ->fixers([
        'align_double_arrow',
        'align_equals',
        'short_array_syntax',
        'ordered_use',
    ])
    ->finder($finder)
    ->setUsingCache(true)
;