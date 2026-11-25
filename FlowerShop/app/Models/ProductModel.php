<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    // Name of the database table this model manages
    protected $table = 'products';

    // Primary key column in the products table
    protected $primaryKey = 'id';

    // Automatically return rows as associative arrays
    protected $returnType = 'array';

    // Fields allowed to be inserted or updated via save()/insert()/update()
    protected $allowedFields = [
        'name',
        'sku',
        'price',
        'stock_quantity',
        'image',
    ];

    // Automatically populate created_at and updated_at if those columns exist
    protected $useTimestamps = true;
}
?>