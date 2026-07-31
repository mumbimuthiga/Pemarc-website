<?php
defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;

if (!$list) {
    return;
}
?>

<?php foreach ($list as $index => $item) : ?>

    <?php
        $images = json_decode($item->images ?? '{}');

        // Resolve image (intro → full → fallback)
        $image = '';

        if (!empty($images->image_intro)) {
            $image = $images->image_intro;
        } elseif (!empty($images->image_fulltext)) {
            $image = $images->image_fulltext;
        }

        // Make sure path is absolute
        if ($image && strpos($image, 'http') !== 0) {
            $image = Uri::root() . ltrim($image, '/');
        }
    ?>

    <?php if ($index === 0) : ?>
        <!-- FEATURED STORY -->
        <div class="col-lg-6">
            <article class="student-life-feature">
                <a href="<?php echo $item->link; ?>">
                    <div class="image-wrapper">

                        <?php if ($image) : ?>
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($item->title); ?>">
                        <?php endif; ?>

                        <div class="overlay"></div>

                        <div class="content">
                            <h3><?php echo htmlspecialchars($item->title); ?></h3>
                            <p><?php echo strip_tags($item->introtext); ?></p>
                        </div>
                    </div>
                </a>
            </article>
        </div>

        <div class="col-lg-6">
            <div class="row g-4">

    <?php else : ?>
                <!-- SUPPORTING STORIES -->
                <div class="col-md-6">
                    <article class="student-life-card">
                        <a href="<?php echo $item->link; ?>">

                            <?php if ($image) : ?>
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($item->title); ?>">
                            <?php endif; ?>

                            <div class="card-body">
                                <h5><?php echo htmlspecialchars($item->title); ?></h5>
                            </div>
                        </a>
                    </article>
                </div>
    <?php endif; ?>

<?php endforeach; ?>

            </div>
        </div>
