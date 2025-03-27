<?php global $table; ?>
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {<?php echo $table->getName()->snakeToCamelU(); ?>RoutingModule} from "./<?php echo $table->getName()->snakeToDash(); ?>-routing.module";
import {<?php echo $table->getName()->snakeToCamelU(); ?>TableComponent} from "./<?php echo $table->getName()->snakeToDash(); ?>-table/<?php echo $table->getName()->snakeToDash(); ?>-table.component";
import {ComponentsModule} from "../../components/components.module";
import {<?php echo $table->getName()->snakeToCamelU(); ?>FormComponent} from "./<?php echo $table->getName()->snakeToDash(); ?>-form/<?php echo $table->getName()->snakeToDash(); ?>-form.component";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";

@NgModule({
declarations: [
    <?php echo $table->getName()->snakeToCamelU(); ?>TableComponent,
    <?php echo $table->getName()->snakeToCamelU(); ?>FormComponent
],
imports: [
    <?php echo $table->getName()->snakeToCamelU(); ?>RoutingModule,
    CommonModule,
    ComponentsModule,
    ReactiveFormsModule,
    FormsModule
],
providers: []
})
export class <?php echo $table->getName()->snakeToCamelU(); ?>Module { }