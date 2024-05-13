<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Employee;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $confirmingDelete = false;
    public $employeeToDelete;
    public $showAddEmployeeModal = false;
    public $showEditEmployeeModal = false;
    public $showViewEmployeeModal = false;
    public $selectedEmployee;
    public $employeeToEdit;
    public $employeeToView;
    public $name;
    public $role;
    public $email;

    public function editEmployee($employeeId)
    {
        $employeeToEdit = Employee::find($employeeId);

        if ($employeeToEdit) {
            $this->employeeToEdit = $employeeToEdit;
            $this->name = $employeeToEdit->name;
            $this->role = $employeeToEdit->role;
            $this->email = $employeeToEdit->email;

            $this->showEditEmployeeModal = true;
        }
    }

    public function viewEmployee($employeeId)
    {
        $employeeToView = Employee::find($employeeId);

        if ($employeeToView) {
            $this->employeeToView = $employeeToView;
            $this->showViewEmployeeModal = true;
        }
    }

    public function cancelEditAndClearFields()
    {
        $this->clearEditModalFields();
        $this->showEditEmployeeModal = false;
    }

    public function updateEmployee()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'role' => 'required',
            'email' => 'required',
        ]);

        if ($this->employeeToEdit) {
            try {
                $this->employeeToEdit->fill($validatedData);
                $this->employeeToEdit->save();
                Log::info('Employee updated successfully!');
            } catch (\Exception $e) {
                Log::error('Error updating employee: ' . $e->getMessage());
            }
            $this->clearEditModalFields();
        }
    }

    public function delete($employeeId)
    {
        $employee = Employee::find($employeeId);
        if ($employee) {
            $employee->delete();
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($employeeId)
    {
        $this->employeeToDelete = Employee::find($employeeId);
        $this->confirmingDelete = true;
    }

    public function addEmployee()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'role' => 'required',
            'email' => 'required',
        ]);

        Employee::create($validatedData);
        $this->resetAddModalFields();
    }

    protected function clearEditModalFields()
    {
        $this->reset([
            'name', 'role', 'email',
            'showEditEmployeeModal', 'employeeToEdit'
        ]);
    }

    protected function resetAddModalFields()
    {
        $this->reset([
            'name', 'role', 'email',
            'showAddEmployeeModal'
        ]);
    }

    public function render()
    {
        $employees = Employee::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate($this->perPage);

        return view('livewire.table', compact('employees'));
    }
}
