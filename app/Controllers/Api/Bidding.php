<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BiddingModel;
use App\Models\ProjectModel;
use App\Models\DescriptionModel;
use App\Models\AuditLogModel;

class Bidding extends BaseController
{
    protected $biddingModel;
    protected $projectModel;
    protected $descriptionModel;

    public function __construct()
    {
        $this->biddingModel = new BiddingModel();
        $this->projectModel = new ProjectModel();
        $this->descriptionModel = new DescriptionModel();
    }

    public function index()
    {
        $biddings = $this->biddingModel->findAll();
        $data = [];
        foreach ($biddings as $bidding) {
            $bidding['projects'] = $this->getProjects($bidding['bidding_id']);
            $data[] = $bidding;
        }
        return $this->response->setJSON($data);
    }

    public function show($id = null)
    {
        $bidding = $this->biddingModel->find($id);
        if (!$bidding) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }
        $bidding['projects'] = $this->getProjects($id);
        return $this->response->setJSON($bidding);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        
        $db = \Config\Database::connect();
        $db->transException(true)->transStart();

        $biddingId = $this->biddingModel->insert([
            'contractor' => $data['contractor'],
            'amount' => $data['amount'],
            'notice_date' => $data['notice_date'] ?? null,
            'contract_number' => $data['contract_number'] ?? null,
            'contract_date' => $data['contract_date'] ?? null,
            'notice_proceed' => $data['notice_proceed'] ?? null,
        ]);

        if (!$biddingId) {
            $db->transRollback();
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Failed to create bidding']);
        }

        foreach ($data['projectTitles'] as $title) {
            $projectId = $this->projectModel->insert([
                'bidding_id' => $biddingId,
                'project_title' => $title['title'],
            ]);

            foreach ($title['descriptions'] as $desc) {
                $attachment = '';
                if (!empty($desc['fileData'])) {
                    $attachment = $desc['fileData'];
                }
                $this->descriptionModel->insert([
                    'project_id' => $projectId,
                    'project_description' => $desc['description'] ?? '',
                    'date_posted' => $desc['date_posted'] ?? null,
                    'project_attachment' => $attachment,
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Transaction failed']);
        }

        (new AuditLogModel())->log('bidding.created', "Created bidding record: {$data['contractor']} (Contract #{$data['contract_number']})");
        return $this->response->setStatusCode(201)->setJSON(['id' => $biddingId, 'message' => 'Created']);
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        
        if (!$this->biddingModel->find($id)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->biddingModel->update($id, [
            'contractor' => $data['contractor'],
            'amount' => $data['amount'],
            'notice_date' => $data['notice_date'] ?? null,
            'contract_number' => $data['contract_number'] ?? null,
            'contract_date' => $data['contract_date'] ?? null,
            'notice_proceed' => $data['notice_proceed'] ?? null,
        ]);

        $this->projectModel->where('bidding_id', $id)->delete();

        foreach ($data['projectTitles'] as $title) {
            $projectId = $this->projectModel->insert([
                'bidding_id' => $id,
                'project_title' => $title['title'],
            ]);

            foreach ($title['descriptions'] as $desc) {
                $attachment = '';
                if (!empty($desc['fileData'])) {
                    $attachment = $desc['fileData'];
                }
                $this->descriptionModel->insert([
                    'project_id' => $projectId,
                    'project_description' => $desc['description'] ?? '',
                    'date_posted' => $desc['date_posted'] ?? null,
                    'project_attachment' => $attachment,
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Transaction failed']);
        }

        (new AuditLogModel())->log('bidding.updated', "Updated bidding record: {$data['contractor']} (Contract #{$data['contract_number']})");
        return $this->response->setJSON(['message' => 'Updated']);
    }

    public function delete($id = null)
    {
        $bidding = $this->biddingModel->find($id);
        if (!$bidding) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }

        $this->biddingModel->delete($id);
        (new AuditLogModel())->log('bidding.deleted', "Deleted bidding record: {$bidding['contractor']} (Contract #{$bidding['contract_number']})");
        return $this->response->setJSON(['message' => 'Deleted']);
    }

    protected function getProjects($biddingId)
    {
        $projects = $this->projectModel->where('bidding_id', $biddingId)->findAll();
        foreach ($projects as &$project) {
            $project['descriptions'] = $this->descriptionModel->where('project_id', $project['project_id'])->findAll();
        }
        return $projects;
    }
}
