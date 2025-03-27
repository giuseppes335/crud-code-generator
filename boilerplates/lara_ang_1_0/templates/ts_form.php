<?php global $table; ?>
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators, FormBuilder, FormArray } from "@angular/forms";
import { <?php echo $table->getName()->snakeToCamelU(); ?>Service } from "../<?php echo $table->getName()->snakeToDash(); ?>.service";
<?php foreach ($table->getTablesForFeServicesImport() as $table2): ?>
import { <?php echo $table2->getName()->snakeToCamelU(); ?>Service } from "../../<?php echo $table2->getName()->snakeToDash(); ?>/<?php echo $table2->getName()->snakeToDash(); ?>.service";
<?php endforeach; ?>
import { <?php echo $table->getName()->snakeToCamelU(); ?> } from "../<?php echo $table->getName()->snakeToDash(); ?>";
<?php foreach ($table->getTablesForFeServicesImport() as $table2): ?>
import {<?php echo $table2->getName()->snakeToCamelU(); ?>} from "../../<?php echo $table2->getName()->snakeToDash(); ?>/<?php echo $table2->getName()->snakeToDash(); ?>";
<?php endforeach; ?>
import { ActivatedRoute } from "@angular/router";
import { map } from "rxjs";
import { fe_path } from "../../../configuration";
import { MyValidators } from "../../../my-validators";

@Component({
selector: 'app-<?php echo $table->getName()->snakeToDash(); ?>-form',
templateUrl: './<?php echo $table->getName()->snakeToDash(); ?>-form.component.html',
styleUrls: ['./<?php echo $table->getName()->snakeToDash(); ?>-form.component.css']
})
export class <?php echo $table->getName()->snakeToCamelU(); ?>FormComponent implements OnInit {

    decimalRegex = /^-?\d{0,3}([.]\d{0,5})?$/;

    integerRegex = /^\d{1,10}$/;

<?php foreach ($table->getFields() as $field): ?>
    <?php echo $field->getName()->snakeToCamel(); ?>Control: FormControl = new FormControl(null, [<?php echo implode(',', $field->getFeValidators()); ?>]);
<?php endforeach; ?>

<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
    <?php echo $subtableO->getAlias()->snakeToCamel(); ?>FormArray: FormArray = this.fb.array([]);
<?php endforeach; ?>
<?php endif; ?>

<?php echo $table->getName()->snakeToCamel(); ?>Form = new FormGroup({
<?php foreach ($table->getFields() as $field): ?>
        <?php echo $field->getName()->getPropertyValue(); ?>: this.<?php echo $field->getName()->snakeToCamel(); ?>Control,
<?php endforeach; ?>
<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
        <?php echo $subtableO->getAlias()->getPropertyValue(); ?>: this.<?php echo $subtableO->getAlias()->snakeToCamel(); ?>FormArray,
<?php endforeach; ?>
<?php endif; ?>
    });

<?php foreach ($table->getTablesForFeArrayDefinitions() as $table2): ?>
    <?php echo $table2->getPluralName()->snakeToCamel(); ?>Rows!: any[];
<?php endforeach; ?>

<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
    loading<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>: boolean = false;
<?php endforeach; ?>
<?php endif; ?>

    constructor(
        private <?php echo $table->getName()->snakeToCamel(); ?>Service: <?php echo $table->getName()->snakeToCamelU(); ?>Service,
<?php foreach ($table->getTablesForFeServicesImport() as $table2): ?>
        private <?php echo $table2->getName()->snakeToCamel(); ?>Service: <?php echo $table2->getName()->snakeToCamelU(); ?>Service,
<?php endforeach; ?>
        private activatedRoute: ActivatedRoute,
        private fb: FormBuilder
    ) {}

    ngOnInit() {

        this.activatedRoute.paramMap.subscribe((params) => {
            if (params.get("id")) {
                this.<?php echo $table->getName()->snakeToCamel();  ?>Service.get<?php echo $table->getName()->snakeToCamelU(); ?>(params.get("id")).subscribe((<?php echo $table->getName()->snakeToCamel(); ?>: <?php echo $table->getName()->snakeToCamelU(); ?>) => {
<?php foreach ($table->getFields() as $field): ?>
                    this.<?php echo $field->getName()->snakeToCamel(); ?>Control.setValue(<?php echo $table->getName()->snakeToCamel(); ?>.<?php echo $field->getName()->getPropertyValue(); ?><?php echo $field->isBoolean()?'?true:false':''; ?>);
<?php endforeach; ?>
                });

<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
                this.loading<?php echo $subtableO->getAlias()->snakeToCamelU(); ?> = true;
                this.<?php echo $table->getName()->snakeToCamel(); ?>Service.get<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>Of<?php echo $table->getPluralName()->snakeToCamelU(); ?>(params.get("id")).subscribe((<?php echo $subtableO->getPluralName()->snakeToCamel(); ?>: <?php echo $subtableO->getName()->snakeToCamelU(); ?>[]) => {
                    <?php echo $subtableO->getPluralName()->snakeToCamel(); ?>.forEach((<?php echo $subtableO->getName()->snakeToCamel(); ?>: <?php echo $subtableO->getName()->snakeToCamelU(); ?>, index: number) => {
                        this.<?php echo $subtableO->getAlias()->snakeToCamel(); ?>FormArray.push(this.fb.group({
<?php foreach ($subtableO->getFields() as $field): ?>
                        <?php echo $field->getName()->getPropertyValue(); ?>: new FormControl(<?php echo $subtableO->getName()->snakeToCamel(); ?>.<?php echo $field->getName()->getPropertyValue(); ?><?php echo $field->isBoolean()?'?true:false':''; ?>, [<?php echo implode(',', $field->getFeValidators()); ?>], <?php echo $field->getFeAsyncValidators2($subtableO); ?>),
<?php endforeach; ?>
                        }));

                    });
                    this.loading<?php echo $subtableO->getAlias()->snakeToCamelU(); ?> = false
                });
<?php endforeach; ?>
<?php endif; ?>
            }
        })

<?php foreach ($table->getTablesForFeArrayDefinitions() as $table2): ?>
        this.<?php echo $table2->getName()->snakeToCamel(); ?>Service.get<?php echo $table2->getPluralName()->snakeToCamelU(); ?>(null, null, null).subscribe((<?php echo $table2->getPluralName()->snakeToCamel(); ?>: <?php echo $table2->getName()->snakeToCamelU(); ?>[]) => {
            this.<?php echo $table2->getPluralName()->snakeToCamel(); ?>Rows = <?php echo $table2->getPluralName()->snakeToCamel(); ?>;
        })
<?php endforeach; ?>


<?php if (count($table->getFieldsUnique()) > 0):?>
        this.activatedRoute.paramMap.subscribe((params) => {
            if (params.get("id")) {
<?php foreach ($table->getFieldsUnique() as $field): ?>
                this.<?php echo $field->getName()->snakeToCamel(); ?>Control.addAsyncValidators(MyValidators.notUnique(this.<?php echo $table->getName()->snakeToCamel(); ?>Service, '<?php echo $field->getName()->getPropertyValue(); ?>', '<?php echo $table->getId()->getPropertyValue(); ?>', params.get("id")));
<?php endforeach; ?>
            } else {
<?php foreach ($table->getFieldsUnique() as $field): ?>
                this.<?php echo $field->getName()->snakeToCamel();?>Control.addAsyncValidators(MyValidators.notUnique(this.<?php echo $table->getName()->snakeToCamel(); ?>Service, '<?php echo $field->getName()->getPropertyValue(); ?>'));
<?php endforeach; ?>
            }
        });
<?php endif; ?>

    }

    getFormControl(fg: any, controlName: string): any {
        return fg.get(controlName);
    }

    onSubmit() {
        this.<?php echo $table->getName()->snakeToCamel(); ?>Form.markAllAsTouched();
        if (this.<?php echo $table->getName()->snakeToCamel(); ?>Form.valid) {
            let <?php echo $table->getName()->snakeToCamel(); ?>: <?php echo $table->getName()->snakeToCamelU(); ?> = {
<?php foreach ($table->getFields() as $field): ?>
                <?php echo $field->getName()->getPropertyValue(); ?>: this.<?php echo $table->getName()->snakeToCamel(); ?>Form.value.<?php echo $field->getName()->getPropertyValue(); ?>!,
<?php endforeach; ?>
<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
                <?php echo $subtableO->getAlias()->getPropertyValue(); ?>: this.<?php echo $table->getName()->snakeToCamel(); ?>Form.value.<?php echo $subtableO->getAlias()->getPropertyValue(); ?>!,
<?php endforeach; ?>
<?php endif; ?>
            }



            this.activatedRoute.paramMap.subscribe((params) => {
                if (params.get("id")) {
                    this.<?php echo $table->getName()->snakeToCamel(); ?>Service.update<?php echo $table->getName()->snakeToCamelU(); ?>(params.get("id"), <?php echo $table->getName()->snakeToCamel(); ?>).
                        pipe(
                            map((<?php echo $table->getName()->snakeToCamel(); ?>: <?php echo $table->getName()->snakeToCamelU(); ?> ) => {
                                alert("Update done !");
                                return <?php echo $table->getName()->snakeToCamel(); ?>;
                            })
                        ).subscribe((<?php echo $table->getName()->snakeToCamel(); ?>: <?php echo $table->getName()->snakeToCamelU(); ?> ) => {
                            window.location.reload();
                        });

                } else {
                    this.<?php echo $table->getName()->snakeToCamel(); ?>Service.add<?php echo $table->getName()->snakeToCamelU(); ?>(<?php echo $table->getName()->snakeToCamel(); ?>).
                        pipe(
                            map((<?php echo $table->getName()->snakeToCamel(); ?>: <?php echo $table->getName()->snakeToCamelU(); ?> ) => {
                                alert("Insert done !");
                                return <?php echo $table->getName()->snakeToCamel(); ?>;
                            })
                        ).subscribe((<?php echo $table->getName()->snakeToCamel(); ?>: <?php echo $table->getName()->snakeToCamelU(); ?> ) => {
                            window.location.href = fe_path + "/#" + "/<?php echo $table->getPluralName()->snakeToDash(); ?>/<?php echo $table->getName()->snakeToDash(); ?>-form/" + <?php echo $table->getName()->snakeToCamel(); ?>.id;
                        });
                }

            })
        }

    }

<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
    add<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>Row() {
        this.activatedRoute.paramMap.subscribe((params) => {
            this.<?php echo $subtableO->getAlias()->snakeToCamel(); ?>FormArray.push(this.fb.group({
<?php foreach ($subtableO->getFields() as $field): ?>
<?php if ($field->getRef() && !$field->isMultipleSelect()): ?>
                <?php echo $field->getName()->getPropertyValue(); ?>: new FormControl(<?php echo ($field->getRef()->getTableRef()->getPropertyValue() === $table->getName()->getPropertyValue())?"params.get(\"id\")":"null"?>, [<?php echo implode(',', $field->getFeValidators()); ?>], <?php echo $field->getFeAsyncValidators2($subtableO); ?>),
<?php else: ?>
                <?php echo $field->getName()->getPropertyValue(); ?>: new FormControl(null, [<?php echo implode(',', $field->getFeValidators()); ?>], <?php echo $field->getFeAsyncValidators($subtableO); ?>),
<?php endif; ?>
<?php endforeach; ?>
            }));
        });
    }
<?php endforeach; ?>
<?php endif; ?>

<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
    remove<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>Row(i: number) {
        this.<?php echo $subtableO->getAlias()->snakeToCamel(); ?>FormArray.removeAt(i);
    }
<?php endforeach; ?>
<?php endif; ?>


    delete(id: string) {
        if (confirm('Do you really want to delete the record ?')) {
            this.<?php echo $table->getName()->snakeToCamel(); ?>Service.remove<?php echo $table->getName()->snakeToCamelU(); ?>(id).subscribe(() => {
                window.location.href = fe_path + "/#" + "/<?php echo $table->getPluralName()->snakeToDash() ?>/<?php echo $table->getName()->snakeToDash(); ?>-table/";
            });
        }

    }

    back(url: string) {
        window.location.href = fe_path + "/#" + url;
    }

}
