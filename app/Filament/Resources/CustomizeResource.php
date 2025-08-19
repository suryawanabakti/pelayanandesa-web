<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomizeResource\Pages;
use App\Filament\Resources\CustomizeResource\RelationManagers;
use App\Models\Customize;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomizeResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Main Menu';
    protected static ?string $navigationLabel = 'Masyarakat';
    protected static ?string $modelLabel = 'Masyarakat';
    protected static ?string $pluralModelLabel = 'Masyarakat';

    public static function canAccess(): bool
    {
        return auth()->user()->email === 'admin@gmail.com';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('username')->required(),
                TextInput::make('email')->required()->label('NIK'),
                TextInput::make('password')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::where('role', 'masyarakat'))
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('nik')->searchable(),
                IconColumn::make('has_login')
                    ->boolean()
                    ->label('Status')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomizes::route('/'),
            'create' => Pages\CreateCustomize::route('/create'),
            'view' => Pages\ViewCustomize::route('/{record}'),
            'edit' => Pages\EditCustomize::route('/{record}/edit'),
        ];
    }
}
