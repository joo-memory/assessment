<?php

namespace App\Filament\Resources\StoreResource\Pages;

use Filament\Pages\Actions;
use Filament\Forms\Components\Card;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\StoreResource;
use App\Filament\Widgets\StoreProfitChart;

class EditStore extends EditRecord
{
    protected static string $resource = StoreResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
