<?php

class Client
{
    private string $url;
    private string $token;
    private array $machine;

    public function __construct(string $url, string $username, string $password)
    {
        $this->url = rtrim($url, '/') . '/rest/';
        $this->initializeToken($username, $password);
    }

    private function initializeToken(string $username, string $password): void
    {
        $response = $this->send("com/vmware/cis/session", [
            'username' => $username,
            'password' => $password
        ], [], [], 'POST');

        $this->token = $response['value'] ?? throw new RuntimeException('Failed to obtain token');
    }

    public function find(string $ip): self
    {
        $hostResponse = $this->get("?filter.names={$ip}");
        $this->machine = array_filter($hostResponse['value'], fn($host) => $host['name'] === $ip) ?? throw new RuntimeException('Machine not found');
        return $this;
    }

    public function createVM(array $config): array
    {
        $response = $this->post('', $config);
        return $response;
    }

    public function deleteVM(string $vmId): void
    {
        $this->delete("/{$vmId}");
    }

    public function reset(): void
    {
        $this->power('reset');
    }

    public function stop(): void
    {
        $this->power('suspend');
    }

    public function start(): void
    {
        $this->power('start');
    }

    private function power(string $action): void
    {
        $this->post("/{$this->machine['vm']}/power/{$action}");
    }

    private function get(string $url): array
    {
        return $this->send("vcenter/vm{$url}", [], [], [
            'vmware-api-session-id: ' . $this->token
        ], 'GET');
    }

    private function post(string $url, array $params = []): array
    {
        return $this->send("vcenter/vm{$url}", [], $params, [
            'vmware-api-session-id: ' . $this->token
        ], 'POST');
    }

    private function delete(string $url): void
    {
        $this->send("vcenter/vm{$url}", [], [], [
            'vmware-api-session-id: ' . $this->token
        ], 'DELETE');
    }

    private function send(string $url, array $auth, array $params, array $headers, string $method = 'GET'): array
    {
        $fullUrl = $this->url . $url;
        $querystring = $params ? '?' . http_build_query($params) : '';

        if ($auth) {
            $headers[] = 'Authorization: Basic ' . base64_encode("{$auth['username']}:{$auth['password']}");
        }

        $fullUrl .= $method === 'GET' ? $querystring : '';
        $ch = curl_init($fullUrl);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => false,
        ]);

        if ($method === 'PUT' || $method === 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return json_decode($response, true) ?? throw new RuntimeException("Request failed with status code {$httpCode}");
    }
}
