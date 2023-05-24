<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use App\Getters\FormattedLocalTime;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';

    protected static ?string $recordTitleAttribute = 'title';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                Grid::make(12)->schema([
                    Forms\Components\TextInput::make('id')
                        ->disabled()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(5),
                    Forms\Components\Textarea::make('summary')
                        ->columnSpan(6),
                    Forms\Components\MarkdownEditor::make('story')
                        ->columnSpan(12),

                ]));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('summary')
                    ->getStateUsing(fn($record) => Str::markdown($record->summary ?? ""))
                    ->wrap(true)
                    ->html(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'asc')
            ;
    }


}
