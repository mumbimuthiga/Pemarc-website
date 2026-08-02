
<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$wa = Factory::getApplication()
    ->getDocument()
    ->getWebAssetManager();

$wa->registerAndUseStyle(
    'students.courses',
    'templates/students/css/courses.css'
);

$article = $this->item;

$fields = [];

foreach ($article->jcfields as $field)
{
    $fields[$field->name] = $field->rawvalue;
}
?>

<section class="course-hero">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6 course-hero-content">

                <h1 class="course-title">
                    <?= htmlspecialchars($article->title); ?>
                </h1>

                <p class="course-subtitle">
                    <?= htmlspecialchars($fields['hero-subtitle'] ?? ''); ?>
                </p>

            </div>

            <div class="col-lg-6 course-hero-image">

                <?php if (!empty($fields['hero-image'])) : ?>

                    <img
                        src="<?= htmlspecialchars($fields['hero-image']); ?>"
                        class="img-fluid"
                        alt="<?= htmlspecialchars($article->title); ?>">

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>

<section class="course-pricing">

    <div class="container">

        <!-- Pricing cards go here -->

    </div>

</section>

<section class="course-about">

    <div class="container">

        <?= $article->fulltext; ?>

    </div>

</section>

<section class="course-faq">

</section>

<section class="course-cta">

</section>