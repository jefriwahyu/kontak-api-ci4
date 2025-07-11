<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFavoriteToContacts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('contacts', [
            'is_favorite' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'grup'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('contacts', 'is_favorite');
    }
}