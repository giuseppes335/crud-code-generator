<?php global $table; ?>

<h1><?php echo $table->getHtmlFormLabel(); ?></h1>
<form [formGroup]="<?php echo $table->getName()->snakeToCamel(); ?>Form" (ngSubmit)="onSubmit()">

    <div style="max-width: 600px">
        <table class="my-table-layout">
<?php foreach ($table->getFields() as $field): ?>
            <tr>
<?php if (!$field->getRef()): ?>
                <td>
                    <label for="<?php echo $field->getName()->getPropertyValue(); ?>" class="form-label"><?php echo $field->getLabel(); ?></label>
                </td>
                <td>
<?php if ($table->getId()->getPropertyValue() !== $field->getName()->getPropertyValue()): ?>
<?php if ("text" === $field->getHtmlType() || "date" === $field->getHtmlType() || "time" === $field->getHtmlType() || "datetime-local" === $field->getHtmlType()): ?>
                    <input id="<?php echo $field->getName()->getPropertyValue(); ?>" type="<?php echo $field->getHtmlType(); ?>" [formControl]="<?php echo $field->getName()->snakeToCamel(); ?>Control" class="form-control">
<?php elseif ("textarea" === $field->getHtmlType()): ?>
                    <textarea id="<?php echo $field->getName()->getPropertyValue(); ?>" [formControl]="<?php echo $field->getName()->snakeToCamel(); ?>Control"  class="form-control"></textarea>
<?php elseif ("checkbox" === $field->getHtmlType()): ?>
                    <input class="form-check-input" type="checkbox" [formControl]="<?php echo $field->getName()->snakeToCamel(); ?>Control" id="<?php echo $field->getName()->getPropertyValue(); ?>">
<?php endif; ?>

<?php else: ?>
                    <input id="<?php echo $field->getName()->getPropertyValue(); ?>" type="<?php echo $field->getHtmlType(); ?>" [formControl]="<?php echo $field->getName()->snakeToCamel(); ?>Control" class="form-control" readonly>
<?php endif; ?>
                </td>
                <td>
                    <app-validation [formControl0]="getFormControl(<?php echo $table->getName()->snakeToCamel(); ?>Form, '<?php echo $field->getName()->getPropertyValue(); ?>')" [validators]="[<?php echo implode(',', $field->getValidatorsHtml()); ?>]"></app-validation>
                </td>
<?php else: ?>
                <td>
                    <label for="<?php echo $field->getName()->getPropertyValue(); ?>" class="form-label"><?php echo $field->getLabel(); ?></label>
                </td>
                <td>
                    <select id="<?php echo $field->getName()->getPropertyValue(); ?>" [formControl]="<?php echo $field->getName()->snakeToCamel(); ?>Control" class="form-select" <?php echo ($field->getRef()->getMultipleTableRef())?"multiple":""; ?>>
                        <option></option>
                        <option *ngFor="let <?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Row of <?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>Rows; let i = index" value="{{ <?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Row['<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue() . "_" . $field->getRef()->getTableRefO()->getId()->getPropertyValue(); ?>'] }}">{{ <?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Row['<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue() . "_" . $field->getRef()->getLabelRef()->getPropertyValue(); ?>'] }}</option>
                    </select>
                </td>
                <td>
                    <app-validation [formControl0]="getFormControl(<?php echo $table->getName()->snakeToCamel(); ?>Form, '<?php echo $field->getName()->getPropertyValue(); ?>')" [validators]="[<?php echo implode(',', $field->getValidatorsHtml()); ?>]"></app-validation>
                </td>
<?php endif; ?>
            </tr>
<?php endforeach; ?>
        </table>
        <div class="text-end">
            <button *ngIf="<?php echo $table->getId()->snakeToCamel(); ?>Control.value" type="button" class="btn btn-success ms-2" (click)="back('/<?php echo $table->getPluralName()->snakeToDash(); ?>/<?php echo $table->getName()->snakeToDash(); ?>-form')">New</button>
            <button *ngIf="<?php echo $table->getId()->snakeToCamel(); ?>Control.value" type="button" class="btn btn-danger ms-2" (click)="delete(<?php echo $table->getId()->snakeToCamel(); ?>Control.value)">Delete</button>
            <button type="button" class="btn btn-secondary ms-2" (click)="back('/<?php echo $table->getPluralName()->snakeToDash(); ?>/<?php echo $table->getName()->snakeToDash(); ?>-table')">Back</button>
            <input type="submit" class="btn btn-primary ms-2">
        </div>
    </div>
</form>




<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableIndex => $subtableO): ?>
<h3 class="mt-5"><?php echo $subtableO->getAlias()->snakeToLabel(); ?></h3>
<div style="display: flex; justify-content: center">
<div class="spinner-border align-self-center" style="width: 5rem; height: 5rem;" role="status" *ngIf="loading<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>">
    <span class="visually-hidden">Loading...</span>
</div>
<div class="align-self-start w-100" *ngIf="!loading<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>">
<table class="my-table-layout">
    <tr>
        <td *ngFor="let label of ['<?php echo implode('\', \'', $subtableO->getFieldsLabel($table)); ?>']">
            {{ label }}
        </td>
        <td>
        </td>
    </tr>
    <tr *ngFor="let fg of <?php echo $subtableO->getAlias()->snakeToCamel(); ?>FormArray.controls; let i = index">
<?php foreach ($subtableO->getFields() as $fieldIndex => $field): ?>
<?php if (!$field->getRef() || ($field->getRef() && $field->getRef()->getTableRef()->getPropertyValue() !== $table->getName()->getPropertyValue())): ?>
        <td class="align-middle">
<?php if (!$field->getRef()): ?>
<?php if ($table->getId()->getPropertyValue() !== $field->getName()->getPropertyValue()): ?>
<?php if ("text" === $field->getHtmlType() || "date" === $field->getHtmlType() || "time" === $field->getHtmlType() || "datetime-local" === $field->getHtmlType()): ?>
            <input id="<?php echo $field->getName()->getPropertyValue(); ?>" type="<?php echo $field->getHtmlType(); ?>" [formControl]="getFormControl(fg, '<?php echo $field->getName()->getPropertyValue(); ?>')" class="form-control">
<?php elseif ("textarea" === $field->getHtmlType()): ?>
            <textarea id="<?php echo $field->getName()->getPropertyValue(); ?>" [formControl]="getFormControl(fg, '<?php echo $field->getName()->getPropertyValue(); ?>')"  class="form-control"></textarea>
<?php elseif ("checkbox" === $field->getHtmlType()): ?>
            <input class="form-check-input" type="checkbox" [formControl]="getFormControl(fg, '<?php echo $field->getName()->getPropertyValue(); ?>')" id="<?php echo $field->getName()->getPropertyValue(); ?>">
<?php endif; ?>
<?php else: ?>
            <input id="<?php echo $field->getName()->getPropertyValue(); ?>" type="<?php echo $field->getHtmlType(); ?>" [formControl]="getFormControl(fg, '<?php echo $field->getName()->getPropertyValue(); ?>')" class="form-control" readonly>
<?php endif; ?>
            <app-validation [formControl0]="getFormControl(fg, '<?php echo $field->getName()->getPropertyValue(); ?>')" [validators]="[<?php echo implode(',', $field->getValidatorsHtml()); ?>]"></app-validation>
<?php else: ?>
            <select id="<?php echo $field->getName()->getPropertyValue(); ?>" [formControl]="getFormControl(fg, '<?php echo $field->getName()->getPropertyValue(); ?>')" class="form-select" <?php echo ($field->getRef()->getMultipleTableRef())?"multiple":""; ?>>
                <option></option>
                <option *ngFor="let <?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Row of <?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>Rows" value="{{ <?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Row['<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue() . "_" . $field->getRef()->getTableRefO()->getId()->getPropertyValue(); ?>'] }}">{{ <?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Row['<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue() . "_" . $field->getRef()->getLabelRef()->getPropertyValue(); ?>'] }}</option>
            </select>
            <app-validation [formControl0]="getFormControl(fg, '<?php echo $field->getName()->getPropertyValue(); ?>')" [validators]="[<?php echo implode(',', $field->getValidatorsHtml()); ?>]"></app-validation>
<?php endif; ?>
        </td>
<?php endif; ?>
<?php endforeach; ?>
        <td><button type="button" class="btn btn-danger" (click)="remove<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>Row(i)">Remove</button></td>
    </tr>
    <tr>
<?php foreach ($subtableO->getFields() as $field): ?>
            <?php if (!$field->getRef() || ($field->getRef() && $field->getRef()->getTableRef()->getPropertyValue() !== $table->getName()->getPropertyValue())): ?>
        <td>
<?php if (!$field->getRef()): ?>
<?php if ("text" === $field->getHtmlType() || "date" === $field->getHtmlType() || "time" === $field->getHtmlType() || "datetime-local" === $field->getHtmlType()): ?>
        <input id="<?php echo $field->getName()->getPropertyValue(); ?>" type="<?php echo $field->getHtmlType(); ?>" class="form-control" disabled>
<?php elseif ("textarea" === $field->getHtmlType()): ?>
        <textarea id="<?php echo $field->getName()->getPropertyValue(); ?>" class="form-control" disabled></textarea>
<?php elseif ("checkbox" === $field->getHtmlType()): ?>
        <input class="form-check-input" type="checkbox"  id="<?php echo $field->getName()->getPropertyValue(); ?>" disabled>
<?php endif; ?>

<?php else: ?>
            <select id="<?php echo $field->getName()->getPropertyValue(); ?>"  class="form-select" disabled <?php echo ($field->getRef()->getMultipleTableRef())?"multiple":""; ?>>
                <option></option>
            </select>
<?php endif; ?>
        </td>
<?php endif; ?>
<?php endforeach; ?>
        <td>
            <button type="button" class="btn btn-primary" (click)="add<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>Row()">Add</button>
        </td>
    </tr>
</table>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>