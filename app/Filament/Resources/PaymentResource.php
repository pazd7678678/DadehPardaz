<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Pzamani\Payment\app\Models\Payment;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $slug = 'payments';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sender_id')
                    ->relationship('sender', 'name')
                    ->searchable()
                    ->required(),

                Select::make('receiver_id')
                    ->relationship('receiver', 'name')
                    ->searchable()
                    ->required(),

                Select::make('paytype_id')
                    ->relationship('paytype', 'name')
                    ->searchable()
                    ->required(),

                Select::make('gateway_id')
                    ->relationship('gateway', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('description')
                    ->required(),

                TextInput::make('amount')
                    ->required()
                    ->integer(),

                TextInput::make('iban')
                    ->required(),

                TextInput::make('attachment')
                    ->required(),

                TextInput::make('authority')
                    ->required(),

                TextInput::make('reference')
                    ->required(),

                Checkbox::make('is_confirmed'),

                Checkbox::make('is_paid'),

                DatePicker::make('paid_at')
                    ->label('Paid Date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->rowIndex(),
                TextColumn::make('sender.name')->searchable()->sortable(),
                TextColumn::make('receiver.name')->searchable()->sortable(),
                TextColumn::make('paytype.name')->searchable()->sortable(),
                TextColumn::make('amount'),
                IconColumn::make('is_confirmed')
                    ->label('Is Confirmed')
                    ->icons([
                        'heroicon-o-check'  => true,
                        'heroicon-o-x-mark' => false,
                    ])
                    ->colors([
                        'success' => true,
                        'danger'  => false,
                    ])
                    ->sortable(),
                IconColumn::make('is_paid')
                    ->label('Is Paid')
                    ->icons([
                        'heroicon-o-check'  => true,
                        'heroicon-o-x-mark' => false,
                    ])
                    ->colors([
                        'success' => true,
                        'danger'  => false,
                    ])
                    ->sortable(),
                TextColumn::make('paid_at')->label('Payment Date')->date('Y/m/d H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index'  => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit'   => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['gateway', 'paytype', 'receiver', 'sender']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['gateway.name', 'paytype.name', 'receiver.name', 'sender.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->gateway) {
            $details['Gateway'] = $record->gateway->name;
        }

        if ($record->paytype) {
            $details['Paytype'] = $record->paytype->name;
        }

        if ($record->receiver) {
            $details['Receiver'] = $record->receiver->name;
        }

        if ($record->sender) {
            $details['Sender'] = $record->sender->name;
        }

        return $details;
    }
}
