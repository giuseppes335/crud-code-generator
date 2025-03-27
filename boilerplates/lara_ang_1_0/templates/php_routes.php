<?php global $app; ?>

<?php echo '<?php'; ?>

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<?php foreach ($app->getTablesToImport() as $table): ?>
use App\Http\Controllers\<?php echo $table->getName()->snakeToCamelU(); ?>Controller;
<?php endforeach; ?>

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

<?php foreach ($app->getTables() as $table): ?>
Route::get('/<?php echo $table->getPluralName()->snakeToDash(); ?>', [<?php echo $table->getName()->snakeToCamelU(); ?>Controller::class, 'index']);
Route::get('/<?php echo $table->getPluralName()->snakeToDash(); ?>/{id}', [<?php echo $table->getName()->snakeToCamelU(); ?>Controller::class, 'get']);
Route::post('/<?php echo $table->getPluralName()->snakeToDash(); ?>', [<?php echo $table->getName()->snakeToCamelU(); ?>Controller::class, 'store']);
Route::put('/<?php echo $table->getPluralName()->snakeToDash(); ?>/{id}', [<?php echo $table->getName()->snakeToCamelU(); ?>Controller::class, 'update']);
Route::get('/<?php echo $table->getPluralName()->snakeToDash(); ?>/not-unique/{field}/{value}', [<?php echo $table->getName()->snakeToCamelU(); ?>Controller::class, 'notUnique']);
Route::delete('/<?php echo $table->getPluralName()->snakeToDash(); ?>/{id}', [<?php echo $table->getName()->snakeToCamelU(); ?>Controller::class, 'delete']);
<?php if($table->hasSubtables())  : ?>
<?php foreach ($table->getSubtablesO() as $subtableO): ?>
Route::get('/<?php echo $table->getPluralName()->snakeToDash(); ?>/{id}/<?php echo $subtableO->getAlias()->snakeToDash(); ?>', [<?php echo $table->getName()->snakeToCamelU(); ?>Controller::class, 'get<?php echo $subtableO->getAlias()->snakeToCamelU(); ?>Of<?php echo $table->getName()->snakeToCamelU(); ?>']);
<?php endforeach; ?>
<?php endif; ?>

<?php endforeach; ?>

