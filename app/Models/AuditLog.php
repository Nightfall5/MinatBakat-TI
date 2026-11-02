<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $action, string $description, ?string $entityType = null, ?int $entityId = null, ?array $metadata = null): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 500),
            'metadata' => self::sanitizeMetadata($metadata),
            'created_at' => now(),
        ]);
    }

    /**
     * Remove sensitive fields such as passwords and tokens from metadata before logging.
     */
    public static function sanitizeMetadata(?array $metadata): ?string
    {
        if (empty($metadata)) {
            return null;
        }

        $redactedKeys = [
            'password', 'password_confirmation', 'secret', 'token',
            'api_key', 'access_token', 'refresh_token', 'authorization',
            'credit_card', 'pin', 'cookie'
        ];

        $sanitized = self::maskRecursive($metadata, $redactedKeys);

        return json_encode($sanitized);
    }

    private static function maskRecursive(array $data, array $redactedKeys): array
    {
        foreach ($data as $key => $value) {
            $lowerKey = strtolower((string) $key);
            $matched = false;
            foreach ($redactedKeys as $redacted) {
                if (str_contains($lowerKey, $redacted)) {
                    $matched = true;
                    break;
                }
            }

            if ($matched) {
                $data[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $data[$key] = self::maskRecursive($value, $redactedKeys);
            }
        }

        return $data;
    }
}
