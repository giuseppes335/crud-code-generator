<?php global $table; ?>
<?php echo '<?php'; ?>

namespace App\Http\Controllers;

use App\Models\<?php echo $table->getName()->snakeToCamelU(); ?>;
<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesForImport() as $subtableO): ?>
use App\Models\<?php echo $subtableO->getName()->snakeToCamelU(); ?>;
<?php endforeach; ?>
<?php endif; ?>
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class <?php echo $table->getName()->snakeToCamelU(); ?>Controller extends Controller
{

    public function store(Request $request) {

        $this->validate($request, [
<?php foreach ($table->getFieldsDifferentFrom($table->getId()->getPropertyValue()) as $field): ?>
<?php if ($field->isMultipleSelect()) : ?>
            '<?php echo $field->getName()->getPropertyValue(); ?>' => 'nullable|array',
            '<?php echo $field->getName()->getPropertyValue(); ?>.*' => '<?php echo $field->getBeValidators($table); ?>',
<?php else: ?>
            '<?php echo $field->getName()->getPropertyValue(); ?>' => '<?php echo $field->getBeValidators($table); ?>',
<?php endif; ?>
<?php endforeach; ?>
        ]);

        $<?php echo $table->getName()->snakeToCamel(); ?> = new <?php echo $table->getName()->snakeToCamelU(); ?>;
<?php foreach ($table->getFieldForStore() as $field): ?>
        $<?php echo $table->getName()->snakeToCamel(); ?>-><?php echo $field->getName()->getPropertyValue(); ?> = $request-><?php echo $field->getName()->getPropertyValue(); ?>;
<?php endforeach; ?>
        $<?php echo $table->getName()->snakeToCamel(); ?>->save();

<?php if (count($table->getMultipleSelectField()) > 0) : ?>
<?php foreach ($table->getMultipleSelectField() as $field): ?>
        $<?php echo $table->getName()->snakeToCamel(); ?>-><?php echo $field->getRef()->getMultipleTableRefO()->getPluralName()->snakeToCamel(); ?>()->attach($request-><?php echo $field->getName()->getPropertyValue(); ?>);
<?php endforeach; ?>
<?php endif; ?>

        return $<?php echo $table->getName()->snakeToCamel(); ?>->toArray();
    }

    public function index() {

        $result = [];
        $<?php echo $table->getPluralName()->snakeToCamel(); ?> = <?php echo $table->getName()->snakeToCamelU(); ?>::query();
<?php if (count($table->getMultipleSelectField()) > 0) : ?>
        $<?php echo $table->getPluralName()->snakeToCamel(); ?>->with(<?php echo $table->getEagerLoding(); ?>);
<?php endif; ?>
        $<?php echo $table->getPluralName()->snakeToCamel(); ?> = $<?php echo $table->getPluralName()->snakeToCamel(); ?>->select(DB::raw('<?php echo $table->getSqlSelect(); ?>'));

<?php $index = 0 ?>
<?php foreach ($table->getSelectField() as $field): ?>
<?php if (!$field->isMultipleSelect()) : ?>
        $<?php echo $table->getPluralName()->snakeToCamel(); ?>->leftJoin("<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue(); ?> as <?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue(); ?>_<?php echo $index ?>", "<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue(); ?>_<?php echo $index ?>.<?php echo $field->getRef()->getTableRefO()->getId()->getPropertyValue(); ?>", "=", "<?php echo $table->getPluralName()->getPropertyValue(); ?>.<?php echo $field->getName()->getPropertyValue(); ?>");
<?php $index++; ?>
<?php endif; ?>
<?php endforeach; ?>

        if (request()->has('search')) {
<?php $index = 0 ?>
<?php foreach ($table->getFields() as $field): ?>
<?php if ($field->getRef()): ?>
<?php if (!$field->isMultipleSelect()) : ?>
            $<?php echo $table->getPluralName()->snakeToCamel(); ?>->orWhere('<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue(); ?>_<?php echo $index; ?>.<?php echo $field->getRef()->getLabelRef()->getPropertyValue(); ?>', 'like', '%' . request()->query('search') . '%');
<?php $index++; ?>
<?php endif; ?>
<?php else: ?>
<?php if ("datetime" === $field->getDataType()): ?>
            $<?php echo $table->getPluralName()->snakeToCamel(); ?>->orWhereRaw('DATE_FORMAT(<?php echo $table->getPluralName()->getPropertyValue(); ?>.<?php echo $field->getName()->getPropertyValue(); ?>, "%d/%c/%Y %H:%i") like ?', ["%" . request()->query('search') . "%"]);
<?php elseif ("date" === $field->getDataType()): ?>
            $<?php echo $table->getPluralName()->snakeToCamel(); ?>->orWhereRaw('DATE_FORMAT(<?php echo $table->getPluralName()->getPropertyValue(); ?>.<?php echo $field->getName()->getPropertyValue(); ?>, "%d/%c/%Y") like ?', ["%" . request()->query('search') . "%"]);
<?php elseif ("time" === $field->getDataType()): ?>
            $<?php echo $table->getPluralName()->snakeToCamel(); ?>->orWhereRaw('DATE_FORMAT(<?php echo $table->getPluralName()->getPropertyValue(); ?>.<?php echo $field->getName()->getPropertyValue(); ?>, "%H:%i") like ?', ["%" . request()->query('search') . "%"]);
<?php elseif ("boolean" === $field->getDataType()): ?>
<?php
            $fieldName = $table->getPluralName()->getPropertyValue() . "." . $field->getName()->getPropertyValue();
            $fieldLabel = $field->getLabel();
            $as = $table->getPluralName()->getPropertyValue() . "_" . $field->getName()->getPropertyValue();
            $case = "CASE $fieldName WHEN 1 THEN \'$fieldLabel TRUE\' like ? ELSE \'$fieldLabel FALSE\' like ? END";
?>
            $<?php echo $table->getPluralName()->snakeToCamel(); ?>->orWhereRaw('<?php echo $case; ?>', ["%" . request()->query('search') . "%", "%" . request()->query('search') . "%"]);

<?php else: ?>
            $<?php echo $table->getPluralName()->snakeToCamel(); ?>->orWhere('<?php echo $table->getPluralName()->getPropertyValue(); ?>.<?php echo $field->getName()->getPropertyValue(); ?>', 'like', '%' . request()->query('search') . '%');
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>

<?php if (count($table->getMultipleSelectField()) > 0) : ?>
<?php foreach ($table->getMultipleSelectField() as $field): ?>
            $<?php echo $table->getPluralName()->snakeToCamel(); ?>->orWhereHas('<?php echo $field->getRef()->getMultipleTableRefO()->getPluralName()->snakeToCamel(); ?>', function($query) {
                $query->where('<?php echo $field->getRef()->getLabelRef()->getPropertyValue(); ?>', 'like', '%' . request()->query('search') . '%');
            });
<?php endforeach; ?>
<?php endif; ?>
        }

        if (request()->has('sort')) {
            if (request()->has('sortDir')) {
                $<?php echo $table->getPluralName()->snakeToCamel(); ?>->orderBy(request()->sort, request()->sortDir);
            } else {
                $<?php echo $table->getPluralName()->snakeToCamel(); ?>->orderBy(request()->sort);
            }
        }

        if (request()->has('page')) {
            $result = $<?php echo $table->getPluralName()->snakeToCamel(); ?>->paginate(10);
        } else {
            $result = $<?php echo $table->getPluralName()->snakeToCamel(); ?>->get();
        }

        return $result;
    }

    public function get($id) {
        return <?php echo $table->getName()->snakeToCamelU(); ?>::find($id);
    }

    public function update($id, Request $request) {

        $this->validate($request, [
<?php foreach ($table->getFieldsDifferentFrom($table->getId()->getPropertyValue()) as $field): ?>
<?php if ($field->isMultipleSelect()) : ?>
            '<?php echo $field->getName()->getPropertyValue(); ?>' => 'nullable|array',
            '<?php echo $field->getName()->getPropertyValue(); ?>.*' => '<?php echo $field->getBeValidators($table, '$id'); ?>',
<?php else: ?>
            '<?php echo $field->getName()->getPropertyValue(); ?>' => '<?php echo $field->getBeValidators($table, '$id'); ?>',
<?php endif; ?>
<?php endforeach; ?>
        ]);

        $<?php echo $table->getName()->snakeToCamel(); ?> = <?php echo $table->getName()->snakeToCamelU(); ?>::find($id);
<?php foreach ($table->getFieldForStore() as $field): ?>
        $<?php echo $table->getName()->snakeToCamel(); ?>-><?php echo $field->getName()->getPropertyValue(); ?> = $request-><?php echo $field->getName()->getPropertyValue(); ?>;
<?php endforeach; ?>
        $<?php echo $table->getName()->snakeToCamel(); ?>->save();


<?php if (count($table->getMultipleSelectField()) > 0) : ?>
<?php foreach ($table->getMultipleSelectField() as $index => $field): ?>
        $<?php echo $table->getName()->snakeToCamel(); ?>-><?php echo $field->getRef()->getMultipleTableRefO()->getPluralName()->snakeToCamel(); ?>()->sync($request-><?php echo $field->getName()->getPropertyValue(); ?>);
<?php endforeach; ?>
<?php endif; ?>


<?php if ($table->hasSubtables()): ?>
        $lastInsertedIds = [];

<?php foreach ($table->getSubtablesO() as $subtableO): ?>
        if (!isset($lastInsertedIds['book'])) {
            $lastInsertedIds['<?php echo $subtableO->getName()->snakeToDash(); ?>'] = [];
        }
        foreach($request-><?php echo $subtableO->getAlias()->getPropertyValue(); ?> as $r<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>) {
            if (isset($r<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>["<?php echo $subtableO->getId()->getPropertyValue(); ?>"])) {

                $validator = Validator::make($r<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>, [
<?php foreach ($subtableO->getFieldsDifferentFrom($subtableO->getId()->getPropertyValue()) as $field): ?>
<?php if ($field->isMultipleSelect()) : ?>
                    '<?php echo $field->getName()->getPropertyValue(); ?>' => 'array',
                    '<?php echo $field->getName()->getPropertyValue(); ?>.*' => '<?php echo $field->getBeValidators($subtableO, '$r' . $subtableO->getAlias()->snakeToCamelU() .'["' . $subtableO->getId()->getPropertyValue() . '"]'); ?>',
<?php else: ?>
                    '<?php echo $field->getName()->getPropertyValue(); ?>' => '<?php echo $field->getBeValidators($subtableO, '$r' . $subtableO->getAlias()->snakeToCamelU() .'["' . $subtableO->getId()->getPropertyValue() . '"]'); ?>',
<?php endif; ?>
<?php endforeach; ?>
                ]);
                $validator->validate();

                $<?php echo $subtableO->getAlias()->snakeToCamel(); ?> = <?php echo $subtableO->getName()->snakeToCamelU(); ?>::find($r<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>["<?php echo $subtableO->getId()->getPropertyValue(); ?>"]);
                if ($<?php echo $subtableO->getAlias()->snakeToCamel(); ?>) {
<?php foreach ($subtableO->getFieldForStore() as $field): ?>
                    $<?php echo $subtableO->getAlias()->snakeToCamel(); ?>-><?php echo $field->getName()->getPropertyValue(); ?> = $r<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>["<?php echo $field->getName()->getPropertyValue(); ?>"];
<?php endforeach; ?>
                    $<?php echo $subtableO->getAlias()->snakeToCamel(); ?>->save();

<?php if (count($subtableO->getMultipleSelectField()) > 0) : ?>
<?php foreach ($subtableO->getMultipleSelectField() as $field): ?>
                    $<?php echo $subtableO->getAlias()->snakeToCamel(); ?>-><?php echo $field->getRef()->getMultipleTableRefO()->getPluralName()->snakeToCamel(); ?>()->sync($r<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>["<?php echo $field->getName()->getPropertyValue(); ?>"]);
<?php endforeach; ?>
<?php endif; ?>
                }
            } else {
                $<?php echo $subtableO->getAlias()->snakeToCamel(); ?> = new <?php echo $subtableO->getName()->snakeToCamelU(); ?>;
<?php foreach ($subtableO->getFieldForStore() as $field): ?>
                $<?php echo $subtableO->getAlias()->snakeToCamel(); ?>-><?php echo $field->getName()->getPropertyValue(); ?> = $r<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>["<?php echo $field->getName()->getPropertyValue(); ?>"];
<?php endforeach; ?>
                $<?php echo $subtableO->getAlias()->snakeToCamel(); ?>->save();

<?php if (count($subtableO->getMultipleSelectField()) > 0) : ?>
<?php foreach ($subtableO->getMultipleSelectField() as $field): ?>
                $<?php echo $subtableO->getAlias()->snakeToCamel(); ?>-><?php echo $field->getRef()->getMultipleTableRefO()->getPluralName()->snakeToCamel(); ?>()->sync($r<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>["<?php echo $field->getName()->getPropertyValue(); ?>"]);
<?php endforeach; ?>
<?php endif; ?>

                array_push($lastInsertedIds['<?php echo $subtableO->getName()->snakeToDash(); ?>'], $<?php echo $subtableO->getAlias()->snakeToCamel(); ?>-><?php echo $subtableO->getId()->getPropertyValue(); ?>);
            }

        }

        $<?php echo $subtableO->getPluralName()->snakeToCamel(); ?> = <?php echo $subtableO->getName()->snakeToCamelU(); ?>::where('<?php echo $subtableO->getInverseRef()->getPropertyValue(); ?>', '=', $id)->get();
        foreach($<?php echo $subtableO->getPluralName()->snakeToCamel(); ?> as $<?php echo $subtableO->getAlias()->snakeToCamel(); ?>) {
            if (!in_array($<?php echo $subtableO->getAlias()->snakeToCamel(); ?>-><?php echo $subtableO->getId()->getPropertyValue(); ?>, $lastInsertedIds['<?php echo $subtableO->getName()->snakeToDash(); ?>']) && !in_array($<?php echo $subtableO->getAlias()->snakeToCamel(); ?>-><?php echo $subtableO->getId()->getPropertyValue(); ?>, array_column($request-><?php echo $subtableO->getAlias()->getPropertyValue(); ?>, "<?php echo $subtableO->getId()->getPropertyValue(); ?>"))) {
                $<?php echo $subtableO->getAlias()->snakeToCamel(); ?>->delete();
            }
        }
<?php endforeach; ?>
<?php endif; ?>

        return $<?php echo $table->getName()->snakeToCamel();?>->toArray();

    }


<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
    public function get<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>Of<?php echo $table->getName()->snakeToCamelU(); ?>($id) {
        return <?php echo $subtableO->getName()->snakeToCamelU(); ?>::where('<?php echo $subtableO->getInverseRef()->getPropertyValue(); ?>', '=', $id)->get();
    }
<?php endforeach; ?>
<?php endif; ?>


    public function notUnique($field, $value) {
        $<?php echo $table->getName()->snakeToCamel(); ?>Q = <?php echo $table->getName()->snakeToCamelU(); ?>::where($field, "=", $value);

        if (request()->has('fieldId') && request()->has('excludingId')) {
            $<?php echo $table->getName()->snakeToCamel(); ?>Q->whereNotIn(request()->fieldId, [request()->excludingId]);
        }
        $<?php echo $table->getName()->snakeToCamel(); ?> = $<?php echo $table->getName()->snakeToCamel(); ?>Q->first();

        return $<?php echo $table->getName()->snakeToCamel(); ?>?json_encode($<?php echo $table->getName()->snakeToCamel(); ?>->toArray()):json_encode(null);
    }

    public function delete($id) {

        $<?php echo $table->getName()->snakeToCamel(); ?> = <?php echo $table->getName()->snakeToCamelU(); ?>::find($id);
        if ($<?php echo $table->getName()->snakeToCamel(); ?>) {
            $<?php echo $table->getName()->snakeToCamel(); ?>->delete();
        }
        return [];

    }

}
