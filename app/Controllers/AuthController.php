<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        verify_csrf();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($email, $password)) {
            $this->redirect('/dashboard');
        }

        $_SESSION['error'] = 'Credenciales inválidas';
        $this->redirect('/');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }
}
