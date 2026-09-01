<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        $data = $this->request->getJSON(true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Email and password required']);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Invalid email or password']);
        }

        if ($user['status'] !== 'ACTIVE') {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Account is inactive']);
        }

        $session = session();
        $session->set('user_id', $user['user_id']);
        $session->set('user_name', $user['first_name'] . ' ' . $user['last_name']);
        $session->set('user_role', $user['role']);

        return $this->response->setJSON(['success' => true, 'user' => $user['first_name'] . ' ' . $user['last_name']]);
    }

    public function logout()
    {
        session()->destroy();
        return $this->response->setJSON(['success' => true]);
    }

    public function check()
    {
        $session = session();
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Not logged in']);
        }
        return $this->response->setJSON([
            'user_id' => $userId,
            'name' => $session->get('user_name'),
            'role' => $session->get('user_role'),
        ]);
    }
}
