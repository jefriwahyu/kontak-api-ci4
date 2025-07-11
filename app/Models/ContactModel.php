<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use DateTimeZone;

class ContactModel extends Model
{
    protected $table            = 'contacts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = ['name', 'phone_number', 'email', 'alamat', 'avatar', 'grup', 'is_favorite'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $afterFind = ['transformContactData'];

    protected function transformContactData(array $data): array
    {
        if (!isset($data['data']) || empty($data['data'])) {
            return $data;
        }

        $isSingle = isset($data['data']['id']);

        if ($isSingle) {
            $data['data'] = $this->transformSingleContact($data['data']);
        } else {
            foreach ($data['data'] as $key => $contact) {
                $data['data'][$key] = $this->transformSingleContact($contact);
            }
        }
        
        return $data;
    }
    
    private function transformSingleContact(array $contact): array
    {
        $formatToISO = function ($dateString) {
            if (empty($dateString)) return '';
            try {
                return (new DateTime($dateString))->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d\TH:i:s.v\Z');
            } catch (\Exception $e) {
                return '';
            }
        };

        return [
            '_id'       => (string)($contact['id'] ?? ''),
            'nama'      => $contact['name'] ?? '',
            'email'     => $contact['email'] ?? '',
            'no_hp'     => $contact['phone_number'] ?? '',
            'alamat'    => $contact['alamat'] ?? '',
            'grup'      => $contact['grup'] ?? '',
            'avatar'    => $contact['avatar'] ?? '',
            'createdAt' => $formatToISO($contact['created_at']),
            'updatedAt' => $formatToISO($contact['updated_at']),
        ];
    }
}