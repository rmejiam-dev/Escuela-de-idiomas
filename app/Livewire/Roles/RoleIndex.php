<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleIndex extends Component
{
    use WithPagination;

    public $roleId;
    public $name;
    public $selectedPermissions = [];

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'selectedPermissions' => 'array',
    ];

    public function resetForm()
    {
        $this->reset(['roleId', 'name', 'selectedPermissions']);
        $this->resetValidation();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();

        $this->rules['name'] = 'required|string|max:255|unique:roles,name,' . $id;
    }

    public function save()
    {
        $this->validate();


        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);
        session()->flash('success', 'Rol creado correctamente');


        $this->resetForm();
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'admin') {
            session()->flash('error', 'No se puede eliminar el rol de administrador');
            return;
        }

        if ($role->users()->count() > 0) {
            session()->flash('error', 'No se puede eliminar un rol que tiene usuarios asignados');
            return;
        }

        $role->delete();
        session()->flash('success', 'Rol eliminado correctamente');
    }

    public function render()
    {
        $query = Role::query();

        // if ($this->search) {
        //     $query->where('name', 'like', '%' . $this->search . '%');
        // }

        $roles = $query->paginate(10);
        $permissions = Permission::all();

        return view('livewire.roles.role-index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
