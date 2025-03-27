<?php global $table; ?>
<h1><?php echo $table->getHtmlListLabel(); ?></h1>

<app-sort
  [fields]="[
<?php $index = 0; ?>
<?php foreach ($table->getFields() as $field): ?>
<?php if (!$field->getRef()): ?>
      {
        optionValue: '<?php echo $table->getPluralName()->getPropertyValue() . "_" . $field->getName()->getPropertyValue(); ?>',
        optionHtml: '<?php echo $field->getLabel(); ?>'
      },
<?php else: ?>
<?php if (!$field->isMultipleSelect()): ?>
      {
        optionValue: '<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue() . "_" . $index . "_" . $field->getRef()->getLabelRef()->getPropertyValue() . "_" . $index; ?>',
        optionHtml: '<?php echo $field->getLabel(); ?>'
      },
<?php $index++; ?>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
  ]"
  (sortChange)="applySort($event)"
></app-sort>


<app-search
        (searchStringChange)="applySearch($event)"
></app-search>

<div style="height: 100%; display: flex; justify-content: center">
<div class="spinner-border align-self-center" style="width: 5rem; height: 5rem;" role="status" *ngIf="loading">
    <span class="visually-hidden">Loading...</span>
</div>

<div class="align-self-start w-100" *ngIf="!loading">
<table class="table my-list-table">
    <thead>
        <tr>
<?php foreach ($table->getFields() as $field): ?>
            <th><?php echo $field->getLabel() ?></th>
<?php endforeach; ?>
            <th></th>
        </tr>
    </thead>

    <tbody>
    <tr *ngFor="let <?php echo $table->getName()->snakeToCamel(); ?> of <?php echo $table->getPluralName()->snakeToCamel(); ?>">

<?php $index = 0; ?>
<?php foreach ($table->getFields() as $field): ?>
<?php if(!$field->getRef()): ?>
        <td>{{ <?php echo $table->getName()->snakeToCamel(); ?>.<?php echo $table->getPluralName()->getPropertyValue(); ?>_<?php echo $field->getName()->getPropertyValue() ?> }}</td>
<?php else: ?>
<?php if (!$field->isMultipleSelect()) : ?>
        <td>{{ <?php echo $table->getName()->snakeToCamel();  ?>.<?php echo $field->getRef()->getTableRefO()->getPluralName()->getPropertyValue(); ?>_<?php echo $index; ?>_<?php echo $field->getRef()->getLabelRef()->getPropertyValue() ?>_<?php echo $index; ?> }}</td>
<?php $index++; ?>
<?php else: ?>
        <td>
            <span class="badge bg-secondary me-1" *ngFor="let object of  <?php echo $table->getName()->snakeToCamel();  ?>.<?php echo $field->getRef()->getMultipleTableRefO()->getPluralName()->getPropertyValue(); ?>">
                {{ object.<?php echo $field->getRef()->getLabelRef()->getPropertyValue(); ?> }}
            </span>
        </td>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
        <td>
            <select id="action" name="action" class="form-select" style="width: 200px" [(ngModel)]="action" (change)="go(action)">
                <option value="default">Choose an action ...</option>
                <option value="/<?php echo $table->getPluralName()->snakeToDash(); ?>/<?php echo $table->getName()->snakeToDash(); ?>-form/{{ <?php echo $table->getName()->snakeToCamel(); ?>.<?php echo $table->getPluralName()->getPropertyValue(); ?>_<?php echo $table->getId()->getPropertyValue(); ?> }}">Edit row</option>
            </select>
        </td>
    </tr>
    </tbody>
</table>
<app-pagination
        [totalPages]="totalPages"
        (pageChange)="applyPagination($event)"
></app-pagination>
</div>

</div>



