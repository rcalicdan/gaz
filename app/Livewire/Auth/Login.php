<?php

namespace App\Livewire\Auth;

use App\Services\AuthService;
use App\Traits\DispatchFlashMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    use DispatchFlashMessage;

    public $email = '';

    public $password = '';

    public $remember = false;

    public $showPassword = false;

    protected $authService;

    public function boot(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }
    }

    protected function rules()
    {
        return [
            'email' => 'required|email|max:60',
            'password' => 'required|string|max:60',
        ];
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = ! $this->showPassword;
    }

    public function login()
    {
        $this->validate();

        try {
            $credentials = [
                'email' => $this->email,
                'password' => $this->password,
            ];

            $user = $this->authService->authenticateUser($credentials);

            if (!$user) {
                $this->dispatchFlashMessage('error', 'Nieprawidłowe dane logowania');
                return;
            }

            // Logowanie użytkownika z regeneracją sesji
            Auth::login($user, $this->remember);

            // Regeneracja sesji dla bezpieczeństwa
            request()->session()->regenerate();

            // Próba wygenerowania tokenu API - nie blokuje logowania jeśli się nie powiedzie
            try {
                $token = $this->authService->generateToken($user);
                session(['api_token' => $token]);
            } catch (\Exception $tokenError) {
                \Log::warning('API token was not generated during login. Continuing without token.', [
                    'email' => $this->email,
                    'user_id' => $user->id,
                    'error' => $tokenError->getMessage()
                ]);
            }

            $this->dispatchFlashMessage('success', 'Zalogowano pomyślnie! Przekierowywanie...');
            $this->dispatch('user-logged-in', $user->toArray());

            return $this->redirectIntended('/', false);
        } catch (\App\Exceptions\AccountDeactivatedException $e) {
            \Log::warning('Login attempt with deactivated account', ['email' => $this->email]);
            $this->dispatchFlashMessage('error', 'Twoje konto zostało dezaktywowane.');
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $this->email,
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatchFlashMessage('error', 'Wystąpił błąd podczas logowania. Proszę spróbować ponownie.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
