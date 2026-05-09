<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsVariationChildToItems extends Migration
{
    public function up()
    {
        $fields = [
            'is_variation_child' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            'variation_group_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'variation_label' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('items', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('items', ['is_variation_child', 'variation_group_id', 'variation_label']);
    }
}
