<?php global $app; ?>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#"><?php echo $app->getAppName(); ?></a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</header>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            <app-menu
                    [menuActions]="[
                    <?php foreach ($app->getTables() as $table): ?>
                          {
                            optionValue: '/<?php echo $table->getPluralName()->snakeToDash(); ?>/<?php echo $table->getName()->snakeToDash(); ?>-form',
                            optionHtml: '<?php echo $table->getHtmlFormLabel(); ?>'
                          },
                          {
                            optionValue: '/<?php echo $table->getPluralName()->snakeToDash(); ?>/<?php echo $table->getName()->snakeToDash(); ?>-table',
                            optionHtml: '<?php echo $table->getHtmlListLabel(); ?>'
                          },
                    <?php endforeach; ?>
                      ]"
                    (pathChange)="go($event)"
            ></app-menu>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 p-md-4">
            <router-outlet></router-outlet>
        </main>
    </div>
</div>
