<?php global $table; ?>
import {Component, OnInit} from '@angular/core';
import {<?php echo $table->getName()->snakeToCamelU(); ?>Service} from "../<?php echo $table->getName()->snakeToDash(); ?>.service";
import {<?php echo $table->getName()->snakeToCamelU(); ?>} from "../<?php echo $table->getName()->snakeToDash(); ?>";
import {Sort} from "../../../components/v2/sort/sort";
import {Pagination} from "../../../components/v2/pagination/pagination";
import {Search} from "../../../components/v2/search/search";
import {fe_path} from "../../../configuration";

@Component({
    selector: 'sab-<?php echo $table->getName()->snakeToDash(); ?>-table',
    templateUrl: './<?php echo $table->getName()->snakeToDash(); ?>-table.component.html',
    styleUrls: ['./<?php echo $table->getName()->snakeToDash(); ?>-table.component.css']
})
export class <?php echo $table->getName()->snakeToCamelU(); ?>TableComponent implements OnInit {


    sort: Sort | null = null;
    pagination: Pagination | null = null;
    totalPages: number | null = null;
    search: Search | null = null;

    <?php echo $table->getPluralName()->snakeToCamel(); ?>: any[] = [];

    action: string | null = null;

    loading: boolean = false;

    constructor(
        public <?php echo $table->getName()->snakeToCamel(); ?>Service: <?php echo $table->getName()->snakeToCamelU(); ?>Service
    ) {}

    private refresh() {
        this.loading = true;
        this.<?php echo $table->getName()->snakeToCamel(); ?>Service.get<?php echo $table->getPluralName()->snakeToCamelU(); ?>(this.sort, this.pagination, this.search).subscribe((response: any) => {
            this.<?php echo $table->getPluralName()->snakeToCamel(); ?> = response.data;
            this.totalPages = response.last_page;
            this.loading = false;
        })
    }

    ngOnInit() {
        this.pagination = {
            page: 1
        }
        this.refresh();
    }

    applySort(sort: Sort) {
        this.sort = sort;
        this.refresh();
    }

    applyPagination(pagination: Pagination) {
        this.pagination = pagination;
        this.refresh();
    }

    applySearch(search: Search) {
        this.search = search;
        this.refresh()
    }


    go(action: string | null) {
        window.location.href = fe_path + "/#" + action;
    }

}
