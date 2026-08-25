<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class Upload extends BaseController
{
    public function index()
    {
        $file = $this->request->getFile('file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No file uploaded']);
        }

        if ($file->getClientMimeType() !== 'application/pdf') {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Only PDF files allowed']);
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'File too large (max 10MB)']);
        }

        $newName = 'doc_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
        $file->move(WRITEPATH . 'uploads', $newName, true);

        return $this->response->setJSON([
            'path' => '/uploads/' . $newName,
            'name' => $file->getClientName(),
        ]);
    }
}
