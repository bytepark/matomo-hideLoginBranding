<?php

/**
 * HideLoginBranding - DI configuration
 *
 * Replaces the core "What's New" provider of the login layout with one that
 * always returns an empty list.
 */

return [
    \Piwik\Plugins\Login\WhatsNewProvider::class =>
        \Piwik\DI::autowire(\Piwik\Plugins\HideLoginBranding\NoWhatsNewProvider::class),
];
