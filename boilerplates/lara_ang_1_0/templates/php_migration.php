<?php global $table; ?>
<?php echo '<?php'; ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
    * Run the migrations.
    */
    public function up(): void
    {
        Schema::create('<?php echo $table->getPluralName()->getPropertyValue(); ?>', function (Blueprint $table) {
            $table->id();
<?php foreach ($table->getFieldsDifferentFrom($table->getId()->getPropertyValue()) as $field): ?>
<?php if ("string" === $field->getDataType()): ?>
            $table->string('<?php echo $field->getName()->getPropertyValue(); ?>')<?php echo $field->isRequired()?'':'->nullable()'; ?>;
<?php elseif ("number" === $field->getDataType() && !$field->isMultipleSelect()): ?>
            $table->unsignedBigInteger('<?php echo $field->getName()->getPropertyValue(); ?>')<?php echo $field->isRequired()?'':'->nullable()'; ?>;
<?php elseif ("date" === $field->getDataType()) :  ?>
            $table->date('<?php echo $field->getName()->getPropertyValue(); ?>')<?php echo $field->isRequired()?'':'->nullable()'; ?>;
<?php elseif ("datetime" === $field->getDataType()) :  ?>
            $table->dateTime('<?php echo $field->getName()->getPropertyValue(); ?>', 0)<?php echo $field->isRequired()?'':'->nullable()'; ?>;
<?php elseif ("time" === $field->getDataType()) :  ?>
            $table->time('<?php echo $field->getName()->getPropertyValue(); ?>', 0)<?php echo $field->isRequired()?'':'->nullable()'; ?>;
<?php elseif ("boolean" === $field->getDataType()) :  ?>
            $table->boolean('<?php echo $field->getName()->getPropertyValue(); ?>')<?php echo $field->isRequired()?'':'->nullable()'; ?>;
<?php elseif ("text" === $field->getDataType()) :  ?>
            $table->text('<?php echo $field->getName()->getPropertyValue(); ?>')<?php echo $field->isRequired()?'':'->nullable()'; ?>;
<?php elseif ("integer" === $field->getDataType()) :  ?>
            $table->integer('<?php echo $field->getName()->getPropertyValue(); ?>')<?php echo $field->isRequired()?'':'->nullable()'; ?>;
<?php elseif ("float" === $field->getDataType()) :  ?>
            $table->float('<?php echo $field->getName()->getPropertyValue(); ?>')<?php echo $field->isRequired()?'':'->nullable()'; ?>;
<?php endif; ?>
<?php if ($field->getRef() && !$field->isMultipleSelect()): ?>
            $table->foreign('<?php echo $field->getName()->getPropertyValue(); ?>')->references('<?php echo $field->getRef()->getTableRefO()->getId()->getPropertyValue(); ?>')->on('<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue(); ?>')->onUpdate('cascade')->onDelete('cascade');
<?php endif; ?>
<?php endforeach; ?>
            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::dropIfExists('<?php echo $table->getPluralName()->getPropertyValue(); ?>');
    }
};
