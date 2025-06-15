<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages\CreateActivity;
use App\Filament\Resources\ActivityResource\Pages\EditActivity;
use App\Filament\Resources\ActivityResource\Pages\ListActivities;
use App\Filament\Resources\ActivityResource\Pages\ViewActivity;
use App\Models\Activity;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('source')
                    ->maxLength(255),
                FileUpload::make('main_image_path')
                    ->directory('activities')
                    ->image(),
                TextInput::make('short_description')
                    ->maxLength(200),
                TextInput::make('registration_link')
                    ->maxLength(255),
                Textarea::make('location_description')
                    ->required()
                    ->columnSpanFull(),
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
                Repeater::make('schedule')
                    ->schema([
                        DatePicker::make('date')
                            ->label('Date')
                            ->required(),
                        TimePicker::make('time')
                            ->label('Time')
                            ->required(),
                    ]),
                Select::make('activity_type_id')
                    ->relationship('activityType', 'name')
                    ->required(),
                Select::make('creator_id')
                    ->relationship('creator', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('source')
                    ->url(fn (?string $state): ?string => $state)
                    ->tooltip(fn (?string $state): ?string => $state)
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->searchable(),
                ImageColumn::make('main_image_path')
                    ->label('Image'),
                TextColumn::make('activityType.name')
                    ->label('Type')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable(),
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
            'index' => ListActivities::route('/'),
            'view' => ViewActivity::route('/{record}'),
            'create' => CreateActivity::route('/create'),
            'edit' => EditActivity::route('/{record}/edit'),
        ];
    }
}
