<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password = '';
    public $password_confirmation = '';
    public $phone;
    public $address;
    public $identification_number;
    public $is_active = true;
    public $selectedRoles = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'identification_number' => 'required|string|validate_rut|unique:users,identification_number',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'password' => 'required|string|min:8|confirmed',
        'selectedRoles' => 'required|array|min:1',
    ];

    protected $messages = [
        'identification_number.required' => 'El número de identificación es obligatorio',
        'identification_number.unique' => 'Este número de identificación ya está registrado',
        'identification_number.validate_rut' => 'Revise su información y vuelva a intentarlo (formato: 12345678-9)',
        'selectedRoles.required' => 'Debe seleccionar al menos un rol',
        'selectedRoles.min' => 'Debe seleccionar al menos un rol',
    ];

    public function mount($userId = null)
    {
        if ($userId) {
            $this->userId = $userId;
            $user = User::findOrFail($userId);
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->address = $user->address;
            $this->identification_number = $user->identification_number;
            $this->is_active = (bool) $user->is_active;            
            $this->selectedRoles = $user->roles->pluck('name')->toArray();
        }
    }
    public function toggleStatus()
    {
        $this->is_active = !$this->is_active;
    }

    public function save()
    {
        if ($this->userId) {
            $this->rules['password'] = 'nullable|string|min:8|confirmed';
            $this->rules['email'] = 'required|email|unique:users,email,' . $this->userId;
            $this->rules['identification_number'] = 'required|string|validate_rut|unique:users,identification_number,' . $this->userId;
        }
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'identification_number' => $this->formatIdentificationNumber($this->identification_number),
            'is_active' => (bool) $this->is_active,
        ];

        // Solo incluir contraseña si se proporcionó
        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->update($userData);
            $user->syncRoles($this->selectedRoles);
            session()->flash('success', 'Usuario actualizado correctamente');
        } else {
            $userData['password'] = Hash::make($this->password);
            $user = User::create($userData);
            $user->assignRole($this->selectedRoles);
            session()->flash('success', 'Usuario creado correctamente');
        }

        return redirect()->route('users.index');
    }

    // Método para validar el RUT chileno
    protected function validateRut($rut)
    {
        // Limpiar el RUT
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $rut = strtoupper($rut);

        // Separar número y dígito verificador
        $numero = substr($rut, 0, -1);
        $dv_ingresado = substr($rut, -1);

        // Validar formato
        if (!is_numeric($numero) || strlen($numero) < 6) {
            return false;
        }

        // Calcular dígito verificador
        $suma = 0;
        $multiplo = 2;

        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += $numero[$i] * $multiplo;
            $multiplo = $multiplo == 7 ? 2 : $multiplo + 1;
        }

        $dv_esperado = 11 - ($suma % 11);
        if ($dv_esperado == 11) $dv_esperado = 0;
        if ($dv_esperado == 10) $dv_esperado = 'K';

        // Comparar
        return $dv_esperado == $dv_ingresado;
    }

    // Formatear el RUT para almacenar (opcional)
    protected function formatIdentificationNumber($rut)
    {
        // Limpiar el RUT
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $rut = strtoupper($rut);

        // Formatear como 12345678-9
        if (strlen($rut) > 1) {
            $numero = substr($rut, 0, -1);
            $dv = substr($rut, -1);
            return $numero . '-' . $dv;
        }

        return $rut;
    }

    public function render()
    {
        return view('livewire.users.user-form', [
            'roles' => Role::all(),
        ]);
    }
}
