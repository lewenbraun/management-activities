<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityTypeResource\Pages\CreateActivityType;
use App\Filament\Resources\ActivityTypeResource\Pages\EditActivityType;
use App\Filament\Resources\ActivityTypeResource\Pages\ListActivityTypes;
use App\Filament\Resources\ActivityTypeResource\Pages\ViewActivityType;
use App\Models\ActivityType;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use TomatoPHP\FilamentIcons\Components\IconColumn;
use TomatoPHP\FilamentIcons\Components\IconPicker;

class ActivityTypeResource extends Resource
{
    protected static ?string $model = ActivityType::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-center-left';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                IconPicker::make('icon_name')
                    ->default('heroicon-o-academic-cap')
                    ->label('Icon'),
                TextInput::make('display_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('icon_name')
                    ->label('Icon'),
                TextColumn::make('display_order')
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
            'index' => ListActivityTypes::route('/'),
            'view' => ViewActivityType::route('/{record}'),
            'create' => CreateActivityType::route('/create'),
            'edit' => EditActivityType::route('/{record}/edit'),
        ];
    }
}
