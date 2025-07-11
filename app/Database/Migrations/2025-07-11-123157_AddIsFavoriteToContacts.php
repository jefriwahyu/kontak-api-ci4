// app/Database/Migrations/2025-07-11-123157_AddIsFavoriteToContacts.php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsFavoriteToContacts extends Migration
{
    public function up()
    {
        $fields = [
            'is_favorite' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'after'      => 'grup', // Opsional: menempatkan kolom setelah kolom 'grup'
            ],
        ];
        $this->forge->addColumn('contacts', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('contacts', 'is_favorite');
    }
}