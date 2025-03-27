import {AbstractControl, AsyncValidatorFn} from "@angular/forms";
import {map, of} from "rxjs";

export class MyValidators{

static notUnique(service: any, field: string, fieldId: string | null = null, excludingId: string | null = null) : AsyncValidatorFn {


    return (ctrl: AbstractControl) => {
        if (ctrl.value) {
            return service.notUnique(ctrl.value, field, fieldId, excludingId).pipe(
                map(obj => (obj ? { notUnique: true }: null ))
            );
        } else {
            return of (null);
        }

    }

    }
}
