<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlaceResource\Pages;
use App\Filament\Resources\PlaceResource\RelationManagers;
use App\Models\Place;
use App\Models\Keyword;
use Filament\Forms;
use Filament\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\TextInput::make('title_it')->label('Title (IT)'),
                    Forms\Components\TextInput::make('title_de')->label('Title (DE)'),
                    Forms\Components\TextInput::make('title_en')->label('Title (EN)'),

                    Forms\Components\Select::make('keyword_id')
                    ->label('Keyword')
                    ->options(
                        Keyword::all()->pluck('title_de', 'id')
                    )
                    ->required(),

                    Forms\Components\Select::make('keyword_selector')
                        ->label('Selettore keyword per collegamenti')
                        ->options(
                            Keyword::all()->pluck('title_de', 'id')
                        )
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('keyword_text', 
                        '<span class="nested keywordItem" onclick="openBoxFromKeyword(this)" data-id="'.Keyword::find($state)?->id.'">'.Keyword::find($state)?->title_de.'</span>')
                    ),

                    Forms\Components\TextInput::make('keyword_text')
                            ->label('Copia ed Incolla')
                    ->readOnly(),

                    Forms\Components\MarkdownEditor::make('content_it')->label('Content (IT)')->required(),
                    Forms\Components\MarkdownEditor::make('content_de')->label('Content (DE)')->required(),
                    Forms\Components\MarkdownEditor::make('content_en')->label('Content (EN)')->required(),
                    Forms\Components\FileUpload::make('file')->label('Upload PDF')->multiple()->preserveFilenames()->downloadable()->reorderable()->directory('files'),
                    Forms\Components\TextInput::make('latitude')->label('Latitude')->numeric()->required(),
                    Forms\Components\TextInput::make('longitude')->label('Longitude')->numeric()->required(),
                   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title_it')->label('Title (IT)'),
                TextColumn::make('title_de')->label('Title (DE)'),
                TextColumn::make('title_en')->label('Title (EN)'),
                TextColumn::make('latitude'),
                TextColumn::make('longitude'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPlaces::route('/'),
            'create' => Pages\CreatePlace::route('/create'),
            'edit' => Pages\EditPlace::route('/{record}/edit'),
        ];
    }
}
