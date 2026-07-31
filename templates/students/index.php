<?php

/**
 * @package     Joomla.Site
 * @subpackage  Templates.cassiopeia
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$input = $app->getInput();
$wa = $this->getWebAssetManager();

// Browsers support SVG favicons
// $this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
// $this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

// Detecting Active Variables
$option = $input->getCmd('option', '');
$view = $input->getCmd('view', '');
$layout = $input->getCmd('layout', '');
$task = $input->getCmd('task', '');
$itemid = $input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Color Theme
$paramsColorName = $this->params->get('colorName', 'colors_standard');
$assetColorName = 'theme.' . $paramsColorName;

// Use a font scheme if set in the template style options
$paramsFontScheme = $this->params->get('useFontScheme', false);
$fontStyles = '';

if ($paramsFontScheme) {
    if (stripos($paramsFontScheme, 'https://') === 0) {
        $this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preload($paramsFontScheme, ['as' => 'style', 'crossorigin' => 'anonymous']);
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], ['rel' => 'lazy-stylesheet', 'crossorigin' => 'anonymous']);

        if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0) {
            $fontStyles = '--cassiopeia-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
			--cassiopeia-font-family-headings: "' . str_replace('+', ' ', $matches[1][1] ?? $matches[1][0]) . '", sans-serif;
			--cassiopeia-font-weight-normal: 400;
			--cassiopeia-font-weight-headings: 700;';
        }
    } elseif ($paramsFontScheme === 'system') {
        $fontStylesBody = $this->params->get('systemFontBody', '');
        $fontStylesHeading = $this->params->get('systemFontHeading', '');

        if ($fontStylesBody) {
            $fontStyles = '--cassiopeia-font-family-body: ' . $fontStylesBody . ';
            --cassiopeia-font-weight-normal: 400;';
        }
        if ($fontStylesHeading) {
            $fontStyles .= '--cassiopeia-font-family-headings: ' . $fontStylesHeading . ';
    		--cassiopeia-font-weight-headings: 700;';
        }
    } else {
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], ['rel' => 'lazy-stylesheet']);
        $this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
    }
}

// Enable assets
$wa->usePreset('template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->registerAndUseStyle($assetColorName, 'global/' . $paramsColorName . '.css')
    ->useStyle('template.user')
    ->useScript('template.user')
    ->addInlineStyle(":root {
		--hue: 214;
		--template-bg-light: #f0f4fb;
		--template-text-dark: #495057;
		--template-text-light: #ffffff;
		--template-link-color: var(--link-color);
		--template-special-color: #001B4C;
		$fontStyles
	}");

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Logo file or site title param
if ($this->params->get('logoFile')) {
    $logo = HTMLHelper::_('image', Uri::root(false) . htmlspecialchars($this->params->get('logoFile'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} elseif ($this->params->get('siteTitle')) {
    $logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
} else {
    $logo = HTMLHelper::_('image', 'logo.svg', $sitename, ['class' => 'logo d-inline-block', 'loading' => 'eager', 'decoding' => 'async'], true, 0);
}

$hasClass = '';

if ($this->countModules('sidebar-left', true)) {
    $hasClass .= ' has-sidebar-left';
}

if ($this->countModules('sidebar-right', true)) {
    $hasClass .= ' has-sidebar-right';
}

// Container
$wrapper = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$stickyHeader = $this->params->get('stickyHeader') ? 'position-sticky sticky-top' : '';

// Defer fontawesome for increased performance. Once the page is loaded javascript changes it to a stylesheet.
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>

<body>

    <section class="navigation">
        <div class="container">
            <div class="row top-menu">

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-5">
                            <jdoc:include type="modules" name="top-contacts" style="none" />
                        </div>
                        <div class="col-md-5 mt-2">
                            <jdoc:include type="modules" name="top-menu" style="none" />
                        </div>
                        <div class="col-md-2">
                            <jdoc:include type="modules" name="social-media" style="none" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="lower-menu">
        <div class="container">
            <div class="row">

                <div class="col-md-2">
                    <div class="logo-container">
                        <a class="navbar-brand" href="<?php echo Uri::base(); ?>">
                            <img src="images/logo.jpg" alt="Pemarc Logo">
                        </a>
                    </div>
                </div>

                <div class="col-md-9">
                    <jdoc:include type="modules" name="menu" style="none" />
                </div>

                <div class="col-md-1">
                    <div class="d-flex justify-content-end align-items-center gap-2">
                        <div class="engine-button">
                            <button id="toggle">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="engine text-right">
                        <div class="engine-inner" id="engine-inner">
                            <jdoc:include type="modules" name="search" style="none" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- <jdoc:include type="modules" name="inner-slider" style="none" /> -->

    <section class="contents">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-content">
                        <div class="col-sm-12">
                            <jdoc:include type="component" />
                        </div>
                        



                    </div>
                </div>




            </div>
        </div>
    </section>




    <section class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <jdoc:include type="modules" name="zetech-experience" style="xhtml" />
                </div>
                <div class="col-md-2">
                    <jdoc:include type="modules" name="quicklinks" style="xhtml" />
                </div>

                <div class="col-md-4">
                    <jdoc:include type="modules" name="schools-institutes" style="xhtml" />
                </div>

                <div class="col-md-3">
                    <jdoc:include type="modules" name="footer-contact-us" style="xhtml" />
                </div>
            </div>
        </div>
    </section>

    <section class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="footer-bottom-left">
                    <a href="/privacy-policy">Privacy Policy</a>
                    <span>|</span>
                    <a href="/site-map">Site Map</a>
                </div>

                <div class="footer-bottom-center">
                    © <?php echo date('Y'); ?> Pemarc Institute | Empowering Minds Building Futures. All Rights Reserved.

                </div>

            </div>
        </div>
    </section>


    <section class="quick-actions">
        <jdoc:include type="modules" name="quick-actions" style="xhtml" />
    </section>

    <jdoc:include type="modules" name="debug" style="none" />

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const items = document.querySelectorAll(".jo-timeline .timeline-item");

            items.forEach(function(item) {
                const title = item.querySelector(".timeline-title");

                title.addEventListener("click", function() {
                    // Close all other items
                    items.forEach(function(otherItem) {
                        if (otherItem !== item) {
                            otherItem.classList.remove("expanded");
                        }
                    });

                    // Toggle current item
                    item.classList.toggle("expanded");
                });
            });
        });



        document.addEventListener("DOMContentLoaded", function() {
            const lowerMenu = document.querySelector(".lower-menu");
            if (!lowerMenu) return;
            const stickyOffset = lowerMenu.offsetTop;
            window.addEventListener("scroll", function() {
                if (window.scrollY > stickyOffset) {
                    lowerMenu.classList.add("is-sticky");
                    document.body.classList.add("has-sticky-menu");
                } else {
                    lowerMenu.classList.remove("is-sticky");
                    document.body.classList.remove("has-sticky-menu");
                }
            });
        });



        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll('.zn-counter');

            const animateCounters = () => {
                counters.forEach(counter => {
                    const update = () => {
                        const target = +counter.getAttribute('data-target');
                        const current = +counter.innerText;
                        const increment = target / 70;

                        if (current < target) {
                            counter.innerText = Math.ceil(current + increment);
                            requestAnimationFrame(update);
                        } else {
                            counter.innerText = target.toLocaleString();
                        }
                    };
                    update();
                });
            };

            // Trigger when section is visible
            const observer = new IntersectionObserver(entries => {
                if (entries[0].isIntersecting) {
                    animateCounters();
                    observer.disconnect();
                }
            });

            observer.observe(document.querySelector('.zetech-numbers'));
        });



        const targetDiv = document.getElementById("engine-inner");
        const btn = document.getElementById("toggle");
        btn.onclick = function() {
            if (targetDiv.style.display !== "block") {

                targetDiv.style.display = "block";

            } else {
                targetDiv.style.display = "none";
            }
        };
    </script>

</body>

</html>