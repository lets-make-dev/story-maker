<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use App\Getters\FormattedLocalTime;
use App\Tables\Columns\AudioPlayer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ChapterPartsRelationManager extends RelationManager
{
    protected static string $relationship = 'chapterParts';

    protected static ?string $recordTitleAttribute = 'text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('story'),
                Forms\Components\TextInput::make('image_description'),
                Forms\Components\TextInput::make('image_url'),
                Forms\Components\TextInput::make('order')
                    ->default(0)
                    ->numeric(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('story')->wrap(),
                Tables\Columns\TextColumn::make('image')
                    ->getStateUsing(fn($record) => $record['image_url'] ? "<a onclick='return false;' href='{$record['image_url']}' target=_blank><img src='{$record['image_url']}'/></a><em>{$record['image_description']}</em>" : "<em>{$record['image_description']}</em>"
                    )
                    ->html()
                    ->wrap(),
//                Tables\Columns\TextColumn::make('image_description')->wrap(),
//                Tables\Columns\ImageColumn::make('image_url')
//                    ->width(400)
//                    ->height('auto')
//                ,
                AudioPlayer::make('audio_file'),
                Tables\Columns\TextColumn::make('order'),

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
            ->defaultSort('order', 'asc');
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [100, 200, 500, 1000];
    }
}
