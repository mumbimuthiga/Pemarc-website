<?php
// modules/mod_articles_category/tmpl/custom-carousel.php
foreach ($list as $i => $item) :
    $active = ($i == 0) ? 'active' : '';
?>
<div class="carousel-item <?php echo $active; ?>">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="testimonial-card">
                <?php if ($item->images) : ?>
                    <?php $images = json_decode($item->images); ?>
                    <?php if (!empty($images->image_intro)) : ?>
                        <img src="<?php echo $images->image_intro; ?>" alt="<?php echo $item->title; ?>" class="rounded-circle" width="100" height="100">
                    <?php endif; ?>
                <?php endif; ?>
                <span class="quote-mark">“</span>
                <p class="testimonial-text"><?php echo $item->introtext; ?></p>
                <div class="testimonial-meta">
                    <strong><?php echo $item->title; ?></strong>
                    <?php if (!empty($item->attribs->program)) : ?>
                        <span><?php echo $item->attribs->program; ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
