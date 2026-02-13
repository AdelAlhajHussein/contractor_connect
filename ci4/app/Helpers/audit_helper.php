<?php

use CodeIgniter\Database\Config;

function audit_log(string $action, ?string $entityType = null, ?int $entityId = null, ?string $details = null): void
{
    $session = session();
    $adminId = (int) ($session->get('user_id') ?? 0);

    // If not logged in or not admin, skip
    if ($adminId <= 0) {
        return;
    }

    $request = service('request');
    $db = Config::connect();

    $db->table('admin_activity_logs')->insert([
        'admin_id'    => $adminId,
        'action'      => $action,
        'entity_type' => $entityType,
        'entity_id'   => $entityId,
        'details'     => $details,
        'ip_address'  => $request->getIPAddress(),
        'user_agent'  => substr((string)$request->getUserAgent(), 0, 255),
        'created_at'  => date('Y-m-d H:i:s'),
    ]);
}
