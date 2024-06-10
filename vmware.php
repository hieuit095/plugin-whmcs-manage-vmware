<?php

function vmware_ConfigOptions(): array
{
    return [
        'Url' => [
            'Type' => 'text',
            'Size' => '64',
            'Default' => '',
            'Description' => 'Vcenter Url',
        ],
        'User' => [
            'Type' => 'text',
            'Size' => '64',
            'Default' => '',
            'Description' => 'Vcenter User',
        ],
        'Password' => [
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Vcenter password',
        ]
    ];
}

function vmware_TerminateAccount(array $params): string
{
    $vm = vmware_vm($params);
    if ($vm->machine) {
        $vm->stop();
        return 'success';
    }
    return 'An error occurred';
}

function vmware_SuspendAccount(array $params): string
{
    $vm = vmware_vm($params);
    if ($vm->machine) {
        $vm->stop();
        return 'success';
    }
    return 'An error occurred';
}

function vmware_UnsuspendAccount(array $params): string
{
    $vm = vmware_vm($params);
    if ($vm->machine) {
        $vm->start();
        return 'success';
    }
    return 'An error occurred';
}

function vmware_CreateAccount(array $params): string
{
    $vm = vmware_vm($params);
    $config = [
        'name' => $params['customfields']['VMName'] ?? 'New_VM',
        'cpu' => $params['customfields']['vCPU'] ?? 2,
        'memory' => $params['customfields']['RAM'] ?? 4096,
        'disk' => $params['customfields']['Disk'] ?? 100,
        // Thêm các thông số khác nếu cần
    ];
    $response = $vm->createVM($config);
    return 'success';
}

function vmware_DeleteAccount(array $params): string
{
    $vm = vmware_vm($params);
    if ($vm->machine) {
        $vm->deleteVM($vm->machine['vm']);
        return 'success';
    }
    return 'An error occurred';
}

function vmware_AdminServicesTabFields(array $params): void
{
    $vm = vmware_vm($params);
    if ($vm->machine) {
        echo "Vm exists and status: {$vm->machine['power_state']}.";
    } else {
        echo "VM does not exist.";
    }
}

function vmware_send_suspend(array $params, string $message): void
{
    // Implement the function if needed
}

function vmware_vm(array $params): Client
{
    require_once 'Client.php';
    $ip = $params['customfields']['VMIP'] ?? throw new InvalidArgumentException('VMIP custom field is required');
    $url = $params['configoption1'] ?? throw new InvalidArgumentException('Vcenter URL is required');
    $username = $params['configoption2'] ?? throw new InvalidArgumentException('Vcenter User is required');
    $password = $params['configoption3'] ?? throw new InvalidArgumentException('Vcenter Password is required');

    return (new Client($url, $username, $password))->find($ip);
}
