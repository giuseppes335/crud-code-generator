<?php global $table; ?>
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {<?php echo $table->getName()->snakeToCamelU(); ?>FormComponent} from "./<?php echo $table->getName()->snakeToDash(); ?>-form/<?php echo $table->getName()->snakeToDash(); ?>-form.component";
import {<?php echo $table->getName()->snakeToCamelU(); ?>TableComponent} from "./<?php echo $table->getName()->snakeToDash(); ?>-table/<?php echo $table->getName()->snakeToDash(); ?>-table.component";

const routes: Routes = [
    {
        path: '<?php echo $table->getName()->snakeToDash(); ?>-form', component: <?php echo $table->getName()->snakeToCamelU(); ?>FormComponent
    },
    {
        path: '<?php echo $table->getName()->snakeToDash(); ?>-form/:id', component: <?php echo $table->getName()->snakeToCamelU(); ?>FormComponent
    },
    {
        path: '<?php echo $table->getName()->snakeToDash(); ?>-table', component: <?php echo $table->getName()->snakeToCamelU(); ?>TableComponent
    }
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class <?php echo $table->getName()->snakeToCamelU(); ?>RoutingModule { }