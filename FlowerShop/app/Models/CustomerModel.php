<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    // Database table for customers
    protected $table = 'customers';

    // Primary key column
    protected $primaryKey = 'id';

    // Return arrays for easy view rendering
    protected $returnType = 'array';

    // Columns allowed to be written by the model
    protected $allowedFields = [
        'name',
        'email',
        'phone',
    ];

    protected $useTimestamps = true;
}