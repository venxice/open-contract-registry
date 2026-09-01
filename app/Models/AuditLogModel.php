<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'user_name', 'action', 'description', 'ip_address', 'user_agent', 'page_url'];
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function log(string $action, string $description = '', ?int $userId = null, ?string $userName = null)
    {
        $request = service('request');
        $ip = $request->getIPAddress() ?? '';
        $agent = $request->getServer('HTTP_USER_AGENT') ?? '';
        $url = $request->getUri()->getPath() ?? '';

        $session = session();
        if (!$userId) $userId = $session->get('user_id');
        if (!$userName) $userName = $session->get('user_name');

        $this->insert([
            'user_id' => $userId,
            'user_name' => $userName,
            'action' => $action,
            'description' => $description,
            'ip_address' => $ip,
            'user_agent' => $agent,
            'page_url' => $url,
        ]);
    }

    public function getLogs(int $perPage = 20, string $search = '', string $dateFrom = '', string $dateTo = '')
    {
        $builder = $this->db->table('audit_logs');
        $builder->orderBy('created_at', 'DESC');

        if ($search) {
            $builder->groupStart();
            $builder->like('user_name', $search);
            $builder->orLike('action', $search);
            $builder->orLike('description', $search);
            $builder->groupEnd();
        }

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        $total = $builder->countAllResults(false);
        $page = (int)($_GET['page'] ?? 0);
        $results = $builder->limit($perPage, $page * $perPage)->get()->getResultArray();

        return ['data' => $results, 'total' => $total];
    }
}
