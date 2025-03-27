import {Component, EventEmitter, Input, Output} from '@angular/core';

import {Pagination} from "./pagination";


@Component({
  selector: 'app-pagination',
  templateUrl: './pagination.component.html',
  styleUrls: ['./pagination.component.css']
})
export class PaginationComponent {

  currentPage: number | null = null;

  @Input('totalPages') totalPages: number | null = null;

  @Output('pageChange') pageChange: EventEmitter<Pagination> = new EventEmitter<Pagination>();

  getPages(totalPages: number | null)  {
    let items: number[] = [];
    if (totalPages) {

      let r: number = 1;
      let r1: number | null = null;
      let r2: number | null = null;

      if (!this.currentPage) {
        r1 = 2;
        r2 = Math.min(totalPages - 1, 4);
      } else {

        if (this.currentPage === 1 || this.currentPage === totalPages) {
          r = 3;
        }
        if (this.currentPage === 2 || this.currentPage === totalPages - 1) {
          r = 2;
        }
        r1 = Math.max(2, this.currentPage - r);
        r2 = Math.min(this.currentPage + r, totalPages - 1);

      }

      for (let i = r1; i <= r2; i++) {
        items.push(i)
      }
      return items;
    }
    return items;
  }

  pageChangeFn(pagination: Pagination) {
    this.currentPage = pagination.page;
    this.pageChange.emit(pagination);
  }
}
