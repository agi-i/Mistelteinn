<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomerController extends BaseController
{
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    // READ: List all customers ordered by full_name
    public function index()
    {
        $customers = $this->customerModel->orderBy('full_name', 'ASC')->findAll();

        return view('customers/index', [
            'customers' => $customers,
        ]);
    }

    // CREATE form
    public function new()
    {
        return view('customers/form', [
            'customer' => null,
        ]);
    }

    // CREATE action: Validate and save a new customer
    public function create()
    {
        $rules = [
            'full_name' => 'required|min_length[2]|max_length[150]',
            'email'     => 'permit_empty|valid_email|max_length[150]',
            'phone'     => 'permit_empty|max_length[40]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customerModel->insert([
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ]);

        return redirect()->to('/customers')->with('message', 'Customer added successfully.');
    }

    // UPDATE form
    public function edit($id = null)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to('/customers')->with('error', 'Customer not found.');
        }

        return view('customers/form', [
            'customer' => $customer,
        ]);
    }

    // UPDATE action: Validate and save changes to an existing customer
    public function update($id = null)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to('/customers')->with('error', 'Customer not found.');
        }

        $rules = [
            'full_name' => 'required|min_length[2]|max_length[150]',
            'email'     => 'permit_empty|valid_email|max_length[150]',
            'phone'     => 'permit_empty|max_length[40]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customerModel->update($id, [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ]);

        return redirect()->to('/customers')->with('message', 'Customer updated successfully.');
    }

    // DELETE action: Remove a customer by ID
    public function delete($id = null)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to('/customers')->with('error', 'Customer not found.');
        }

        $this->customerModel->delete($id);

        return redirect()->to('/customers')->with('message', 'Customer deleted successfully.');
    }
}