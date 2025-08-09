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

function register_acf_blocks() {
    foreach ($blocks = new DirectoryIterator( __DIR__ . '/blocks' ) as $item) {
        // Check if block.json file exists in each subfolder.
        if ($item->isDir() && !$item->isDot()
            && file_exists($item->getPathname() . '/block.json')
        ) {
            // Register the block given the directory name within the blocks
            // directory.
            register_block_type($item -> getPathname());
        }
    }
}

add_action('init', 'register_acf_blocks');

function my_acf_block_render_callback($attributes, $content = '', $is_preview = false, $post_id = 0, $wp_block = null) {
    // Create the slug of the block using the name property in the block.json.
    $slug = str_replace( 'acf/', '', $attributes['name'] );

    $context = Timber::context();

    // Store block attributes.
    $context['attributes'] = $attributes;

    // Store field values. These are the fields from your ACF field group for the block.
    $context['fields'] = get_fields();

    // Store whether the block is being rendered in the editor or on the frontend.
    $context['is_preview'] = $is_preview;

    // Render the block.
    Timber::render(
        'blocks/' . $slug . '/' . $slug . '.twig',
        $context
    );
}

// Fonction pour charger les groupes de champs ACF depuis les fichiers JSON
function load_acf_field_groups() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Charger le groupe de champs cover-home
    $cover_home_json = file_get_contents(get_template_directory() . '/blocks/cover-home/block.json');
    $cover_home_group = json_decode($cover_home_json, true);
    
    if ($cover_home_group && is_array($cover_home_group)) {
        foreach ($cover_home_group as $group) {
            acf_add_local_field_group($group);
        }
    }
}

add_action('acf/init', 'load_acf_field_groups');

new StarterSite();
