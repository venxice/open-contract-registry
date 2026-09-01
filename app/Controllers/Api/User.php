<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

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
        return $this->response->setJSON(['message' => 'Updated']);
    }

    public function delete($id = null)
    {
        if (!$this->userModel->find($id)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }
        $this->userModel->delete($id);
        return $this->response->setJSON(['message' => 'Deleted']);
    }
}
