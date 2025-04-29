<?php

namespace App\Audit;

use App\Services\Security;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Drivers\Database;

class SignedDatabaseAuditDriver extends Database
{
    /**
     * {@inheritdoc}
     */
    public function audit(Auditable $model): ?Audit
    {
        // Get the audit data from the model
        $auditData = $model->toAudit();
        
        // Generate SHA512 signature
        $signatureData = [
            'user_type' => $auditData['user_type'],
            'user_id' => $auditData['user_id'],
            'event' => $auditData['event'],
            'auditable_type' => $auditData['auditable_type'],
            'auditable_id' => $auditData['auditable_id'],
            'old_values' => json_encode($auditData['old_values']),
            'new_values' => json_encode($auditData['new_values']),
            'url' => $auditData['url'],
            'ip_address' => $auditData['ip_address'],
            'user_agent' => $auditData['user_agent'],
            'tags' => $auditData['tags'],
        ];
        
        // Add the signature to the audit data
        $auditData['SHASignature'] = Security::protectData($signatureData);
        
        // Create the audit using the modified data
        return call_user_func([get_class($model->audits()->getModel()), 'create'], $auditData);
    }
}