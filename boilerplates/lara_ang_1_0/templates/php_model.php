<?php global $table; ?>
<?php echo '<?php'; ?>

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<?php if (count($table->getMultipleSelectField()) > 0) : ?>
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
<?php endif; ?>

class <?php echo $table->getName()->snakeToCamelU(); ?> extends Model {

    protected $table = '<?php echo $table->getPluralName()->getPropertyValue(); ?>';

    protected $appends = <?php echo $table->getAppends(); ?>;

<?php foreach ($table->getMultipleSelectField() as $field) : ?>
    public function <?php echo $field->getRef()->getMultipleTableRefO()->getPluralName()->snakeToCamel(); ?>(): BelongsToMany
    {
        return $this->belongsToMany(<?php echo $field->getRef()->getTableRef()->snakeToCamelU(); ?>::class, '<?php echo $field->getRef()->getMultipleTableRefO()->getPluralName()->getPropertyValue(); ?>', '<?php echo $field->getRef()->getMultipleRefId($table->getName()->getPropertyValue())->getPropertyValue(); ?>', '<?php echo $field->getName()->getPropertyValue(); ?>');
    }
<?php endforeach; ?>


<?php foreach ($table->getMultipleSelectField() as $field) : ?>
    public function get<?php echo $field->getName()->snakeToCamelU(); ?>Attribute() {
        return array_map(function($id) {
            return strval($id);
        }, $this-><?php echo $field->getRef()->getMultipleTableRefO()->getPluralName()->snakeToCamel(); ?>->pluck('<?php echo $field->getRef()->getTableRefO()->getId()->getPropertyValue(); ?>')->toArray());
    }
<?php endforeach; ?>


<?php foreach ($table->getFields() as $field) : ?>
<?php if ("datetime" === $field->getDataType()): ?>
    public function get<?php echo $field->getName()->snakeToCamelU(); ?>Attribute() {
        $<?php echo $field->getName()->snakeToCamel(); ?> = date_create($this->attributes['<?php echo $field->getName()->getPropertyValue(); ?>']);
        return date_format($<?php echo $field->getName()->snakeToCamel(); ?>, "Y-m-d\TH:i");
    }
<?php elseif ("time" === $field->getDataType()) : ?>
    public function get<?php echo $field->getName()->snakeToCamelU(); ?>Attribute() {
        $<?php echo $field->getName()->snakeToCamel(); ?> = date_create($this->attributes['<?php echo $field->getName()->getPropertyValue(); ?>']);
        return date_format($<?php echo $field->getName()->snakeToCamel(); ?>, "H:i");
    }
<?php endif; ?>
<?php endforeach; ?>


}
