<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AuditLogModel;

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

        $this->setSession($user);

        (new AuditLogModel())->log('login', 'Signed in via email/password', $user['user_id'], $user['first_name'] . ' ' . $user['last_name']);

        return $this->response->setJSON(['success' => true, 'user' => $user['first_name'] . ' ' . $user['last_name']]);
    }

    public function googleCallback()
    {
        $code = $this->request->getGet('code');
        $error = $this->request->getGet('error');

        if ($error) {
            return redirect()->to('/admin/login?error=' . urlencode($error));
        }

        if (!$code) {
            return redirect()->to('/admin/login?error=' . urlencode('No authorization code received'));
        }

        $clientId = env('app.googleClientId', '');
        $clientSecret = env('app.googleClientSecret', '');
        $redirectUri = base_url('api/auth/google/callback');

        $tokenData = $this->exchangeCode($code, $clientId, $clientSecret, $redirectUri);
        if (!$tokenData || !isset($tokenData['id_token'])) {
            return redirect()->to('/admin/login?error=' . urlencode('Failed to authenticate with Google'));
        }

        $payload = $this->decodeIdToken($tokenData['id_token']);
        if (!$payload || !isset($payload['email'])) {
            return redirect()->to('/admin/login?error=' . urlencode('Invalid Google token'));
        }

        $email = $payload['email'];
        $givenName = $payload['given_name'] ?? '';
        $familyName = $payload['family_name'] ?? '';

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            $randomPassword = bin2hex(random_bytes(16));
            $userId = $userModel->insert([
                'first_name' => $givenName,
                'last_name' => $familyName,
                'middle_initial' => '',
                'email' => $email,
                'password' => password_hash($randomPassword, PASSWORD_DEFAULT),
                'role' => 'Viewer',
                'status' => 'ACTIVE',
            ]);
            if (!$userId) {
                return redirect()->to('/admin/login?error=' . urlencode('Failed to create account'));
            }
            $user = $userModel->find($userId);
        }

        if ($user['status'] !== 'ACTIVE') {
            return redirect()->to('/admin/login?error=' . urlencode('Account is inactive'));
        }

        $this->setSession($user);

        (new AuditLogModel())->log('login', 'Signed in via Google SSO', $user['user_id'], $user['first_name'] . ' ' . $user['last_name']);

        return redirect()->to('/admin');
    }

    public function logout()
    {
        $session = session();
        (new AuditLogModel())->log('logout', 'Signed out');
        $session->destroy();
        return $this->response->setJSON(['success' => true]);
    }

    public function check()
    {
        $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $this->response->setHeader('Pragma', 'no-cache');

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

    private function setSession(array $user)
    {
        $session = session();
        $session->regenerate(true);
        $session->set('user_id', $user['user_id']);
        $session->set('user_name', $user['first_name'] . ' ' . $user['last_name']);
        $session->set('user_role', $user['role']);
        $session->set('logged_in', true);
        $session->set('last_activity', time());
    }

    private function exchangeCode(string $code, string $clientId, string $clientSecret, string $redirectUri): ?array
    {
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ]),
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) return null;
        return json_decode($response, true);
    }

    private function decodeIdToken(string $idToken): ?array
    {
        $parts = explode('.', $idToken);
        if (count($parts) !== 3) return null;

        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
        if (!$payload) return null;

        if (isset($payload['exp']) && $payload['exp'] < time()) return null;

        $clientId = env('app.googleClientId', '');
        if (!empty($clientId) && isset($payload['aud']) && $payload['aud'] !== $clientId) return null;

        return $payload;
    }
}
