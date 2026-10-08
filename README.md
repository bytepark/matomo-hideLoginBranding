# HideLoginBranding

Matomo plugin by **bytepark GmbH**.

Removes Matomo branding from the login pages without changing the Matomo core
and without a theme override:

- **"What's New" panel** (new in Matomo 5.14.0) next to the login form is no longer shown.
  The core login layout drops the right-hand column completely, so the form is centered as before.
- **Logo link** to matomo.org above the login form is removed (the logo itself stays).
- **"Web Analytics" link** to matomo.org below the login form is removed
  (Matomo renders it when a custom logo is configured).

Applies to every page that uses the login layout: login, password reset,
invitation accept/decline, confirm password and two-factor authentication,
including custom login plugins such as LoginSaml.

## Requirements

- Matomo >= 5.14.0, < 6.0

## Installation

1. Put the plugin into `plugins/HideLoginBranding` of your Matomo installation, e.g.:

   ```bash
   git clone https://github.com/bytepark/matomo-hideLoginBranding.git plugins/HideLoginBranding
   ```

   or download the ZIP from [GitHub](https://github.com/bytepark/matomo-hideLoginBranding) and extract it there
   (or upload the ZIP via *Administration > Plugins > Upload a plugin*, if uploads are enabled).
   The folder name must be exactly `HideLoginBranding` (case-sensitive). With any other spelling
   (such as the `matomo-hideLoginBranding-main` folder from a GitHub ZIP download) Matomo cannot autoload the plugin
   classes and the login page fails with
   `Entry "Piwik\Plugins\Login\WhatsNewProvider" cannot be resolved`.
2. Activate the plugin under *Administration > Plugins* or via CLI:
   `./console plugin:activate HideLoginBranding`
3. Clear caches if the panel still shows (it is cached for up to 1 hour for logged-out visitors):
   `./console cache:clear`

## How it works

| Element | Mechanism |
| --- | --- |
| What's New panel | DI override of `Piwik\Plugins\Login\WhatsNewProvider` (`config/config.php`) with a provider returning an empty list |
| Logo link / "Web Analytics" link | Event `Request.dispatch.end`: the rendered HTML of the modules `Login`, `TwoFactorAuth` and the active login plugin is post-processed |

To restore the default behaviour, deactivate the plugin.

## Notes

- The link filter matches the markup of Matomo 5.14 (`<span id="logo">` and `<p id="piwik">`).
  If Matomo changes this markup, the filter just stops matching; nothing breaks.
  Check the login page after Matomo major updates.

## License

MIT, see [LICENSE](LICENSE).
