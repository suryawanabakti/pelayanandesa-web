<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermohonanResource\Pages;
use App\Filament\Resources\PermohonanResource\RelationManagers;
use App\Models\Permohonan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermohonanResource extends Resource
{
    protected static ?string $model = Permohonan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Main Menu';
    protected static ?string $navigationLabel = 'Permohonan';
    public static function getGloballySearchableAttributes(): array
    {
        return ['jenis_layanan', 'keterangan'];
    }

    public static function getPluralModelLabel(): string
    {
        return 'Permohonan'; // Customize the plural label
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')->required()
                    ->relationship('user', 'name', function ($query) {
                        $query->where('role', 'masyarakat');
                    }),
                DatePicker::make('tanggal')->required(),
                TextInput::make('jenis_layanan')->required(),
                Textarea::make('keterangan')->required(),
                Select::make('status')->options(['DIAJUKAN' => 'DIAJUKAN', 'DIPROSES' => 'DIPROSES', 'SELESAI' => 'SELESAI', 'DITOLAK' => 'DITOLAK'])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal'),
                TextColumn::make('jenis_layanan')->searchable(),
                TextColumn::make('user.name')->searchable(),
                BadgeColumn::make('status')
                    ->colors([
                        'primary',
                        'secondary' => 'DIAJUKAN',
                        'warning' => 'DIPROSES',

                        'success' => 'SELESAI',
                        'danger' => 'DITOLAK',
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('setuju')
                    ->label('Setujui')
                    ->color('success') // Warna hijau untuk menunjukkan keberhasilan
                    ->icon('heroicon-s-check') // Ikon check
                    ->requiresConfirmation() // Menambahkan konfirmasi sebelum aksi dilakukan
                    ->visible(fn($record) => $record->status === 'DIPROSES')
                    ->action(function ($record) {
                        // Logika untuk menyetujui aduan
                        $record->update(['status' => 'SELESAI']);

                        // Anda juga bisa menambahkan notifikasi
                        Notification::make()
                            ->title('Aduan berhasil disetujui!')
                            ->success() // Tipe notifikasi
                            ->send();
                    }),
                Tables\Actions\Action::make('tolak')
                    ->label('Ditolak')
                    ->color('danger') // Warna hijau untuk menunjukkan keberhasilan
                    ->icon('heroicon-s-x-mark') // Ikon check
                    ->requiresConfirmation() // Menambahkan konfirmasi sebelum aksi dilakukan
                    ->visible(fn($record) => $record->status === 'DIPROSES')
                    ->action(function ($record) {
                        // Logika untuk menyetujui aduan
                        $record->update(['status' => 'DITOLAK']);

                        // Anda juga bisa menambahkan notifikasi
                        Notification::make()
                            ->title('Aduan berhasil ditolak!')
                            ->success() // Tipe notifikasi
                            ->send();
                    }),
                Tables\Actions\Action::make('diproses')
                    ->label('Proses')
                    ->color('success') // Warna hijau untuk menunjukkan keberhasilan
                    ->icon('heroicon-s-arrow-path') // Ikon check
                    ->requiresConfirmation() // Menambahkan konfirmasi sebelum aksi dilakukan
                    ->visible(fn($record) => $record->status === 'DIAJUKAN')
                    ->action(function ($record) {
                        // Logika untuk menyetujui aduan
                        $record->update(['status' => 'DIPROSES']);

                        // Anda juga bisa menambahkan notifikasi
                        Notification::make()
                            ->title('Aduan berhasil di proses')
                            ->success() // Tipe notifikasi
                            ->send();
                    }),
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
            'index' => Pages\ListPermohonans::route('/'),
            'create' => Pages\CreatePermohonan::route('/create'),
            'edit' => Pages\EditPermohonan::route('/{record}/edit'),
        ];
    }
}
