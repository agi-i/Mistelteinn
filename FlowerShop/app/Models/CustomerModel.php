<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    // Matches the 'customers' table in your database
    protected $table = 'customers';

    // Primary key column
    protected $primaryKey = 'id';

    // Return associative arrays
    protected $returnType = 'array';

    // Actual columns in your customers table (excluding auto-managed id and created_at)
    protected $allowedFields = [
        'full_name',
        'email',
        'phone',
    ];

    // Disable automatic updated_at since the table only has created_at
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // or set to '' if your table has no updated_at column
}