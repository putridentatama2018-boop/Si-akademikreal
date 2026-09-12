<?php

namespace app\core\middleware;

class authmiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['logged_in']) ||
            $_SESSION['logged_in'] !== true
        ) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}