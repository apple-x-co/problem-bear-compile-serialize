<?php

declare(strict_types=1);

/**
 * @see http://bearsunday.github.io/manuals/1.0/ja/router.html
 * @var \Aura\Router\Map $map
 */

$map->attach('/store', '/~{storeKey}', static function (\Aura\Router\Map $map) {
    $map->tokens([
        'storeKey' => '[0-9a-zA-Z]{10,20}',
    ]);

    // Me API
    $map->attach('/me', '/me', static function (\Aura\Router\Map $map) {
        $map->route('/cart', '/cart');
        $map->route('/cart/item', '/cart/item');
        $map->route('/products/favorites', '/products/favorites');
        $map->route('/products/views', '/products/views');
    });
    $map->route('/me', '/me');

    // Common API
    $map->attach('/common', '/common', static function (\Aura\Router\Map $map) {
        $map->route('/products', '/products');
    });

    $map->route('/forgot-password', '/forgot-password');
    $map->route('/forgot-password/complete', '/forgot-password/complete');
    $map->route('/index', '/index');
    $map->route('/login', '/login');
    $map->route('/logout', '/logout');
    $map->route('/reset-password', '/reset-password');
    $map->route('/reset-password/complete', '/reset-password/complete');
    $map->route('/sign-up', '/sign-up');
    $map->route('/sign-up/confirm', '/sign-up/confirm');
    $map->route('/sign-up/unverified', '/sign-up/unverified');
    $map->route('/sign-up/verified', '/sign-up/verified');
    $map->route('/sign-up/verify', '/sign-up/verify');
});
