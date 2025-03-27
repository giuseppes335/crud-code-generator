<?php global $table; ?>
export interface <?php echo $table->getName()->snakeToCamelU(); ?> {
<?php foreach ($table->getFields() as $field): ?>
    <?php echo $field->getName()->getPropertyValue(); ?>: string,
<?php endforeach; ?>
<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
    <?php echo $subtableO->getAlias()->getPropertyValue(); ?>: [],
<?php endforeach; ?>
<?php endif; ?>
}