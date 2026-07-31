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
<meta name="google-site-verification" content="ERUwwWJH5d9KPl1peSA2o_F2eOXIm1LW2wfaUd-9-hg" />

<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-18324746501">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-18324746501');
</script>
    
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GTM-N6PKNP9B"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  // Google Analytics 4
  gtag('config', 'GTM-N6PKNP9B');

  // Google Ads
  gtag('config', 'AW-18324746501');
</script>

<body>



    <section class="navigation">
        <div class="container">
            <div class="row top-menu">

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <jdoc:include type="modules" name="top-contacts" style="none" />
                        </div>
                        <div class="col-md-1 mt-2">
                            <jdoc:include type="modules" name="top-menu" style="none" />
                        </div>
                        <div class="col-md-5">
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

    <section class="slider">
        <jdoc:include type="modules" name="slider" style="none" />

    </section>


    <!-- <section class='introduction'>
        <div class="container">
            <div class="row g-0">


                <div class="col-lg-12">
                    <div class="intro-content">
                        <div class="content-bg"></div>
                        <div class="content-inner">
                            <jdoc:include type="modules" name="about-us-introduction" style="xhtml" />

                            <div class="read-more mt-3"><a href="<?php echo Uri::base(); ?>/about-zu/about-us"
                                    class="btn btn-primary">Learn More</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <section class="zetech-events">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="testimonials-title">Explore Courses</h3>
                </div>
                <div class="col-12">
                    <jdoc:include type="modules" name="ourcourses" style="xhtml" />
                </div>
            </div>
        </div>
    </section>

    <!-- <section class="our-programs">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-3">
                    <span class="section-label">Our Programmes</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2 col-sm-6 mb-4">
                    <div class="program-card">
                        <div class="program-content">
                            <jdoc:include type="modules" name="postgraduate-programs" style="xhtml" />
                        </div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 mb-4">
                    <div class="program-card">
                        <div class="program-content">
                            <jdoc:include type="modules" name="degree-programs" style="none" />
                        </div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 mb-4">
                    <div class="program-card">
                        <div class="program-content">
                            <jdoc:include type="modules" name="diploma-programs" style="none" />
                        </div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 mb-4">
                    <div class="program-card">
                        <div class="program-content">
                            <jdoc:include type="modules" name="certificate-programs" style="none" />
                        </div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 mb-4">
                    <div class="program-card">
                        <div class="program-content">
                            <jdoc:include type="modules" name="tvet-programs" style="none" />
                        </div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 mb-4">
                    <div class="program-card">
                        <div class="program-content">
                            <jdoc:include type="modules" name="corporate-programs" style="none" />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section> -->


    <section class="why-choose-zetech">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center mb-4">
                    <jdoc:include type="modules" name="why-choose-zetech" style="card" />
                </div>
            </div>

            <div class="row features-row">
                <div class="col-md-8 col-sm-6 mb-3">
                    <div class="feature-card">
                        <span class="feature-icon">01</span>
                        <div class="feature-body">
                            <jdoc:include type="modules" name="quality-guaranteed" style="card" />
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="feature-card">
                        <span class="feature-icon">02</span>
                        <div class="feature-body">
                            <jdoc:include type="modules" name="timely-completion" style="card" />
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="feature-card">
                        <span class="feature-icon">03</span>
                        <div class="feature-body">
                            <jdoc:include type="modules" name="students-finance" style="card" />
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="feature-card">
                        <span class="feature-icon">04</span>
                        <div class="feature-body">
                            <jdoc:include type="modules" name="great-prospects" style="card" />
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="feature-card">
                        <span class="feature-icon">05</span>
                        <div class="feature-body">
                            <jdoc:include type="modules" name="connect-students" style="card" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- <section class="institutes">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <jdoc:include type="modules" name="schools" style="xtml" />
                </div>
            </div>
        </div>
    </section> -->

    <section class="cta-section my-5">
        <div class="container">
            <div class="cta-wrapper">

                <div class="cta-content">
                    <jdoc:include type="modules" name="cta-content" style="none" />
                </div>

                <div class="cta-image">
                    <jdoc:include type="modules" name="cta-image" style="none" />
                </div>

            </div>
        </div>
    </section>






    <section class="zetech-events">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <jdoc:include type="modules" name="events" style="xhtml" />
                </div>
            </div>
        </div>
    </section>





    <section class="zetech-testimonials py-3">
        <div class="container">

            <div class="row mb-5">
                <div class="col-lg-12">
                    <h5 class="testimonials-title">Our Testimonials</h5>
                </div>

                <div class="row">
                    <jdoc:include type="modules" name="testimonials" style="xhtml" />

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
                    © <?php echo date('Y'); ?> Pemarc Institute | Empowering Minds Building Futures. All Rights
                    Reserved.

                </div>


            </div>
        </div>
    </section>


    <section class="quick-actions">
        <jdoc:include type="modules" name="quick-actions" style="xhtml" />
    </section>

    <jdoc:include type="modules" name="debug" style="none" />
    <script>
        // ============================================
        // 1. COUNTER ANIMATION FOR NUMBERS
        // ============================================
        document.addEventListener("DOMContentLoaded", function () {
            const items = document.querySelectorAll('.zetech-numbers .mod-articles-title a');

            function animate(el) {
                const original = el.dataset.original;
                const match = original.match(/[\d,]+/);
                if (!match) return;
                const target = parseInt(match[0].replace(/,/g, ''));
                let count = 0;
                const speed = target / 80;

                function update() {
                    count += speed;
                    if (count < target) {
                        el.innerText = original.replace(match[0], Math.floor(count).toLocaleString());
                        requestAnimationFrame(update);
                    } else {
                        el.innerText = original;
                    }
                }
                update();
            }

            items.forEach(el => {
                el.dataset.original = el.innerText;
            });

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counters = entry.target.querySelectorAll('.mod-articles-title a');
                        counters.forEach(el => {
                            el.innerText = el.dataset.original.replace(/[\d,]+/, "0");
                            animate(el);
                        });
                    }
                });
            }, { threshold: .4 });

            observer.observe(document.querySelector('.zetech-numbers'));
        });

        // ============================================
        // 2. STICKY MENU (KEEP THIS!)
        // ============================================
        document.addEventListener("DOMContentLoaded", function () {
            const lowerMenu = document.querySelector(".lower-menu");
            if (!lowerMenu) return;
            const stickyOffset = lowerMenu.offsetTop;
            window.addEventListener("scroll", function () {
                if (window.scrollY > stickyOffset) {
                    lowerMenu.classList.add("is-sticky");
                    document.body.classList.add("has-sticky-menu");
                } else {
                    lowerMenu.classList.remove("is-sticky");
                    document.body.classList.remove("has-sticky-menu");
                }
            });
        });

        // ============================================
        // 3. SEARCH TOGGLE BUTTON (KEEP THIS!)
        // ============================================
        const targetDiv = document.getElementById("engine-inner");
        const btn = document.getElementById("toggle");
        btn.onclick = function () {
            if (targetDiv.style.display !== "block") {
                targetDiv.style.display = "block";
            } else {
                targetDiv.style.display = "none";
            }
        };

        // ============================================
        // 4. COMBINED SCROLL ANIMATIONS
        // ============================================
        document.addEventListener('DOMContentLoaded', function () {

            function throttle(func, limit) {
                let inThrottle;
                return function () {
                    if (!inThrottle) {
                        func.apply(this, arguments);
                        inThrottle = true;
                        setTimeout(function () { inThrottle = false; }, limit);
                    }
                };
            }

            function isInViewport(element) {
                if (!element) return false;
                const rect = element.getBoundingClientRect();
                const windowHeight = window.innerHeight || document.documentElement.clientHeight;
                return (rect.top <= windowHeight * 0.75 && rect.bottom >= 0);
            }

            function revealAllSections() {

                // 1. Introduction Section
                const introVisual = document.querySelector('.intro-visual');
                const introContent = document.querySelector('.intro-content');
                const statItems = document.querySelectorAll('.stat-item');
                const readMore = document.querySelector('.read-more');

                if (introVisual && isInViewport(introVisual) && !introVisual.classList.contains('active')) {
                    introVisual.classList.add('active');
                }

                if (introContent && isInViewport(introContent) && !introContent.classList.contains('active')) {
                    introContent.classList.add('active');
                    statItems.forEach(function (item) {
                        if (!item.classList.contains('active')) {
                            item.classList.add('active');
                        }
                    });
                    if (readMore && !readMore.classList.contains('active')) {
                        readMore.classList.add('active');
                    }
                }

                // 2. Program Cards Section
                const sectionLabel = document.querySelector('.our-programs .section-label');
                const programCards = document.querySelectorAll('.our-programs .program-card');

                if (sectionLabel && isInViewport(sectionLabel) && !sectionLabel.classList.contains('active')) {
                    sectionLabel.classList.add('active');
                }

                programCards.forEach(function (card) {
                    if (isInViewport(card) && !card.classList.contains('active')) {
                        card.classList.add('active');
                    }
                });

                // 3. Why Choose  Section
                const whyHeader = document.querySelector('.why-choose-zetech .col-md-12.text-center');
                const featureCards = document.querySelectorAll('.why-choose-zetech .feature-card');

                if (whyHeader && isInViewport(whyHeader) && !whyHeader.classList.contains('active')) {
                    whyHeader.classList.add('active');
                }

                featureCards.forEach(function (card) {
                    if (isInViewport(card) && !card.classList.contains('active')) {
                        card.classList.add('active');
                    }
                });

                // 4. CTA Section
                const ctaContent = document.querySelector('.cta-section .cta-content');
                const ctaImage = document.querySelector('.cta-section .cta-image');

                if (ctaContent && isInViewport(ctaContent) && !ctaContent.classList.contains('active')) {
                    ctaContent.classList.add('active');
                }

                if (ctaImage && isInViewport(ctaImage) && !ctaImage.classList.contains('active')) {
                    ctaImage.classList.add('active');
                }

                // 5. Zetech Numbers Section
                const numbersHeader = document.querySelector('.zetech-numbers h3');
                const numberCards = document.querySelectorAll('.zetech-numbers .mod-articles-item');

                if (numbersHeader && isInViewport(numbersHeader) && !numbersHeader.classList.contains('active')) {
                    numbersHeader.classList.add('active');
                }

                numberCards.forEach(function (card) {
                    if (isInViewport(card) && !card.classList.contains('active')) {
                        card.classList.add('active');
                    }
                });

                // 6. Zetech Testimonials Section
                const testimonialsTitle = document.querySelector('.zetech-testimonials .testimonials-title');
                const testimonialsIntro = document.querySelector('.zetech-testimonials .testimonials-intro');
                const testimonialSlides = document.querySelectorAll('.zetech-testimonials .slide');

                if (testimonialsTitle && isInViewport(testimonialsTitle) && !testimonialsTitle.classList.contains('active')) {
                    testimonialsTitle.classList.add('active');
                }

                if (testimonialsIntro && isInViewport(testimonialsIntro) && !testimonialsIntro.classList.contains('active')) {
                    testimonialsIntro.classList.add('active');
                }

                testimonialSlides.forEach(function (slide) {
                    if (isInViewport(slide) && !slide.classList.contains('active')) {
                        slide.classList.add('active');
                    }
                });

                // 7. News Announcements Section
                const newsCards = document.querySelectorAll('.news-announcements .custom-card');

                newsCards.forEach(function (card) {
                    if (isInViewport(card) && !card.classList.contains('active')) {
                        card.classList.add('active');
                    }
                });
            }

            const throttledReveal = throttle(revealAllSections, 100);
            window.addEventListener('scroll', throttledReveal);
            revealAllSections();
        });
    </script>
</body>

</html>