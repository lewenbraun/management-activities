<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ParticipantResource\Pages\CreateParticipant;
use App\Filament\Resources\ParticipantResource\Pages\EditParticipant;
use App\Filament\Resources\ParticipantResource\Pages\ListParticipants;
use App\Filament\Resources\ParticipantResource\Pages\ViewParticipant;
use App\Models\Participant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('website_link')
                    ->maxLength(255),
                FileUpload::make('logo_path')
                    ->directory('participant')
                    ->image(),
                Repeater::make('coordinates')
                    ->schema([
                        TextInput::make('lat')
                            ->label('Latitude')
                            ->numeric()
                            ->required(),
                        TextInput::make('lng')
                            ->label('Longitude')
                            ->numeric()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('website_link')
                    ->url(fn (?string $state): ?string => $state)
                    ->tooltip(fn (?string $state): ?string => $state)
                    ->openUrlInNewTab()
                    ->limit(50)
                    ->searchable(),
                ImageColumn::make('logo_path')
                    ->label('Logo'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListParticipants::route('/'),
            'view' => ViewParticipant::route('/{record}'),
            'create' => CreateParticipant::route('/create'),
            'edit' => EditParticipant::route('/{record}/edit'),
        ];
    }
}
