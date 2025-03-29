<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InformasiResource\Pages;
use App\Filament\Resources\InformasiResource\RelationManagers;
use App\Models\Informasi;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InformasiResource extends Resource
{
    protected static ?string $model = Informasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Main Menu';
    protected static ?string $navigationLabel = 'Informasi';
    public static function getGloballySearchableAttributes(): array
    {
        return ['judul', 'isi'];
    }

    public static function getPluralModelLabel(): string
    {
        return 'Informasi'; // Customize the plural label
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('gambar')->image()->columnSpan(2)->directory('informasi')->required(),
                TextInput::make('judul')->required()->columnSpan(2),
                Select::make('type')->options([
                    'artikel' => 'Artikel',
                    'pengumuman' => 'Pengumuman',
                ])->required()->columnSpan(2),
                RichEditor::make('isi')->required()->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar'),
                TextColumn::make('judul'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListInformasis::route('/'),
            'create' => Pages\CreateInformasi::route('/create'),
            'edit' => Pages\EditInformasi::route('/{record}/edit'),
        ];
    }
}
