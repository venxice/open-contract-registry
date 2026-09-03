<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;

class AuditLog extends BaseController
{
    public function index()
    {
        $model = new AuditLogModel();
        $perPage = 20;
        $page = max(0, (int)($this->request->getGet('page') ?? 0));
        $search = $this->request->getGet('q') ?? '';
        $dateFrom = $this->request->getGet('from') ?? '';
        $dateTo = $this->request->getGet('to') ?? '';

        $result = $model->getLogs($perPage, $page, $search, $dateFrom, $dateTo);

        return $this->response->setJSON([
            'data' => $result['data'],
            'total' => $result['total'],
            'pages' => ceil($result['total'] / $perPage),
            'page' => $page,
        ]);
    }
}
