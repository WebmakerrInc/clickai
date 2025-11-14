=== ClickRank - Ai SEO Automation ===
Contributors: clickrank
Tags: seo, ai, automation, SEO automation,wordpress SEP, title, meta description, taxonomy, categories, tags
Requires at least: 5.8
Tested up to: 6.8
Stable tag: 3.3.5
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Supercharge your WordPress SEO with ClickRank.ai. Automate title & meta descriptions, generate schema, optimize images, and more with the power of AI.

== Description ==

The **ClickRank.ai** plugin brings a full suite of SEO automation tools straight into WordPress. Optimise titles, descriptions, schema, and image metadata automatically without connecting to any external account or service. Activate the plugin and your site is ready to start enhancing search performance immediately.

**Key Features:**

* **AI-Powered Title & Meta Optimization:** Automatically generate and apply SEO-friendly titles and meta descriptions for posts, pages, categories, tags, and custom taxonomies that are crafted to maximize click-through rates.
* **Complete WordPress Content Support:** Optimize all content types including posts, pages, categories, tags, custom post types, custom taxonomies, archive pages, and homepage.
* **Automatic Image SEO:** Generate descriptive alt text and title attributes for your images to improve accessibility and image search rankings.
* **Advanced Schema Markup:** Automatically generate and deploy structured data (JSON-LD) to help search engines understand your content and qualify for rich snippets.
* **Canonical URL Management:** Prevent duplicate content issues by automatically setting the optimal canonical URL for all your content types.
* **Automatic Link Titles:** Improve accessibility and SEO by automatically adding descriptive title attributes to links in your content.
* **Enhanced SEO Plugin Compatibility:** Full integration with Yoast SEO, RankMath, All In One SEO, and graceful fallbacks for other plugins.
* **Full Control & Revert:** Toggle individual automation modules and revert every optimisation with a single click inside the WordPress dashboard.
* **Secure & Transparent:** All automation happens locally. Detailed logs keep you informed of every optimisation performed.

== External Service Disclosure ==

ClickRank.ai now operates entirely in local mode. No external accounts, APIs, or remote web services are required for any feature. All processing stays on your WordPress site.

== Installation ==

1. Upload the `clickrank-ai` folder to the `/wp-content/plugins/` directory, or install the ZIP file directly through the WordPress plugins screen.
2. Activate the plugin.
3. Open **ClickRank.ai** from the WordPress admin menu to review available automation modules.
4. Adjust module toggles in the **Settings** tab to match your SEO strategy.
5. Use the **Run Local Sync** button on the dashboard whenever you want to refresh stored optimisations.

== Frequently Asked Questions ==

= Do I need a ClickRank.ai account to use this plugin? =

No. All features run locally inside your WordPress site—there is no signup, API key, or remote connection required.

= Will this work with my existing SEO plugin? =

Yes. Our plugin is designed to work alongside other popular SEO plugins. When an optimisation runs, the plugin intelligently overrides only the specific fields it manages (such as meta descriptions) so your existing settings remain intact.

= Is my data safe? =

Absolutely. Because the plugin operates in local mode, no optimisation data leaves your site. All processing happens on your server and every action is logged for review.

= Can I undo a change made by the automation? =

Yes. Use the **Revert All Changes** button in the Settings tab to roll back every optimisation to the original content.

= What happens if I uninstall the plugin? =

The `uninstall.php` script will clean up all plugin data, including module settings and the custom database table used for logging. Your site will revert to its previous state.

== Screenshots ==

1. The clean and informative Dashboard tab, providing an at-a-glance overview with local sync controls.
2. The powerful Settings tab, where you can manage automation mode and toggle individual modules.
3. The detailed Logs tab, showing a transparent history of all local automation activity.

== Changelog ==

= 3.3.5 =
* Major Enhancement: Fixed duplicate meta tags that were appearing on pages - now only ONE of each tag appears!
* Bugfix: Schema markup no longer duplicates - removed conflicts with Yoast SEO, RankMath, and All in One SEO.
* Bugfix: Meta descriptions now appear only once per page - eliminated duplicates from SEO plugins and themes.
* Bugfix: Canonical URLs no longer duplicate - only one canonical tag per page ensuring proper search engine indexing.
* Enhancement: Added complete Open Graph tags for better Facebook and social media sharing (og:title, og:description, og:url, og:type).
* Enhancement: Added complete Twitter Card support for improved Twitter sharing (twitter:title, twitter:description, twitter:card).
* Enhancement: Improved compatibility with all major SEO plugins - automatically removes their meta tags and replaces with ClickRank optimizations.
* Enhancement: Better URL-based optimization system - works correctly with translated pages and pagination.
* Performance: Pages now load faster with smaller HTML output due to removal of duplicate meta tags.
* SEO: Cleaner meta tags mean better search engine understanding and improved rankings.

= 3.3.3 =
* Enhancement: Improved database upgrade system.
* Bugfix: Minor fixes to URL-based SEO data storage.

= 3.3.2 =
* Bugfix: Enhanced homepage title handling to write directly to page source without JavaScript dependency.
* Enhancement: Unified PHP-based solution for both homepage title and meta description processing.
* Enhancement: Improved output buffering system to replace existing HTML tags with optimized content.

= 3.3.1 =
* Bugfix: Fixed duplicate meta descriptions appearing on homepage when both SEO plugin filters and direct injection were active.
* Enhancement: Improved homepage meta description handling with reliable PHP output buffering solution.
* Enhancement: Added comprehensive meta description cleanup to ensure only one description tag appears per page.

= 3.3.0 =
* Major Enhancement: Production-ready release with comprehensive bug fixes and enhanced SEO plugin compatibility.
* Bugfix: Completely resolved template variables (%%sitename%%, %sep%, etc.) displaying on fresh plugin installations.
* Bugfix: Fixed ampersand encoding issue where "&" characters were displaying as "&amp;" in titles and meta descriptions.
* Enhancement: Improved RankMath, Yoast, and All-in-One SEO plugin compatibility with multiple override approaches.
* Enhancement: Enhanced JavaScript title override system for persistent SEO plugins.
* Enhancement: Added comprehensive template variable detection to prevent raw templates from ever appearing.
* Security: Removed all test and backup files for clean production deployment.
* Performance: Optimized SEO override system to only activate when ClickRank AI has optimization data.

= 3.2.1 =
* Bugfix: Fixed template variables (%%sitename%%, %sep%, etc.) displaying on fresh plugin installations instead of proper processed titles.
* Bugfix: Fixed ampersand encoding issue where "&" characters were displaying as "&amp;" in titles and meta descriptions.
* Enhancement: Improved SEO plugin compatibility to prevent raw template variables from ever appearing to users.
* Enhancement: Enhanced override system to only activate when ClickRank AI has actual optimization data.

= 3.2.0 =
* Major Enhancement: Added comprehensive support for WordPress taxonomy pages including categories, tags, and custom taxonomies.
* Major Enhancement: Added support for taxonomy archive base pages (e.g., /category, /tag).
* Enhancement: Completely integrated SEO compatibility layer with all popular SEO plugins (Yoast, RankMath, All In One SEO).
* Enhancement: Unified SEO plugin detection and meta key handling across all content types.
* Enhancement: Enhanced webhook endpoint to handle all WordPress content types.
* Enhancement: Added revert functionality for all new content types.
* Bugfix: Fixed post URL resolution for date-based and complex permalink structures.
* Compatibility: Full support for custom post types and custom taxonomies.
* Enhancement: Added link title optimization support through sync data processing.

= 3.1.2 =
* Bugfix: Implemented definitive fixes for schema overrides and image alt text updates to ensure compatibility with other plugins and themes.
* Bugfix: Corrected and fully implemented revert functionality for all optimization types.
* Bugfix: Improved image lookup logic to correctly find images from resized thumbnail URLs.
* Enhancement: Upgraded the API structure to handle both image alt text and title updates.

= 3.1.1 =
* Security: Hardened escaping on all echoed variables to prevent potential XSS vulnerabilities.
* Bugfix: Correctly enqueued admin scripts and styles.
* Enhancement: Added full disclosure of external services.
* Enhancement: Removed specific plugin names from readme.

= 3.1.0 =
* Major Refactor: Complete rewrite for a more professional and scalable architecture.

== Upgrade Notice ==

= 3.3.5 =
Critical update! Fixes duplicate meta tags, schema, and canonical URLs that could hurt your SEO. This update ensures only one of each meta tag appears on your pages, improving search engine rankings and social media sharing. Highly recommended for all users!
