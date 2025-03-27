import {Component, Input} from '@angular/core';
import {FormControl} from "@angular/forms";

@Component({
  selector: 'app-validation',
  templateUrl: './validation.component.html',
  styleUrls: ['./validation.component.css']
})
export class ValidationComponent {

  @Input('formControl0') formControl: FormControl | null = null;

  @Input('validators') validators: string[] | null = null;

  getError(errorType: string) {
    if (this.formControl && this.formControl.errors) {
      return this.formControl.errors[errorType];
    }

  }

}
