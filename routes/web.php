<?php

use App\Livewire\Dashboard;
use App\Livewire\Profile;
use App\Livewire\Users\UserIndex;
use App\Livewire\Users\UserForm;
use App\Livewire\Procedures\ProcedureIndex;
use App\Livewire\Procedures\ProcedureForm;
use App\Livewire\Procedures\PublicProcedureForm;
use App\Livewire\Procedures\ProcedureWorkflow;
use App\Livewire\Procedures\ProcedureReview;
use App\Livewire\Payments\PaymentIndex;
use App\Livewire\Payments\PaymentVerification;
use App\Livewire\PreEnrollment\PublicForm;
use App\Livewire\PreEnrollment\PreEnrollmentList;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Roles\PermissionIndex;
use App\Livewire\Reports\Statistics;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Procedure;
use App\Http\Controllers\CertificateController;

Route::get('/certificate/download/{procedureId}', [CertificateController::class, 'download'])->name('certificate.download');
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('login', function () {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, request()->has('remember'))) {
            request()->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden.',
        ]);
    });

    Route::get('register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('register', function () {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'identification_number' => 'required|string|unique:users',
        ]);

        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'identification_number' => $data['identification_number'],
            'is_active' => false,
        ]);

        $user->assignRole('student');
        Auth::login($user);

        return redirect()->route('dashboard');
    });
});

Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware(['auth', 'user.active'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/profile', Profile::class)->name('profile');

    Route::prefix('users')->group(function () {
        Route::get('/', UserIndex::class)->name('users.index');
        Route::get('/create', UserForm::class)->name('users.create');
        Route::get('/{userId}/edit', UserForm::class)->name('users.edit');
    });

    Route::prefix('procedures')->group(function () {
        Route::get('/', ProcedureIndex::class)->name('procedures.index');
        Route::get('/create', ProcedureForm::class)->name('procedures.create');
        Route::get('/{procedureId}/edit', ProcedureForm::class)->name('procedures.edit');
        Route::get('/{procedureId}/workflow', ProcedureWorkflow::class)->name('procedures.workflow');

        // Route::get('/review', ProcedureReview::class)->name('procedures.review');
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', PaymentIndex::class)->name('payments.index');
        // Route::get('/verify', PaymentVerification::class)->name('payments.verify');
    });

    Route::prefix('pre-enrollments')->group(function () {
        Route::get('/', PreEnrollmentList::class)->name('pre-enrollments.index');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', RoleIndex::class)->name('roles.index');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', Statistics::class)->name('reports.index');
    });
});
Route::get('/certificate/verify/{id}', function ($id) {
    $procedure = Procedure::with('signatures')->findOrFail($id);

    if ($procedure->status !== 'completed') {
        return view('certificate.invalid');
    }

    return view('certificate.verify', compact('procedure'));
})->name('certificate.verify');

Route::get('/pre-enrollment', PublicForm::class)->name('pre-enrollment.public');
