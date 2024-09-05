<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Brand;
use App\Models\Store;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use App\Forms\Components\StoreBarChart;
use App\View\Components\ChartComponent;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Widgets\StoreProfitChart;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\StoreResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StoreResource\RelationManagers;
use App\Models\FranchiseOwner;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static array $brandNames;
    protected static array $franchiseOwners;

    public static function form(Form $form): Form
    {
        if (!isset(self::$brandNames)) {
                self::$brandNames = Brand::pluck('name', 'id')->all();
        }
        if (!isset(self::$franchiseOwners)) {
            self::$franchiseOwners = FranchiseOwner::pluck('name', 'id')->all();
    }
        return $form
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 1,
                    '2xl' => 2,
                ])
                ->schema([
                    Section::make('Store Information')
                    ->columnSpan([
                        '2xl' => 1
                    ])
                    ->schema([
                        Section::make('General')
                        ->schema([
                            TextInput::make('number'),
                            Select::make('franchise_owner_id')
                            ->label("Franchise Owner")
                            ->options(self::$franchiseOwners)
                            ->required(),
                            Select::make('brand_id')
                            ->label("Brand")
                            ->options(self::$brandNames)
                            ->required(),
                        ]),
                        Section::make('Address')
                        ->schema([
                            TextInput::make('address')->required(),
                            TextInput::make('city')->required(),
                            TextInput::make('state')->required(),
                            TextInput::make('zip_code')->required(),
                        ])
                    ]),

                    Section::make('Journal Information')
                    ->columnSpan([
                        '2xl' => 1
                    ])
                    ->schema([
                        Fieldset::make('Totals')
                            ->schema([
                                Placeholder::make('total_revenue')
                                ->label('Total Revenue')
                                ->content(fn ($record) => $record ? $record->totalRevenue : 'N/A'),
                                Placeholder::make('total_profit')
                                ->label('Total Profit')
                                ->content(fn ($record) => $record ? $record->totalProfit : 'N/A'),
                                Placeholder::make('total_food_cost')
                                ->label('Total Food Cost')
                                ->content(fn ($record) => $record ? $record->totalFoodCost : 'N/A'),
                                Placeholder::make('total_food_cost')
                                ->label('Total Labor Cost')
                                ->content(fn ($record) => $record ? $record->totalLaborCost : 'N/A'),
                                StoreBarChart::make('')
                            ]),
                    ]),
             
                ]),
                
             
         

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number'),
                TextColumn::make('address')
                ->formatStateUsing(function ($record) {
                    return $record->address." ".$record->city." ".$record->state." ".$record->zip_code;
                }),
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }    
}
