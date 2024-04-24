<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Pzamani\Auth\app\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $slug = 'users';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Credentials')
                    ->columns(3)
                    ->schema([
                        TextInput::make('mobile')
                            ->prefix('09')
                            ->length(9)
                            ->regex('#^[0-39][0-9]{8}$#')
                            ->required(),
                        TextInput::make('email')
                            ->required(),
                        TextInput::make('password')
                            ->afterStateHydrated(function (TextInput $component, $state) {
                                $component->state('');
                            })
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create'),
                    ]),
                TextInput::make('name')->required(),
                TextInput::make('family')->required(),
                TextInput::make('nationalcode')->length(10)->required(),
                DatePicker::make('registered_at')->label('Registered At'),
                Checkbox::make('is_active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->rowIndex(),
                TextColumn::make('mobile')->prefix('09')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('family')->searchable()->sortable(),
                IconColumn::make('is_active')
                    ->label('Is Active')
                    ->icons([
                        'heroicon-o-check'  => true,
                        'heroicon-o-x-mark' => false,
                    ])
                    ->colors([
                        'success' => true,
                        'danger'  => false,
                    ])
                    ->sortable(),
                TextColumn::make('registered_at')
                    ->label('Registered Date')
                    ->date('Y/m/d H:i:s')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()->label('Details')->icon('heroicon-o-eye')->color('info'),
                EditAction::make(),
                DeleteAction::make()->visible(fn(User $user) => $user->id != '00000000-0000-0000-0000-000000000000'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email', 'name'];
    }
}
