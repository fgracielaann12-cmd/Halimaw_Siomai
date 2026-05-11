<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToItems extends Migration
{
    public function up()
    {
        $fields = [
            'image_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'sku'
            ],
            'subcategory' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'category'
            ],
            'pack_small_qty' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'status'
            ],
            'pack_medium_qty' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'pack_small_qty'
            ],
            'pack_biggest_qty' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'pack_medium_qty'
            ],
            'pack_small_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 115.00,
                'after'      => 'pack_biggest_qty'
            ],
            'pack_medium_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 185.00,
                'after'      => 'pack_small_price'
            ],
            'pack_biggest_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 335.00,
                'after'      => 'pack_medium_price'
            ],
            'is_expiring_seen' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'pack_biggest_price'
            ],
            'is_expired_seen' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'is_expiring_seen'
            ],
            'is_variation_child' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'is_expired_seen'
            ],
            'variation_group_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'is_variation_child'
            ],
            'variation_label' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'variation_group_id'
            ],
        ];

        foreach ($fields as $name => $config) {
            if (!$this->db->fieldExists($name, 'items')) {
                $this->forge->addColumn('items', [$name => $config]);
            }
        }
    }

    public function down()
    {
        $columns = [
            'image_path',
            'subcategory',
            'pack_small_qty',
            'pack_medium_qty',
            'pack_biggest_qty',
            'pack_small_price',
            'pack_medium_price',
            'pack_biggest_price',
            'is_expiring_seen',
            'is_expired_seen',
            'is_variation_child',
            'variation_group_id',
            'variation_label'
        ];

        foreach ($columns as $column) {
            if ($this->db->fieldExists($column, 'items')) {
                $this->forge->dropColumn('items', $column);
            }
        }
    }
}
