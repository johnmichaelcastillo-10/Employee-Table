<div class="mt-5 px-5 py-5">
    <div class="mx-5 px-5">
        <div class="d-flex justify-content-between mb-4">
            <div class="input-group w-50">
                <!-- Search input -->
                <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Search...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>

            <!-- Add New Employee Button -->
            <button wire:click.prevent="$set('showAddEmployeeModal', true)" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add New Employee
            </button>
        </div>

        <!-- Employee Table -->
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center w-25">Name</th>
                    <th scope="col" class="text-center w-25">Email</th>
                    <th scope="col" class="text-center w-25">Role</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <!-- Loop through employees -->
                @foreach ($employees as $employee)
                    <tr wire:key="{{ $employee->id }}">
                        <th scope="row" class="text-center">{{ $employee->id }}</th>
                        <td scope="row" class="text-center w-25">{{ $employee->name }}</td>
                        <td scope="row" class="text-center w-25">{{ $employee->email }}</td>
                        <td scope="row" class="text-center w-25">{{ $employee->role }}</td>
                        <td scope="row" class="text-center">
                            <button class="btn btn-secondary" wire:click.prevent="editEmployee({{ $employee->id }})">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>

                            <button class="btn btn-primary" wire:click.prevent="viewEmployee({{ $employee->id }})">
                                <i class="bi bi-person"></i> View
                            </button>

                            <button wire:click.prevent="confirmDelete({{ $employee->id }})" class="btn btn-danger">
                                <i class="bi bi-trash3-fill"></i> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
                <!-- End loop -->
            </tbody>
        </table>

        <!-- View Employee Modal -->
        @if ($showViewEmployeeModal && $employeeToView)
            <div class="modal show" tabindex="-1" role="dialog" style="display: block;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <!-- Modal header and content -->
                        <div class="modal-header">
                            <h5 class="modal-title">View Employee Profile</h5>
                            <button type="button" class="btn-close"
                                wire:click.prevent="$set('showViewEmployeeModal', false)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Display Employee Details -->
                            <p><strong>Name:</strong> {{ $employeeToView->name }}</p>
                            <p><strong>Age:</strong> {{ $employeeToView->age }}</p>
                            <p><strong>Email:</strong> {{ $employeeToView->email }}</p>
                            <p><strong>Role:</strong> {{ $employeeToView->role }}</p>
                            <!-- Add more details if needed -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop show" wire:click.prevent="$set('showViewEmployeeModal', false)"></div>
        @endif

        <!-- Add Employee Modal -->
        @if ($showAddEmployeeModal)
            <div class="modal show" tabindex="-1" role="dialog" style="display: block;"
                wire:key="showAddEmployeeModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <!-- Modal header, form, and buttons -->
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Employee</h5>
                            <button type="button" class="btn-close"
                                wire:click.prevent="$set('showAddEmployeeModal', false)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form content -->
                            <form wire:submit.prevent="addEmployee">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input wire:model="name" type="text" class="form-control" id="name"
                                        placeholder="Enter name">
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select wire:model="role" class="form-select" id="role">
                                        <option value="">Select Role</option>
                                        <option value="Senior Developer">Senior Developer</option>
                                        <option value="Junior Developer">Junior Developer</option>
                                        <option value="Project Manager">Project Manager</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input wire:model="email" type="email" class="form-control" id="email"
                                        placeholder="Enter email">
                                </div>
                            </form>
                        </div>
                        <!-- Submit and Cancel buttons -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" wire:click.prevent="addEmployee">Add
                                Employee</button>
                            <button type="button" class="btn btn-secondary"
                                wire:click.prevent="$set('showAddEmployeeModal', false)">Cancel</button>
                        </div>
                        <!-- End of modal content -->
                    </div>
                </div>
            </div>
            <div class="modal-backdrop show" wire:click.live="$set('showAddEmployeeModal', false)"
                wire:key="showAddEmployeeModalBackdrop"></div>
        @endif

        <!-- Edit Employee Modal -->
        @if ($showEditEmployeeModal && $employeeToEdit)
            <div class="modal show" tabindex="-1" role="dialog"
                style="display: block; background-color: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="background-color: #fff; border-radius: 8px;">
                        <div class="modal-header" style="border-bottom: none;">
                            <h5 class="modal-title">Edit Employee</h5>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="updateEmployee">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Name</label>
                                    <input wire:model="name" type="text" class="form-control" id="edit_name"
                                        value="{{ $employeeToEdit->name }}">
                                </div>

                                <div class="mb-3">
                                    <label for="edit_role" class="form-label">Role</label>
                                    <select wire:model="role" class="form-select" id="edit_role">
                                        <option value="">Select Role</option>
                                        <option value="Senior Developer">Senior Developer</option>
                                        <option value="Junior Developer">Junior Developer</option>
                                        <option value="Project Manager">Project Manager</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_email" class="form-label">Email</label>
                                    <input wire:model="email" type="email" class="form-control" id="edit_email"
                                        value="{{ $employeeToEdit->email }}">
                                </div>
                                <!-- Update Employee Button -->
                                <div class="modal-footer" style="border-top: none;">
                                    <button type="submit" class="btn btn-primary"
                                        wire:click.prevent="updateEmployee">Update Employee</button>
                                    <button type="button" class="btn btn-secondary"
                                        wire:click.prevent="cancelEditAndClearFields">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop show" wire:click.prevent="$set('showEditEmployeeModal', false)"></div>
        @endif

        <!-- Deletion Confirmation Modal -->
        @if ($confirmingDelete)
            <div class="modal show d-flex align-items-center justify-content-center"
                style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5);"
                x-data="{ showModal: true }" x-init="$watch('showModal', value => { if (!value) { $wire.set('confirmingDelete', false) } })" x-on:click="showModal = false"
                wire:key="confirmingDeleteModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Deletion</h5>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete {{ $employeeToDelete->name }}?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click.prevent="$set('confirmingDelete', false)">Cancel</button>
                            <button type="button" class="btn btn-danger"
                                wire:click.prevent="delete({{ $employeeToDelete->id }})">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Per Page Dropdown -->
        <div class="mb-4 d-flex justify-content-start">
            <div class="input-group w-auto">
                <label class="input-group-text" for="perPageSelect">Per Page:</label>
                <select class="form-select" id="perPageSelect" wire:model.live="perPage">
                    <option selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- Pagination -->
        {{ $employees->links('vendor.livewire.bootstrap') }}
    </div>
</div>
