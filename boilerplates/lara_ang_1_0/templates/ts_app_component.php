import { Component } from '@angular/core';
import {Action} from "./components/v2/menu/action";
import {Router} from "@angular/router";

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent {
    title = '';

    constructor(
        private router: Router
    ) {
    }

    go(action: Action) {
        this.router.navigate([action.path]);
    }
}
