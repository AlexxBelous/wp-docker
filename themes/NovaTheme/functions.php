<?php

/*
|--------------------------------------------------------------------------
| DATABASE REPOSITORY START
|--------------------------------------------------------------------------
| This section describes the core logic for working with entities
*/
define('IS_VITE_DEVELOPMENT', true);
/*----------------------- END OF DATABASE REPOSITORY --------------------*/




/*
|--------------------------------------------------------------------------
| DATABASE REPOSITORY START
|--------------------------------------------------------------------------
| Get the theme version from style.css to use it for cache busting
*/
$theme = wp_get_theme();
define('THEME_VERSION', $theme->get('Version'));
/*----------------------- END OF DATABASE REPOSITORY --------------------*/



/*
|--------------------------------------------------------------------------
| VITE HMR PREAMBLE START
|--------------------------------------------------------------------------
| Output React preamble for Vite HMR (Fast Refresh) to work.
| Uncomment the add_action below when you start working with React.
*/
function novatheme_vite_head_preamble() {
    if (IS_VITE_DEVELOPMENT) {
        ?>
        <script type="module">
            import RefreshRuntime from 'http://localhost:3000/@react-refresh'
            RefreshRuntime.injectIntoGlobalHook(window)
            window.$RefreshReg$ = () => {}
            window.$RefreshSig$ = () => (type) => type
            window.__vite_plugin_react_preamble_installed__ = true
        </script>
        <?php
    }
}
// add_action('wp_head', 'novatheme_vite_head_preamble');
/*----------------------- END OF VITE HMR PREAMBLE -----------------------*/



/*
|--------------------------------------------------------------------------
| ASSETS ENQUEUING START
|--------------------------------------------------------------------------
| Main function to register and enqueue scripts and styles for NovaTheme.
| It handles both Vite development server and production build modes.
*/
function novatheme_enqueue_scripts()
{
    if (IS_VITE_DEVELOPMENT) {

        /* --- DEVELOPMENT MODE (Vite) --- */

        // 1. Enqueue Vite client for HMR
        wp_enqueue_script('vite-client', 'http://localhost:3000/@vite/client', [], null, true);

        // Add type="module" filter for ES modules
        add_filter('script_loader_tag', function ($tag, $handle) {
            if ($handle === 'vite-client' || $handle === 'novatheme-main-js') {
                return str_replace('<script ', '<script type="module" ', $tag);
            }
            return $tag;
        }, 10, 2);

        // 2. Enqueue main JS entry point from source (main.jsx)
        wp_enqueue_script('novatheme-main-js', 'http://localhost:3000/src/js/main.jsx', [], null, true);

        // 3. Enqueue main SCSS (Vite compiles this on the fly)
        wp_enqueue_style('novatheme-main-style', 'http://localhost:3000/src/scss/main.scss', [], null);

    } else {

        /* --- PRODUCTION MODE (Bundled assets from manifest) --- */

        $manifest_path = get_theme_file_path('assets/.vite/manifest.json');

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);

            if ($manifest) {
                // Enqueue compiled JS and add theme version for cache busting
                if (isset($manifest['src/js/main.jsx'])) {
                    wp_enqueue_script('novatheme-main-js', get_theme_file_uri('assets/' . $manifest['src/js/main.jsx']['file']), [], THEME_VERSION, true);

                    add_filter('script_loader_tag', function ($tag, $handle) {
                        if ($handle === 'novatheme-main-js') {
                            return str_replace('<script ', '<script type="module" ', $tag);
                        }
                        return $tag;
                    }, 10, 2);
                }

                // Enqueue compiled CSS and add theme version for cache busting
                if (isset($manifest['src/scss/main.scss'])) {
                    wp_enqueue_style('novatheme-main-style', get_theme_file_uri('assets/' . $manifest['src/scss/main.scss']['file']), [], THEME_VERSION);
                }
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'novatheme_enqueue_scripts');
/*----------------------- END OF ASSETS ENQUEUING -----------------------*/