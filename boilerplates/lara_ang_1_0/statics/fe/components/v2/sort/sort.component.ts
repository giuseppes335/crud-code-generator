import {Component, EventEmitter, Input, Output} from '@angular/core';
import {Sort} from "./sort";
import {SortOption} from "./sort-option";

@Component({
  selector: 'app-sort',
  templateUrl: './sort.component.html',
  styleUrls: ['./sort.component.css']
})
export class SortComponent {

  @Input('fields') fields: SortOption[] | null = null;

  sortDir: string | null = null;

  sortField: string | null = null;

  @Output('sortChange') sortChange: EventEmitter<Sort> = new EventEmitter<Sort>();

  sortChangeFn(sort: Sort) {
    this.sortChange.emit(sort);
  }

}
