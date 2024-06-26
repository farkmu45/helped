<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\HistoryResource\Pages;
use App\Models\Calculation;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HistoryResource extends Resource
{
    protected static ?string $model = Calculation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'History';

    protected static ?string $pluralModelLabel = 'History';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make()
                    ->schema([
                        Tab::make('General')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created at')
                                    ->date('Y-m-d'),
                                TextEntry::make('disease.name')
                                    ->label('Diagnosis'),
                                TextEntry::make('value')
                                    ->label('Recomendation')
                                    ->formatStateUsing(function ($state) {
                                        if ($state <= 40) {
                                            return 'Do healthy routine and please proceed life as usual, may you be blessed';
                                        } elseif ($state > 40 && $state <= 70) {
                                            return 'Proceed life as usual, please contact the doctor if you feel more symptoms';
                                        } elseif ($state > 70 && $state <= 100) {
                                            return 'Please contact the doctor immediately';
                                        }
                                    }),
                            ])->columns(),
                        Tab::make('Analysis')
                            ->schema([
                                RepeatableEntry::make('details')
                                    ->label(null)
                                    ->schema([
                                        TextEntry::make('disease.name'),
                                        TextEntry::make('value')
                                            ->label('Score')
                                            ->formatStateUsing(fn ($state) => number_format($state, 2, '.', '')),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Questionaire')
                            ->schema([
                                RepeatableEntry::make('questionnaires')
                                    ->label(null)
                                    ->schema([
                                        TextEntry::make('symptom.name'),
                                        TextEntry::make('answer'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->date('Y-m-d'),
                TextColumn::make('disease.name')
                    ->label('Diagnosis'),
                TextColumn::make('value')
                    ->label('Recomendation')
                    ->formatStateUsing(function ($state) {
                        if ($state <= 40) {
                            return 'Do healthy routine and please proceed life as usual, may you be blessed';
                        } elseif ($state > 40 && $state <= 70) {
                            return 'Proceed life as usual, please contact the doctor if you feel more symptoms';
                        } elseif ($state > 70 && $state <= 100) {
                            return 'Please contact the doctor immediately';
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListHistories::route('/'),
            'view' => Pages\ViewHistory::route('/{record}'),
        ];
    }
}
