<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AuditLogModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return $this->response->setJSON($this->userModel->findAll());
    }

    public function show($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }
        return $this->response->setJSON($user);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['password'])) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Password is required']);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $id = $this->userModel->insert($data);
        if (!$id) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Failed']);
        }
        (new AuditLogModel())->log('user.created', "Created user: {$data['first_name']} {$data['last_name']} ({$data['email']})");
        return $this->response->setStatusCode(201)->setJSON(['id' => $id, 'message' => 'Created']);
    }

    public function update($id = null)
    {
        if (!$this->userModel->find($id)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }
        $data = $this->request->getJSON(true);

        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        $this->userModel->update($id, $data);
        $user = $this->userModel->find($id);
        $desc = !empty($data['password']) ? "Updated user and changed password: {$user['first_name']} {$user['last_name']}" : "Updated user: {$user['first_name']} {$user['last_name']}";
        (new AuditLogModel())->log('user.updated', $desc);
        return $this->response->setJSON(['message' => 'Updated']);
    }

    public function delete($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }
        $this->userModel->delete($id);
        (new AuditLogModel())->log('user.deleted', "Deleted user: {$user['first_name']} {$user['last_name']} ({$user['email']})");
        return $this->response->setJSON(['message' => 'Deleted']);
    }
}
