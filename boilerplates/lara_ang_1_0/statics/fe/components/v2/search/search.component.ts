import {Component, EventEmitter, Output} from '@angular/core';
import {Sort} from "../sort/sort";
import {Search} from "./search";

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent {
  searchString: string | null = null;

  @Output('searchStringChange') searchStringChange: EventEmitter<Search> = new EventEmitter<Search>();

  searchStringChangeFn(search: Search) {
    this.searchStringChange.emit(search);
  }
}
