<?php
use Jotform\Core\{App,Route};
require __DIR__ . '/vendor/autoload.php';


$app = new \Jotform\Core\App();

//dotenv şimdilik burada 
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();



//Laravel dökümanındaki name routes
Route::get('/', 'HomeController@index'); 

Route::get('/user/:id1/:id2', 'UserController@detail')-> name('user');;


Route::prefix('/admin')->group(function(){
    Route::get('/?', function(){
        return 'admin home page';
    });
    Route::get('/users',function(){
        return 'admin user page';
    });
});
//admin
//admin/users




route('user',[':id1' => 5, ':id2' => 6]);
///user/3
// Route::get('/users', function(){
//     return 'user page';
//    });

//    Route::post('updateUser',function(){

//    });


//PHP dilinde "dispatch" terimi, Front Controller deseninin uygulanması sırasında, gelen HTTP isteklerinin işlendiği merkezi işlevi ifade eder
Route::dispatch();