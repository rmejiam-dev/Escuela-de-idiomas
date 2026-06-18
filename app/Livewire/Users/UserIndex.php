<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $role = '';
    public $status = '';
    public $perPage = 10;

    protected $queryString = ['search', 'role', 'status'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRole()
    {
        $this->resetPage();
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);
        $this->dispatch('toast', type: 'success', message: 'Estado actualizado correctamente');
    }

    public function delete($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propio usuario');
            return;
        }
        $user->delete();
        session()->flash('success', 'Usuario eliminado correctamente');
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('identification_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->role) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->role);
            });
        }

        if ($this->status === 'active') {
            $query->where('is_active', true);
        } elseif ($this->status === 'inactive') {
            $query->where('is_active', false);
        }

        $users = $query->with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.users.user-index', [
            'users' => $users,
            'roles' => \Spatie\Permission\Models\Role::all(),
        ]);
    }
}
