<?php defined('_JEXEC') or die; ?>

<div class="zetech-numbers-grid">
<?php foreach ($list as $item) : ?>

<?php
$fields = $item->jcfields;
$type = $fields['stat_type']->rawvalue ?? 'number';
?>

<?php if ($type === 'number') : ?>
    <div class="number-box">
        <div class="number"><?php echo $fields['stat_value']->value; ?></div>
        <div class="number-title"><?php echo $fields['stat_title']->value; ?></div>
        <p><?php echo $fields['stat_description']->value; ?></p>
    </div>

<?php else : ?>
    <div class="award-box">
        <span class="award-badge"><?php echo $fields['award_badge']->value; ?></span>
        <h4><?php echo $fields['award_title']->value; ?></h4>
        <p><?php echo $fields['award_description']->value; ?></p>
    </div>
<?php endif; ?>

<?php endforeach; ?>
</div>
