import { NgModule } from '@angular/core';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {CommonModule} from "@angular/common";
import {SortComponent} from "./v2/sort/sort.component";
import { PaginationComponent } from './v2/pagination/pagination.component';
import { SearchComponent } from './v2/search/search.component';
import { MenuComponent } from './v2/menu/menu.component';
import { ValidationComponent } from './v2/validation/validation.component';


@NgModule({
  declarations: [
    SortComponent,
    PaginationComponent,
    SearchComponent,
    MenuComponent,
    ValidationComponent
  ],
    imports: [
        CommonModule,
        FormsModule,
        ReactiveFormsModule,
    ],
  exports: [
    SortComponent,
    PaginationComponent,
    SearchComponent,
    MenuComponent,
    ValidationComponent
  ],
  providers: []
})
export class ComponentsModule { }
