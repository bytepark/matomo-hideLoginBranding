<?php

/**
 * HideLoginBranding - Matomo plugin
 *
 * @author  bytepark GmbH
 * @link    https://www.bytepark.de
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Piwik\Plugins\HideLoginBranding;

use Piwik\Plugins\Login\WhatsNewProvider;

/**
 * Replaces the core provider via DI (see config/config.php). An empty list makes
 * the login layout drop the "What's New" column entirely.
 */
class NoWhatsNewProvider extends WhatsNewProvider
{
    public function getChanges(): array
    {
        return [];
    }
}
