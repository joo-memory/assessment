<?php

namespace App\Filament\Resources\FranchiseOwnerResource\Pages;

use App\Filament\Resources\FranchiseOwnerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFranchiseOwners extends ListRecords
{
    protected static string $resource = FranchiseOwnerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
