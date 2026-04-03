<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?> <header class="site-header">
    <div class="container">
        <div class="site-branding">
            <?php the_custom_logo(); ?>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
        </div>

        <nav id="site-navigation" class="main-navigation">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'menu-1',
                'menu_id'        => 'primary-menu',
            ) );
            ?>
        </nav>
    </div>
</header>
