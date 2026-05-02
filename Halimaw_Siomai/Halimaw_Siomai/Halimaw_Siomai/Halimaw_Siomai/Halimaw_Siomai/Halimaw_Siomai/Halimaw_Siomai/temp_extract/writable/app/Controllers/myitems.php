<?php

namespace App\Controllers;
use App\Models\ItemModel;
use CodeIgniter\Controller;

class myitems extends Controller
{
    public function index()
    {
        $session = session();

        // ✅ make sure user is logged in
        if (! $session->get('logged_in')) {
            return redirect()->to('/');
        }

        $itemModel = new ItemModel();
        $data['items'] = $itemModel->findAll();

        return view('items_list', $data);
    }

    public function add()
    {
        $itemModel = new ItemModel();

        $data = [
            'item_name'       => $this->request->getPost('item_name'),
            'quantity'        => $this->request->getPost('quantity'),
            'expiration_date' => $this->request->getPost('expiration_date'),
            'status'          => 'active',
        ];

        $itemModel->save($data);
        return redirect()->to('/items');
    }
}
