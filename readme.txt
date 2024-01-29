=== Gutailberg ===
Contributors:      Tung Du
Tags:              fse, full-site-editing, gutenberg, block
Tested up to:      6.4
Stable tag:        0.1.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Gutailberg brings Tailwind to Gutenberg.

== Description ==

Gutailberg is a (nearly) production ready, zero build step TailwindCSS solution for Gutenberg.

- Small footprint. Tailwind only generates the style that you use. No extra bloat.
- Interactive development. The style is generated on the fly, thanks to Tailwind Play CDN.
- Customizable config. Tailwind config can be customized in the plugin settings.
- Seamless integration. Gutailberg reuses the block class name settings. No extra attributes needed.
- Friendly UI. We add a extra class name input with Tailwind classes suggestion to the block inspector. The suggestions are config-aware, meaning that they are generated based on your Tailwind config if exist.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/gutailberg` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Customize your Tailwind config at Tools -> Gutailberg screen. Preflight is disbled by default to avoid conflicts with current styles.
4. Start using Tailwind classes in the block inspector.
5. By default, the output field is empty. Tailwind CDN is used to generate style both on frontend and in the editor.
6. When your site is ready, go to Tools -> Gutailberg screen and generate the style. Your site will now use the generated style instead of Tailwind CDN.
7. You can also copy the generated style to Site Editor > Style > `Additional CSS` then disable this plugin.

== Frequently Asked Questions ==

= How does it generate the style in the setting page? =

When it comes to generating the style, under the hood, the plugin:

1. loads Tailwind Play CDN
2. requests an export of the current block theme, grabs the templates and then send them as a giant string to client.
3. gets and appends the result to the body, lets Tailwind Play CDN do its magic.
4. grabs the generated style and saves it to the output field.

It sounds like a hack, that's why I put `nearly` next to production ready.

= What if Tailwind shuts down the CDN =

It's possible to run Tailwind in browswer. With mhsdesign/jit-browser-tailwindcss, we can bundle Tailwind and run it in the browser. In this plugin, we're already using a folk of that repository to generate Tailwind classes for suggestions.

== Screenshots ==

== Changelog ==

= 0.1.0 =
* Release
