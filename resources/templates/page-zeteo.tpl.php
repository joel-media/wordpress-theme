<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;

/*
 * Standalone, app-like Zeteo page — no site header/footer.
 */
?>
<!doctype html>
<html class="no-js c-zeteo" <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, interactive-widget=resizes-content">
    <meta name="theme-color" content="#061375">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <?php wp_head(); ?>
  </head>

  <body <?php body_class('c-zeteo'); ?>>

    <?php template('vue-components/main', [
      'component' => 'JoCookieConsent',
      'id' => 'cookie-consent',
      'options' => [
        'page-name' => get_bloginfo('name'),
        'privacy-policy-link' => home_url('/datenschutzerklaerung/'),
      ],
    ]) ?>

    <?php template('vue-components/main', [
      'component' => 'JoZeteo',
      'id' => 'JoZeteo',
      'options' => [
        'api_url' => config('study-center-url'),
        'recording_count' => (int) wp_count_posts('recordings')->publish,
        'home_url' => home_url('/'),
        'logo_url' => asset_path('images/jm-logo-white-01.svg'),
      ],
    ]) ?>

    <?php wp_footer(); ?>
  </body>
</html>
