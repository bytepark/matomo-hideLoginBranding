<?php

/**
 * HideLoginBranding - Matomo plugin
 *
 * @author  bytepark GmbH
 * @link    https://www.bytepark.de
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Piwik\Plugins\HideLoginBranding;

use Piwik\Piwik;

/**
 * Removes Matomo branding links from all pages that use the login layout
 * (login, password reset, invitations, confirm password, 2FA).
 *
 * The "What's New" panel itself is disabled via DI in config/config.php.
 */
class HideLoginBranding extends \Piwik\Plugin
{
    /**
     * @see \Piwik\Plugin::registerEvents
     */
    public function registerEvents()
    {
        return [
            'Request.dispatch.end' => 'filterLoginOutput',
        ];
    }

    /**
     * Post-processes the rendered HTML of login-related controller actions.
     *
     * @param mixed $result     Rendered controller output (passed by reference)
     * @param string $module
     * @param string $action
     * @param array $parameters
     */
    public function filterLoginOutput(&$result, $module = '', $action = '', $parameters = [])
    {
        if (!is_string($result) || $result === '' || !$this->isLoginModule($module)) {
            return;
        }

        $result = self::stripBrandingLinks($result);
    }

    /**
     * Removes the matomo.org link around the logo (the image stays) and the
     * "Web Analytics" link (<p id="piwik">) below the login form.
     *
     * Public and static so it can be used without a plugin instance.
     */
    public static function stripBrandingLinks(string $html): string
    {
        // Logo: unwrap <a href="https://matomo.org..."> inside <span id="logo">, keep the <img>
        $filtered = preg_replace(
            '~(<span\s+id="logo"[^>]*>)\s*<a\s[^>]*href="https?://(?:[a-z0-9-]+\.)*matomo\.org[^"]*"[^>]*>(.*?)</a>~is',
            '$1$2',
            $html
        );
        if (is_string($filtered)) {
            $html = $filtered;
        }

        // "Web Analytics" / random title link below the login form (only rendered with a custom logo)
        $filtered = preg_replace('~<p\s+id="piwik"[^>]*>.*?</p>~is', '', $html);
        if (is_string($filtered)) {
            $html = $filtered;
        }

        return $html;
    }

    private function isLoginModule($module): bool
    {
        if (!is_string($module) || $module === '') {
            return false;
        }

        $modules = ['Login', 'TwoFactorAuth'];

        $loginPlugin = Piwik::getLoginPluginName();
        if (!empty($loginPlugin)) {
            $modules[] = $loginPlugin;
        }

        return in_array($module, $modules, true);
    }
}
