<?php global $app; ?>
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [
<?php foreach ($app->getTables() as $table): ?>
    {
        path: '<?php echo $table->getPluralName()->snakeToDash(); ?>',
        loadChildren: () => import('./models/<?php echo $table->getName()->snakeToDash(); ?>/<?php echo $table->getName()->snakeToDash(); ?>.module').then(m => m.<?php echo $table->getName()->snakeToCamelU(); ?>Module)
    },
<?php endforeach; ?>
];

@NgModule({
imports: [RouterModule.forRoot(routes, { useHash: true })],
exports: [RouterModule]
})
export class AppRoutingModule { }

