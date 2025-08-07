<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 */

// Load Composer dependencies.
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/StarterSite.php';

Timber\Timber::init();

// Sets the directories (inside your theme) to find .twig files.
Timber::$dirname = [ 'templates', 'views' ];


 add_filter( 'timber/twig', function( \Twig\Environment $twig ) {
    $twig->addFunction( new \Twig\TwigFunction(
        'dump',
        [ 'Symfony\Component\VarDumper\VarDumper', 'dump' ]
    ) );
    return $twig;
} );

new StarterSite();
