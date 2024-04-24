<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Validation\ValidationException;

class Login extends BaseAuth
{
    public ?string $username;
    public ?string $password;

    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getLoginFormComponent(),
            $this->getPasswordFormComponent(),
        ]);
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('mobile')
            ->label('Mobile')
            ->required()
            ->autofocus()
            ->mutateStateForValidationUsing(function ($state) {
                return $this->formatMobile($state);
            });
    }

    function formatMobile($mobile): string
    {
        return substr(preg_replace('#[^0-9]#', '', $mobile), -9);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->required();
    }

    public function getRules(): array
    {
        return [
            'mobile'   => 'required|string|regex:#^[0-39][0-9]{8}$|exists:users,mobile#',
            'password' => 'required|string',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'data.mobile'   => $e->getMessage(),
                'data.password' => $e->getMessage(),
            ]);
        }
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'mobile'   => $this->formatMobile($data['mobile']),
            'password' => $data['password'],
        ];
    }
}
