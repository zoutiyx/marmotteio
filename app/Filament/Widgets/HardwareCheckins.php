<?php

namespace App\Filament\Widgets;

use App\Models\HardwarePerson;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class HardwareCheckins extends BaseWidget
{
    protected static ?int $sort = 2;

    public function getTableRecordsPerPage(): int|string|null
    {
        return 5;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                HardwarePerson::query()->latest('checked_in_at')->whereNotNull('checked_in_at')
            )
            ->columns([
                TextColumn::make('hardware')
                    ->badge()
                    ->url(fn (HardwarePerson $record) => '/admin/'.Filament::getTenant()->id.'/hardware/'.$record->hardware->id.'/edit')
                    ->getStateUsing(fn (HardwarePerson $record): string => $record->hardware->hardware_model->name)
                    ->iconPosition('after')
                    ->icon('heroicon-o-arrow-right'),
                TextColumn::make('person.name'),
                TextColumn::make('checked_in_at')->dateTime()->alignRight(),
            ]);
    }
}
