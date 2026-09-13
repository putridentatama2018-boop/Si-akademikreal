<?php

// Konfigurasi
require_once __DIR__ . '/../config/app.php';

// Routes
require_once __DIR__ . '/../routes/web.php';

// Middleware
require_once __DIR__ . '/../app/core/middleware/authmiddleware.php';
require_once __DIR__ . '/../app/core/Database.php';

// Models
require_once __DIR__ . '/../app/models/Model.php';
require_once __DIR__ . '/../app/models/ProdiModel.php';
require_once __DIR__ . '/../app/models/MatakuliahModel.php';
require_once __DIR__ . '/../app/models/mahasiswa.php';

// Repository
require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';

// Service
require_once __DIR__ . '/../app/services/MahasiswaServices.php';

// Controllers
require_once __DIR__ . '/../app/controllers/BaseController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/controllers/ProdiController.php';
require_once __DIR__ . '/../app/controllers/MatakuliahController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';


// Ambil URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hilangkan base path
$base = '/si-akademik/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

// Method GET / POST
$method = $_SERVER['REQUEST_METHOD'];


// ======================================================
// FUNGSI MEMBUAT MAHASISWA CONTROLLER
// Repository -> Service -> Controller
// ======================================================
function getMahasiswaController()
{
    $repository = new \App\Repositories\MahasiswaRepository(
        \App\Core\Database::getInstance()
    );

    $service = new \App\Services\MahasiswaService($repository);

    return new \App\Controllers\MahasiswaController($service);
}


// ======================================================
// CEK ROUTE UTAMA
// ======================================================
if (isset($routes[$method][$uri])) {

    $route = $routes[$method][$uri];

    $controllerName = $route[0];
    $action = $route[1];

    // Cek route memiliki middleware
    $middlewareList = $route[2] ?? [];

    // Jalankan middleware
    foreach ($middlewareList as $middleware) {

        $middlewareInstance = new $middleware();

        $middlewareInstance->handle();
    }

    // Nama class controller
    $controllerClass = "App\\Controllers\\{$controllerName}";

    // Dependency Injection Mahasiswa
    if ($controllerName === 'MahasiswaController') {

        $controller = getMahasiswaController();

    } else {

        $controller = new $controllerClass();
    }

    $controller->$action();

    exit();
}


// ======================================================
// SEGMENTS
// ======================================================
$segments = explode('/', trim($uri, '/'));


// ======================================================
// ROUTE EDIT PRODI DENGAN ID
// ======================================================
if (
    count($segments) === 3 &&
    $segments[0] === 'prodi' &&
    $segments[1] === 'edit' &&
    is_numeric($segments[2])
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $id = (int) $segments[2];

    $controller = new \App\Controllers\ProdiController();
    $controller->edit($id);

    exit();
}


// ======================================================
// ROUTE EDIT MATAKULIAH DENGAN ID
// ======================================================
if (
    $method === 'GET' &&
    count($segments) === 3 &&
    $segments[0] === 'matakuliah' &&
    $segments[1] === 'edit' &&
    is_numeric($segments[2])
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $id = (int) $segments[2];

    $controller = new \App\Controllers\MatakuliahController();
    $controller->edit($id);

    exit();
}


// ======================================================
// ROUTE UPDATE PRODI DENGAN ID
// ======================================================
if (
    $method === 'POST' &&
    count($segments) === 3 &&
    $segments[0] === 'prodi' &&
    $segments[1] === 'update' &&
    is_numeric($segments[2])
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $id = (int) $segments[2];

    $controller = new \App\Controllers\ProdiController();
    $controller->update($id);

    exit();
}


// ======================================================
// ROUTE UPDATE MATAKULIAH DENGAN ID
// ======================================================
if (
    $method === 'POST' &&
    count($segments) === 3 &&
    $segments[0] === 'matakuliah' &&
    $segments[1] === 'update' &&
    is_numeric($segments[2])
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $id = (int) $segments[2];

    $controller = new \App\Controllers\MatakuliahController();
    $controller->update($id);

    exit();
}


// ======================================================
// ROUTE EDIT MAHASISWA DENGAN ID
// ======================================================
if (
    $method === 'GET' &&
    count($segments) === 3 &&
    $segments[0] === 'mahasiswa' &&
    $segments[1] === 'edit' &&
    is_numeric($segments[2])
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $id = (int) $segments[2];

    $controller = getMahasiswaController();

    $controller->edit($id);

    exit();
}


// ======================================================
// ROUTE UPDATE MAHASISWA
// ======================================================
if (
    $method === 'POST' &&
    count($segments) === 2 &&
    $segments[0] === 'mahasiswa' &&
    $segments[1] === 'update'
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $controller = getMahasiswaController();

    $controller->update();

    exit();
}


// ======================================================
// ROUTE MAHASISWA DENGAN ID
// ======================================================
if (
    count($segments) === 2 &&
    $segments[0] === 'mahasiswa' &&
    is_numeric($segments[1])
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $id = (int) $segments[1];

    $controller = getMahasiswaController();

    // Jika controller memiliki method show
    if (method_exists($controller, 'show')) {
        $controller->show($id);
    }

    exit();
}


// ======================================================
// ROUTE DELETE PRODI DENGAN ID
// ======================================================
if (
    $method === 'POST' &&
    count($segments) === 3 &&
    $segments[0] === 'prodi' &&
    $segments[1] === 'delete' &&
    is_numeric($segments[2])
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $id = (int) $segments[2];

    $controller = new \App\Controllers\ProdiController();
    $controller->delete($id);

    exit();
}


// ======================================================
// ROUTE DELETE MAHASISWA DENGAN ID
// ======================================================
if (
    $method === 'GET' &&
    count($segments) === 3 &&
    $segments[0] === 'mahasiswa' &&
    $segments[1] === 'delete' &&
    is_numeric($segments[2])
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $id = (int) $segments[2];

    $controller = getMahasiswaController();

    $controller->delete($id);

    exit();
}


// ======================================================
// ROUTE DELETE MATAKULIAH DENGAN ID
// ======================================================
if (
    $method === 'POST' &&
    count($segments) === 3 &&
    $segments[0] === 'matakuliah' &&
    $segments[1] === 'delete' &&
    is_numeric($segments[2])
) {

    $middleware = new \app\core\middleware\authmiddleware();
    $middleware->handle();

    $id = (int) $segments[2];

    $controller = new \App\Controllers\MatakuliahController();
    $controller->delete($id);

    exit();
}


// ======================================================
// 404
// ======================================================
http_response_code(404);

echo "404 - Halaman tidak ditemukan";