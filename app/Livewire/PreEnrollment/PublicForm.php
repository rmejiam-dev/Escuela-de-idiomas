<?php

namespace App\Livewire\PreEnrollment;

use App\Models\PreEnrollment;
use Livewire\Component;

class PublicForm extends Component
{
    public $full_name;
    public $email;
    public $phone;
    public $identification_number;
    public $program_interest;
    public $message;
    public $captcha = '';
    public $captcha_text = '';
    public $captcha_result = 0;

    protected $rules = [
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'identification_number' => 'required|string|max:20',
        'program_interest' => 'required|string',
        'message' => 'nullable|string|max:1000',
        'captcha' => 'required|string',
    ];

    public function mount()
    {
        $this->generateCaptcha();
    }

    public function generateCaptcha()
    {
        $num1 = rand(1, 20);
        $num2 = rand(1, 20);
        $this->captcha_text = $num1 . ' + ' . $num2;
        $this->captcha_result = $num1 + $num2;
    }

    public function save()
    {
        $this->validate();

        if ((int)$this->captcha !== $this->captcha_result) {
            $this->addError('captcha', 'El resultado del captcha es incorrecto');
            $this->generateCaptcha();
            $this->captcha = '';
            return;
        }

        PreEnrollment::create([
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'identification_number' => $this->identification_number,
            'program_interest' => $this->program_interest,
            'message' => $this->message,
            'request_ip' => request()->ip(),
            'status' => 'pending',
        ]);

        session()->flash('success', 'Preinscripcion enviada correctamente. Nos contactaremos contigo pronto.');
        
        $this->reset(['full_name', 'email', 'phone', 'identification_number', 'program_interest', 'message', 'captcha']);
        $this->generateCaptcha();
    }

    public function render()
    {
        return view('livewire.pre-enrollment.public-form')->layout('layouts.guest');
    }
}