<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class Contacts extends ResourceController
{
    protected $modelName = 'App\Models\ContactModel';
    protected $format    = 'json';

    public function index()
    {
        $model = new $this->modelName();
        $data = $model->findAll();
        $response = [
            'success' => true,
            'message' => 'Semua data kontak berhasil diambil.',
            'count'   => count($data),
            'data'    => $data
        ];
        return $this->respond($response);
    }

    public function show($id = null)
    {
        $model = new $this->modelName();
        $contact = $model->find($id);
        if ($contact) {
            $response = ['success' => true, 'data' => $contact];
            return $this->respond($response);
        }
        return $this->failNotFound('Kontak tidak ditemukan.');
    }

    public function create()
    {
        $model = new $this->modelName();
        $data = $this->request->getJSON(true);

        // --- PERBAIKAN DI SINI ---
        $mappedData = [
            'name'         => $data['nama'] ?? null,
            'phone_number' => $data['no_hp'] ?? null, // Diubah dari noHp menjadi no_hp
            'email'        => $data['email'] ?? '',
            'alamat'       => $data['alamat'] ?? '',
            'grup'         => $data['grup'] ?? '',
            'avatar'       => $data['avatar'] ?? ''
        ];

        if ($model->insert($mappedData)) {
            $id = $model->getInsertID();
            $newContact = $model->find($id);
            $response = [
                'success' => true,
                'message' => 'Kontak baru berhasil dibuat.',
                'data'    => $newContact
            ];
            return $this->respondCreated($response);
        }
        return $this->fail(['success' => false, 'message' => $model->errors()], 400);
    }

    public function update($id = null)
    {
        $model = new $this->modelName();
        $data = $this->request->getJSON(true);

        if (!$model->find($id)) {
            return $this->failNotFound('Kontak tidak ditemukan.');
        }

        // --- PERBAIKAN DI SINI ---
        $mappedData = [
            'name'         => $data['nama'] ?? null,
            'phone_number' => $data['no_hp'] ?? null, // Diubah dari noHp menjadi no_hp
            'email'        => $data['email'] ?? '',
            'alamat'       => $data['alamat'] ?? '',
            'grup'         => $data['grup'] ?? '',
            'avatar'       => $data['avatar'] ?? ''
        ];

        if ($model->update($id, $mappedData)) {
            $updatedContact = $model->find($id);
            $response = [
                'success' => true,
                'message' => 'Kontak berhasil diperbarui.',
                'data'    => $updatedContact
            ];
            return $this->respond($response);
        }
        return $this->fail(['success' => false, 'message' => $model->errors()], 400);
    }

    public function delete($id = null)
    {
        $model = new $this->modelName();
        $contact = $model->find($id);
        if ($contact) {
            $model->delete($id);
            $response = ['success' => true, 'message' => 'Kontak berhasil dihapus.', 'data' => $contact];
            return $this->respond($response);
        }
        return $this->failNotFound('Kontak tidak ditemukan.');
    }
    
    public function upload()
    {
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $newName);
            $url = base_url('uploads/' . $newName);
            return $this->respond(['success' => true, 'url' => $url], 200);
        }
        return $this->fail('Gagal mengupload file .', 400);
    }

    public function toggleFavorite($id = null)
    {
        // Cari kontak berdasarkan ID
        $contact = $this->model->find($id);

        if ($contact) {
            // Ubah status is_favorite (jika true jadi false, jika false jadi true)
            $newStatus = !$contact['is_favorite'];
            
            $data = [
                'is_favorite' => $newStatus,
            ];

            // Lakukan update
            $this->model->update($id, $data);
            
            // Beri respons sukses
            return $this->respond([
                'status' => 200,
                'message' => 'Status favorit berhasil diubah.',
                'data' => [
                    'id' => $id,
                    'is_favorite' => $newStatus
                ]
            ]);

        } else {
            return $this->failNotFound('Kontak tidak ditemukan.');
        }
    }
}