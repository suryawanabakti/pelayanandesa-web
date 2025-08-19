<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermohonanResource\Pages;
use App\Filament\Resources\PermohonanResource\RelationManagers;
use App\Models\Permohonan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermohonanResource extends Resource
{
    protected static ?string $model = Permohonan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    public static function getNavigationGroup(): string
    {
        return auth()->check() && auth()->user()->role === 'kepala'
            ? 'Data Permohonan'
            : 'Main Menu';
    }
    protected static ?string $navigationLabel = 'Data Permohonan';

    public static function getGloballySearchableAttributes(): array
    {
        return ['jenis_layanan', 'keterangan', 'nama', 'nik'];
    }

    public static function getPluralModelLabel(): string
    {
        return 'Permohonan'; // Customize the plural label
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Permohonan')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pemohon')
                            ->required()
                            ->relationship('user', 'name', function ($query) {
                                $query->where('role', 'masyarakat');
                            }),

                        Select::make('jenis_layanan')
                            ->label('Jenis Layanan')
                            ->required()
                            ->options([
                                'Surat keterangan tidak mampu' => 'Surat keterangan tidak mampu',
                                'Surat Izin Usaha' => 'Surat Izin Usaha',
                                'Surat Pindah Penduduk' => 'Surat Pindah Penduduk',
                                'Surat Penyaluran BLT' => 'Surat Penyaluran BLT',
                                'Surat Stanting' => 'Surat Stanting',
                            ]),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Permohonan')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'DIAJUKAN' => 'DIAJUKAN',
                                'DIPROSES' => 'DIPROSES',
                                'SELESAI' => 'SELESAI',
                                'DITOLAK' => 'DITOLAK'
                            ])
                            ->required(),

                        FileUpload::make('file')
                            ->label('File Pendukung')
                            ->directory('permohonan-files')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120) // 5MB
                    ]),

                Section::make('Data Pribadi')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required(),

                        TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->length(16)
                            ->numeric(),

                        Fieldset::make('Tempat & Tanggal Lahir')
                            ->schema([
                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->required(),

                                DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->required(),
                            ]),

                        TextInput::make('umur')
                            ->label('Umur')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(150),

                        TextInput::make('nama_orang_tua')
                            ->label('Nama Orang Tua')
                            ->required(),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->rows(3),

                        TextInput::make('pekerjaan')
                            ->label('Pekerjaan')
                            ->required(),
                    ]),

                Section::make('Keterangan')
                    ->schema([
                        Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->required()
                            ->rows(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jenis_layanan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nik')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Pemohon')
                    ->searchable(),

                BadgeColumn::make('status')
                    ->colors([
                        'primary',
                        'secondary' => 'DIAJUKAN',
                        'warning' => 'DIPROSES',
                        'success' => 'SELESAI',
                        'danger' => 'DITOLAK',
                    ])
                    ->sortable(),

                TextInputColumn::make('komentar')
                    ->label('Komentar'),

            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_layanan')
                    ->options([
                        'Surat keterangan tidak mampu' => 'Surat keterangan tidak mampu',
                        'Surat Izin Usaha' => 'Surat Izin Usaha',
                        'Surat Pindah Penduduk' => 'Surat Pindah Penduduk',
                        'Surat Penyaluran BLT' => 'Surat Penyaluran BLT',
                        'Surat Stanting' => 'Surat Stanting',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'DIAJUKAN' => 'DIAJUKAN',
                        'DIPROSES' => 'DIPROSES',
                        'SELESAI' => 'SELESAI',
                        'DITOLAK' => 'DITOLAK'
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->visible(auth()->user()->role === 'admin'),
                Tables\Actions\EditAction::make()->visible(auth()->user()->role === 'admin'),
                Tables\Actions\Action::make('cetak_surat')
                    ->label('Cetak Surat')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('permohonan.cetak', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('setuju')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-s-check')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'DIPROSES')
                    ->action(function ($record) {
                        $record->update(['status' => 'SELESAI']);

                        Notification::make()
                            ->title('Permohonan berhasil disetujui!')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-s-x-mark')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'DIPROSES')
                    ->action(function ($record) {
                        $record->update(['status' => 'DITOLAK']);

                        Notification::make()
                            ->title('Permohonan berhasil ditolak!')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('diproses')
                    ->label('Proses')
                    ->color('warning')
                    ->icon('heroicon-s-arrow-path')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'DIAJUKAN')
                    ->action(function ($record) {
                        $record->update(['status' => 'DIPROSES']);

                        Notification::make()
                            ->title('Permohonan sedang diproses')
                            ->success()
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
