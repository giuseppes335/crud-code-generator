<?php global $table; ?>
import { Injectable } from '@angular/core';
import {catchError, Observable} from "rxjs";
import {be_path} from "../../configuration";
import {HttpClient} from "@angular/common/http";
import {AppService} from "../../app.service";
import {Sort} from "../../components/v2/sort/sort";
import {Pagination} from "../../components/v2/pagination/pagination";
import {Search} from "../../components/v2/search/search";
import {<?php echo $table->getName()->snakeToCamelU(); ?>} from "./<?php echo $table->getName()->snakeToDash(); ?>";
<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesForImport() as $subtableO): ?>
import {<?php echo $subtableO->getName()->snakeToCamelU(); ?>} from "../<?php echo $subtableO->getName()->snakeToDash(); ?>/<?php echo $subtableO->getName()->snakeToDash(); ?>";
<?php endforeach; ?>
<?php endif; ?>

@Injectable({
    providedIn: 'root',
})
export class <?php echo $table->getName()->snakeToCamelU(); ?>Service {

    private endpoint: string = be_path + '/api/<?php echo $table->getPluralName()->snakeToDash(); ?>';

    constructor(
        private httpClient: HttpClient,
        private errorHandlerService: AppService
    ) { }

    add<?php echo $table->getName()->snakeToCamelU(); ?>(<?php echo $table->getName()->snakeToCamel(); ?>: <?php echo $table->getName()->snakeToCamelU(); ?>): Observable<<?php echo $table->getName()->snakeToCamelU(); ?>> {
        return this.httpClient.post<<?php echo $table->getName()->snakeToCamelU(); ?>>(this.endpoint, <?php echo $table->getName()->snakeToCamel(); ?>)
            .pipe(
                catchError(this.errorHandlerService.handleError)
            );
    }

    get<?php echo $table->getName()->snakeToCamelU(); ?>(id: string | null): Observable<<?php echo $table->getName()->snakeToCamelU(); ?>> {
        return this.httpClient.get<<?php echo $table->getName()->snakeToCamelU(); ?>>(this.endpoint + '/' + id)
            .pipe(
                catchError(this.errorHandlerService.handleError)
            );
    }

    update<?php echo $table->getName()->snakeToCamelU(); ?>(id: string | null, <?php echo $table->getName()->snakeToCamel(); ?>: <?php echo $table->getName()->snakeToCamelU(); ?>) {
        return this.httpClient.put<<?php echo $table->getName()->snakeToCamelU(); ?>>(this.endpoint + '/' + id, <?php echo $table->getName()->snakeToCamel(); ?>)
            .pipe(
                catchError(this.errorHandlerService.handleError)
            );
        }

    get<?php echo $table->getPluralName()->snakeToCamelU(); ?>(sort: Sort | null, pagination: Pagination | null, search: Search | null): Observable<any> {

        let beQueryStringArray: string[] = [];
        let beQueryString: string = '';

        if (sort && sort.sortField) {
            if (sort.sortDir) {
                beQueryStringArray.push('sort=' + sort.sortField);
                beQueryStringArray.push('sortDir=' + sort.sortDir);
            } else {
                beQueryStringArray.push('sort=' + sort.sortField);
            }
        }

        if (pagination && pagination.page) {
            beQueryStringArray.push('page=' + pagination.page);
        }

        if (search && search.searchString) {
            beQueryStringArray.push('search=' + search.searchString);
        }

        beQueryString = beQueryStringArray.join('&');

        return this.httpClient.get<<?php echo $table->getName()->snakeToCamelU(); ?>[]>(this.endpoint + '?' + beQueryString    )
            .pipe(
                catchError(this.errorHandlerService.handleError)
            );

    }

    remove<?php echo $table->getName()->snakeToCamelU(); ?>(id: any): any {
        return this.httpClient.delete<<?php echo $table->getName()->snakeToCamelU(); ?>>(this.endpoint + '/' + id)
            .pipe(
                catchError(this.errorHandlerService.handleError)
            );
    }



<?php if ($table->hasSubtables()): ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
        get<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>Of<?php echo $table->getPluralName()->snakeToCamelU(); ?>(id: string | null): Observable<<?php echo $subtableO->getName()->snakeToCamelU(); ?>[]> {
            return this.httpClient.get<<?php echo $subtableO->getName()->snakeToCamelU(); ?>[]>(this.endpoint + '/' + id + '/<?php echo $subtableO->getAlias()->snakeToDash(); ?>'    )
                .pipe(
                    catchError(this.errorHandlerService.handleError)
                );
        }
<?php endforeach; ?>
<?php endif; ?>



    notUnique(value: string, field: string, fieldId: string | null = null, excludingId: string | null = null) {
        let beQueryStringArray: string[] = [];
        let beQueryString: string = '';
        if (fieldId) {
            beQueryStringArray.push('fieldId=' + fieldId);
        }
        if (excludingId) {
            beQueryStringArray.push('excludingId=' + excludingId);
        }
        beQueryString = beQueryStringArray.join('&');

        return this.httpClient.get<<?php echo $table->getName()->snakeToCamelU(); ?>>(this.endpoint + '/not-unique/' + field + '/' + value + '?' + beQueryString)
            .pipe(
                catchError(this.errorHandlerService.handleError)
            );
    }


}
