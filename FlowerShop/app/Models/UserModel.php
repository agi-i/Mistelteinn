<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Staff / users table
    protected $table = 'users';

    // Primary key column
    protected $primaryKey = 'id';

    // Return associative arrays
    protected $returnType = 'array';

    // Fields permitted for insert/update
    protected $allowedFields = [
        'username',
        'full_name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Model callbacks to automatically hash plain passwords before insert/update
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hash the 'password' field with PHP's password_hash() if provided.
     * Avoids double-hashing if the value is already a password_hash() string.
     */
    protected function hashPassword(array $data): array
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        // If password field is empty during an edit, remove it so existing hash stays intact
        if ($data['data']['password'] === '' || $data['data']['password'] === null) {
            unset($data['data']['password']);
            return $data;
        }

        $info = password_get_info($data['data']['password']);
        if (($info['algo'] ?? null) === null) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}