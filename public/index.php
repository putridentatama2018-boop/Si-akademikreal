<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../routes/web.php';

require_once __DIR__ . '/../app/core/middleware/authmiddleware.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Response.php';

require_once __DIR__ . '/../app/models/Model.php';
require_once __DIR__ . '/../app/models/ProdiModel.php';
require_once __DIR__ . '/../app/models/MatakuliahModel.php';
require_once __DIR__ . '/../app/models/mahasiswa.php';
require_once __DIR__ . '/../app/models/Buku.php';

require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';

require_once __DIR__ . '/../app/services/MahasiswaService.php';

require_once __DIR__ . '/../app/controllers/BaseController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/controllers/MahasiswaApiController.php';
require_once __DIR__ . '/../app/controllers/ProdiController.php';
require_once __DIR__ . '/../app/controllers/MatakuliahController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/BukuController.php';


/*
 * URI
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/si-akademik/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];


// =====================================================
// ACARA 27
// Membaca multipart/form-data pada method PUT
// =====================================================

if ($method === 'PUT') {

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (str_contains($contentType, 'multipart/form-data')) {

        $input = file_get_contents('php://input');

        preg_match(
            '/boundary=(.*)$/',
            $contentType,
            $matches
        );

        if (!empty($matches[1])) {

            $boundary = '--' . trim($matches[1], '"');

            $parts = preg_split(
                '/\r\n' . preg_quote($boundary, '/') . '/',
                $input
            );

            foreach ($parts as $part) {

                if (empty(trim($part)) || $part === '--') {
                    continue;
                }

                $part = ltrim($part, "\r\n");

                if (str_ends_with($part, '--')) {
                    $part = substr($part, 0, -2);
                }

                $part = rtrim($part, "\r\n");

                if (
                    preg_match(
                        '/Content-Disposition: form-data; name="([^"]+)"(?:; filename="([^"]*)")?\r\n(?:Content-Type: ([^\r\n]+)\r\n)?\r\n(.*)/s',
                        $part,
                        $matches
                    )
                ) {

                    $fieldName = $matches[1];

                    $fileName = $matches[2] ?? '';

                    $fileType = $matches[3] ?? '';

                    $fieldValue = $matches[4];


                    // =================================================
                    // Jika data yang diterima adalah FILE
                    // =================================================

                    if ($fileName !== '') {

                        $tempFile = tempnam(
                            sys_get_temp_dir(),
                            'php_put_'
                        );

                        file_put_contents(
                            $tempFile,
                            $fieldValue
                        );

                        $_FILES[$fieldName] = [
                            'name' => $fileName,
                            'type' => $fileType,
                            'tmp_name' => $tempFile,
                            'error' => UPLOAD_ERR_OK,
                            'size' => filesize($tempFile)
                        ];

                    } else {

                        // =================================================
                        // Jika data yang diterima adalah INPUT BIASA
                        // =================================================

                        $_POST[$fieldName] = $fieldValue;
                    }
                }
            }
        }
    }
}


/*
 * Membuat MahasiswaController
 */
function getMahasiswaController()
{
    $repository = new \App\Repositories\MahasiswaRepository(
        \App\Core\Database::getInstance()
    );

    $service = new \App\Services\MahasiswaService($repository);

    return new \App\Controllers\MahasiswaController($service);
}


/*
 * Membuat MahasiswaApiController
 */
function getMahasiswaApiController()
{
    $repository = new \App\Repositories\MahasiswaRepository(
        \App\Core\Database::getInstance()
    );

    return new \App\Controllers\MahasiswaApiController($repository);
}


/*
 * Route utama
 */
if (isset($routes[$method][$uri])) {

    $route = $routes[$method][$uri];

    $controllerName = $route[0];

    $action = $route[1];

    $middlewareList = $route[2] ?? [];


    foreach ($middlewareList as $middleware) {

        $middlewareInstance = new $middleware();

        $middlewareInstance->handle();
    }


    $controllerClass = "App\\Controllers\\{$controllerName}";


    if ($controllerName === 'MahasiswaController') {

        $controller = getMahasiswaController();

    } elseif ($controllerName === 'MahasiswaApiController') {

        $controller = getMahasiswaApiController();

    } else {

        $controller = new $controllerClass();
    }


    $controller->$action();

    exit();
}


$segments = explode('/', trim($uri, '/'));


/*
 * Route Buku
 */
if (
    $method === 'GET' &&
    count($segments) === 1 &&
    $segments[0] === 'buku'
) {

    $controller = new \App\Controllers\BukuController();

    $controller->index();

    exit();
}


/*
 * API Mahasiswa
 */

// GET /api/mahasiswa
if (
    $method === 'GET' &&
    count($segments) === 2 &&
    $segments[0] === 'api' &&
    $segments[1] === 'mahasiswa'
) {

    $controller = getMahasiswaApiController();

    $controller->index();

    exit();
}


// GET /api/mahasiswa/{id}
if (
    $method === 'GET' &&
    count($segments) === 3 &&
    $segments[0] === 'api' &&
    $segments[1] === 'mahasiswa' &&
    is_numeric($segments[2])
) {

    $id = (int) $segments[2];

    $controller = getMahasiswaApiController();

    $controller->show($id);

    exit();
}


// POST /api/mahasiswa
if (
    $method === 'POST' &&
    count($segments) === 2 &&
    $segments[0] === 'api' &&
    $segments[1] === 'mahasiswa'
) {

    $controller = getMahasiswaApiController();

    $controller->store();

    exit();
}


// PUT /api/mahasiswa/{id}
if (
    $method === 'PUT' &&
    count($segments) === 3 &&
    $segments[0] === 'api' &&
    $segments[1] === 'mahasiswa' &&
    is_numeric($segments[2])
) {

    $id = (int) $segments[2];

    $controller = getMahasiswaApiController();

    $controller->update($id);

    exit();
}


// DELETE /api/mahasiswa/{id}
if (
    $method === 'DELETE' &&
    count($segments) === 3 &&
    $segments[0] === 'api' &&
    $segments[1] === 'mahasiswa' &&
    is_numeric($segments[2])
) {

    $id = (int) $segments[2];

    $controller = getMahasiswaApiController();

    $controller->destroy($id);

    exit();
}


/*
 * Edit Prodi
 */
if (
    $method === 'GET' &&
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


/*
 * Edit Mata Kuliah
 */
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


/*
 * Update Prodi
 */
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


/*
 * Update Mata Kuliah
 */
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


/*
 * Edit Mahasiswa
 */
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


/*
 * Update Mahasiswa
 */
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


/*
 * Mahasiswa berdasarkan ID
 */
if (
    count($segments) === 2 &&
    $segments[0] === 'mahasiswa' &&
    is_numeric($segments[1])
) {

    $middleware = new \app\core\middleware\authmiddleware();

    $middleware->handle();

    $id = (int) $segments[1];

    $controller = getMahasiswaController();

    if (method_exists($controller, 'show')) {

        $controller->show($id);
    }

    exit();
}


/*
 * Delete Prodi
 */
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


/*
 * Delete Mahasiswa
 */
if (
    $method === 'GET' &&
    count($segments) === 3 &&
    $segments[0] === 'mahasiswa' &&
    $segments[1] === 'delete' &&
    is_numeric($segments[2])
) {

    $middleware = new \app\core\middleware\authmiddleware();

    $middleware->handle();

    $controller = getMahasiswaController();

    $controller->delete((int) $segments[2]);

    exit();
}


/*
 * 404
 */
\App\Core\Response::json(
    404,
    false,
    'Halaman tidak ditemukan'
);