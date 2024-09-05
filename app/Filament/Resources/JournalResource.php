<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Store;
use App\Models\Journal;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\JournalResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JournalResource\RelationManagers;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static array $storeNames;
    public static function form(Form $form): Form
    {
        if (!isset(self::$collectionNames)) {
            self::$storeNames = Store::pluck('number', 'id')->all();
        }
        return $form
            ->schema([
                Section::make('General Information')
                ->schema([
                    Select::make('store_id')
                    ->label("Store")
                    ->options(self::$storeNames)
                    ->required()
                    ,
                    DatePicker::make('date')
                    ->required(),
                    TextInput::make('revenue')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                            $profit = $state - $get('food_cost') - $get('labor_cost');
                            $set('profit', $profit);
                        }),
                    TextInput::make('food_cost')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                            $profit = $get('revenue') - $state - $get('labor_cost');
                            $set('profit', $profit);
                        }),
        
                    TextInput::make('labor_cost')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                            $profit = $get('revenue') - $get('food_cost') - $state;
                            $set('profit', $profit);
                        }),                    
        
                        TextInput::make('profit')
                        ->numeric()
                        ->required()
                        ->disabled()
        
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('store.number'),
                TextColumn::make('revenue'),
                TextColumn::make('food_cost'),
                TextColumn::make('labor_cost'),
                TextColumn::make('profit'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
        ];
    }    
}
