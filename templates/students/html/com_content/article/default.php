
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

                <?php if (!empty($fields['program-code'])) : ?>
                    <span class="course-code">
                        <?= htmlspecialchars($fields['program-code']); ?>
                    </span>
                <?php endif; ?>

                <h1 class="course-title">
                    <?= htmlspecialchars($article->title); ?>
                </h1>

                <p class="course-subtitle">
                    <?= nl2br(htmlspecialchars($fields['hero-subtitle'] ?? '')); ?>
                </p>

            </div>

            <div class="col-lg-6">

                <?php if (!empty($fields['hero-image'])) : ?>

                    <div class="course-hero-image">

                        <img
                            src="<?= htmlspecialchars($fields['hero-image']); ?>"
                            alt="<?= htmlspecialchars($article->title); ?>">

                    </div>

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