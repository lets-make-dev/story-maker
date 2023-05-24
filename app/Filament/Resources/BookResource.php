<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Forms\Components\AudioPlayer;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(12)->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(12)
                    ,
                    Forms\Components\Textarea::make('summary')
                        ->columnSpan(6),
                    Forms\Components\FileUpload::make('cover_image')
                        ->disk('s3')
//                        ->directory('uploads')
                        ->visibility('public')
                        ->image()
                        ->columnSpan(6),
                    Forms\Components\TextInput::make('image_style')
                        ->columnSpan(12),
                    Forms\Components\Textarea::make('spoken_audio_intro')
                        ->columnSpan(12),
                    AudioPlayer::make('audio_intro_file'),
                    Forms\Components\Textarea::make('spoken_audio_outro')
                        ->columnSpan(12),
                    AudioPlayer::make('audio_outro_file'),
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->width(200)
                    ->height('auto'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('summary')->wrap(true)->html(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChaptersRelationManager::class,
            RelationManagers\ChapterPartsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'view' => Pages\ViewBook::route('/{record}'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
