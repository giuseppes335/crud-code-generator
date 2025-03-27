import {Component, EventEmitter, Input, Output} from '@angular/core';
import {SortOption} from "../sort/sort-option";
import {Sort} from "../sort/sort";
import {MenuAction} from "./menu-action";
import {Action} from "./action";
import {fe_path} from "../../../configuration";

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent {

  @Input('menuActions') menuActions: MenuAction[] | null = null;

  path: string | null = null;

  @Output('pathChange') pathChange: EventEmitter<Action> = new EventEmitter<Action>();

  pathChangeFn(action: Action) {
    this.pathChange.emit(action);
  }

  protected readonly fe_path = fe_path;

}
