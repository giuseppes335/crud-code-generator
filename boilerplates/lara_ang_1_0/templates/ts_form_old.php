<?php global $table; ?>
import { Component, OnInit } from '@angular/core';
import {FormControl, FormGroup, Validators, FormBuilder, FormArray} from "@angular/forms";
import {<?php echo $table->getName()->snakeToCamelU(); ?>Service} from "../<?php echo $table->getName()->snakeToDash(); ?>.service";
<?php if ($table->hasSubtables()): ?>
    <?php foreach ($table->getSubtablesO() as $subtableO): ?>
import {<?php echo $subtableO->getName()->snakeToCamelU(); ?>Service} from "../../<?php echo $subtableO->getName()->snakeToDash(); ?>/<?php echo $subtableO->getName()->snakeToDash(); ?>.service";
        <?php foreach ($subtableO->getSelectField() as $field): ?>
            <?php if ($field->getRef()->getTableRef()->getPropertyValue() !== $table->getName()->getPropertyValue()): ?>
import {<?php echo $field->getRef()->getTableRef()->snakeToCamelU(); ?>Service} from "../../<?php echo $field->getRef()->getTableRef()->snakeToCamelU(); ?>/<?php echo $field->getRef()->getTableRef()->snakeToDash(); ?>.service";
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php foreach ($table->getSelectField() as $field): ?>
import {<?php echo $field->getRef()->getTableRef()->snakeToCamelU(); ?>Service} from "../../<?php echo $field->getRef()->getTableRef()->snakeToDash(); ?>/<?php echo $field->getRef()->getTableRef()->snakeToDash();  ?>.service";
<?php endforeach; ?>
import {<?php echo $table->getName()->snakeToCamelU(); ?>} from "../<?php echo $table->getName()->snakeToDash(); ?>";
<?php if ($table->hasSubtables()): ?>
    <?php foreach ($table->getSubtablesO() as $subtableO): ?>
import {<?php echo $subtableO->getName()->snakeToCamelU(); ?>} from "../../<?php echo $subtableO->getName()->snakeToDash(); ?>/<?php echo $subtableO->getName()->snakeToDash(); ?>";
        <?php foreach ($subtableO->getSelectField() as $field): ?>
            <?php if ($field->getRef()->getTableRef()->getPropertyValue() !== $table->getName()->getPropertyValue()): ?>
import {<?php echo $field->getRef()->getTableRef()->snakeToCamelU(); ?>} from "../../<?php echo $field->getRef()->getTableRef()->snakeToDash(); ?>/<?php echo $field->getRef()->getTableRef()->snakeToDash();  ?>";
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php foreach ($table->getSelectField() as $field): ?>
import {<?php echo $field->getRef()->getTableRef()->snakeToCamelU(); ?>} from "../../<?php echo $field->getRef()->getTableRef()->snakeToDash(); ?>/<?php echo $field->getRef()->getTableRef()->snakeToDash(); ?>";
<?php endforeach; ?>
import {ActivatedRoute} from "@angular/router";
import {map} from "rxjs";
import {fe_path} from "../../../configuration";
import {MyValidators} from "../../../my-validators";

@Component({
selector: 'app-<?php echo $table->getName()->snakeToDash(); ?>-form',
templateUrl: './<?php echo $table->getName()->snakeToDash(); ?>-form.component.html',
styleUrls: ['./<?php echo $table->getName()->snakeToDash(); ?>-form.component.css']
})
export class <?php echo $table->getName()->snakeToCamelU(); ?>FormComponent implements OnInit {

    decimalRegex = /^-?\d{0,3}([.]\d{0,5})?$/;

<?php foreach ($table->getFields() as $field): ?>
    <?php echo $field->getName()->snakeToCamel(); ?>Control: FormControl = new FormControl(null, [<?php echo implode(',', $field->getFeValidators()); ?>]);
<?php endforeach; ?>

<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
        <?php echo $subtableO->getPluralName()->snakeToCamel(); ?>FormArray: FormArray = this.fb.array([]);
<?php endforeach; ?>
<?php endif; ?>

    <?php echo $table->getName()->snakeToCamel(); ?>Form = new FormGroup({
<?php foreach ($table->getFields() as $field): ?>
        <?php echo $field->getName()->getPropertyValue(); ?>: this.<?php echo $field->getName()->snakeToCamel(); ?>Control,
<?php endforeach; ?>
<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
        <?php echo $subtableO->getPluralName()->getPropertyValue(); ?>: this.<?php echo $subtableO->getPluralName()->snakeToCamel(); ?>FormArray,
<?php endforeach; ?>
<?php endif; ?>
    });

<?php foreach ($table->getFields() as $field): ?>
<?php if ($field->getRef()): ?>
    <?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>Rows!: any[];
<?php endif; ?>
<?php endforeach; ?>

<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
<?php foreach ($subtableO->getSelectField() as $field): ?>
    <?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>Rows!: any[];
<?php endforeach; ?>
<?php endforeach; ?>
<?php endif; ?>

    constructor(
        private <?php echo $table->getName()->snakeToCamel(); ?>Service: <?php echo $table->getName()->snakeToCamelU(); ?>Service,
<?php foreach ($table->getSelectField() as $field): ?>
        private <?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Service: <?php echo $field->getRef()->getTableRef()->snakeToCamelU(); ?>Service,
<?php endforeach; ?>
<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
        private <?php echo $subtableO->getName()->snakeToCamel(); ?>Service: <?php echo $subtableO->getName()->snakeToCamelU(); ?>Service,
<?php foreach ($subtableO->getSelectField() as $field): ?>
<?php if ($field->getRef()->getTableRef()->getPropertyValue() !== $table->getName()->getPropertyValue()): ?>
        private <?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Service: <?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Service,
<?php endif; ?>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endif; ?>
        private activatedRoute: ActivatedRoute,
        private fb: FormBuilder
    ) {}

    ngOnInit() {
        this.activatedRoute.paramMap.subscribe((params) => {
            if (params.get("id")) {
                this.<?php echo $table->getName()->snakeToCamel();  ?>Service.get<?php echo $table->getName()->snakeToCamelU(); ?>(params.get("id")).subscribe((<?php echo $table->getName()->snakeToCamel(); ?>: <?php echo $table->getName()->snakeToCamelU(); ?>) => {
<?php foreach ($table->getFields() as $field): ?>
                    this.<?php echo $field->getName()->snakeToCamel(); ?>Control.setValue(<?php echo $table->getName()->snakeToCamel(); ?>.<?php echo $field->getName()->getPropertyValue(); ?>);
<?php endforeach; ?>
                });

<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
                this.<?php echo $table->getName()->snakeToCamel(); ?>Service.get<?php echo $subtableO->getName()->snakeToCamelU(); ?>Of<?php echo $table->getPluralName()->snakeToCamelU(); ?>(params.get("id")).subscribe((<?php echo $subtableO->getPluralName()->snakeToCamel(); ?>: <?php echo $subtableO->getName()->snakeToCamelU() ?>[]) => {
                    <?php echo $subtableO->getPluralName()->snakeToCamel(); ?>.forEach((<?php echo $subtableO->getName()->snakeToCamel(); ?>: <?php echo $subtableO->getName()->snakeToCamelU(); ?>, index: number) => {
                        this.<?php echo $subtableO->getPluralName()->snakeToCamel(); ?>FormArray.push(this.fb.group({
<?php foreach ($subtableO->getFields() as $field): ?>
                        <?php echo $field->getName()->getPropertyValue(); ?>: new FormControl(<?php echo $subtableO->getName()->snakeToCamel(); ?>.<?php echo $field->getName()->getPropertyValue(); ?>, [<?php echo implode(',', $field->getFeValidators()); ?>], <?php echo $field->getFeAsyncValidators2($subtableO); ?>),
<?php endforeach; ?>
                        }));
                    })
                });
<?php endforeach; ?>
<?php endif; ?>
            }
        })

<?php foreach ($table->getSelectField() as $field): ?>
        this.<?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Service.get<?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamelU(); ?>(null, null, null).subscribe((<?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>: <?php echo $field->getRef()->getTableRef()->snakeToCamelU(); ?>[]) => {
            this.<?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>Rows = <?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>;
        })
<?php endforeach; ?>

<?php if ($table->hasSubtables()): ?>
    <?php foreach ($table->getSubtablesO() as $subtableO): ?>
        <?php foreach ($subtableO->getSelectField() as $field): ?>
        this.<?php echo $field->getRef()->getTableRef()->snakeToCamel(); ?>Service.get<?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamelU(); ?>(null, null, null).subscribe((<?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>: <?php echo $field->getRef()->getTableRef()->snakeToCamelU(); ?>[]) => {
            this.<?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>Rows = <?php echo $field->getRef()->getTableRefO()->getPluralName()->snakeToCamel(); ?>;
        })
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>


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
                <?php echo $subtableO->getPluralName()->getPropertyValue(); ?>: this.<?php echo $table->getName()->snakeToCamel(); ?>Form.value.<?php echo $subtableO->getPluralName()->getPropertyValue(); ?>!
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
                            window.location.href = fe_path + "<?php echo $table->getPluralName()->snakeToDash(); ?>/<?php echo $table->getName()->snakeToDash(); ?>-form/" + <?php echo $table->getName()->snakeToCamel(); ?>.id;
                        });
                }

            })
        }

    }

<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
    add<?php echo $subtableO->getPluralName()->snakeToCamelU(); ?>Row() {
        this.activatedRoute.paramMap.subscribe((params) => {
            this.<?php echo $subtableO->getPluralName()->snakeToCamel(); ?>FormArray.push(this.fb.group({
<?php foreach ($subtableO->getFields() as $field): ?>
<?php if ($field->getRef()): ?>
                <?php echo $field->getName()->getPropertyValue(); ?>: new FormControl(params.get("id"), [<?php echo implode(',', $field->getFeValidators()); ?>], <?php echo $field->getFeAsyncValidators2($subtableO); ?>),
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
    remove<?php echo $subtableO->getPluralName()->snakeToCamelU(); ?>Row(i: number) {
        this.<?php echo $subtableO->getPluralName()->snakeToCamel(); ?>FormArray.removeAt(i);
    }
<?php endforeach; ?>
<?php endif; ?>


    delete(id: string) {
        if (confirm('Do you really want to delete the record ?')) {
            this.<?php echo $table->getName()->snakeToCamel(); ?>Service.remove<?php echo $table->getName()->snakeToCamelU(); ?>(id).subscribe(() => {
                window.location.href = fe_path + "/<?php echo $table->getPluralName()->snakeToDash() ?>/<?php echo $table->getName()->snakeToDash(); ?>-table/";
            });
        }

    }

    back(url: string) {
        window.location.href = fe_path + url;
    }

}
