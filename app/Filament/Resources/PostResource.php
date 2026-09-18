<?php

namespace App\Filament\Resources;

use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Article Content')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('title_ar'),
                    Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required(),
                    Forms\Components\Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->preload(),
                    Forms\Components\Textarea::make('excerpt')->rows(2),
                    Forms\Components\MarkdownEditor::make('content')->required()->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Publishing & Media')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'scheduled' => 'Scheduled',
                            'archived' => 'Archived',
                        ])->default('published')->required(),
                    Forms\Components\DateTimePicker::make('published_at')->default(now()),
                    Forms\Components\TextInput::make('featured_image'),
                    Forms\Components\TextInput::make('alt_text'),
                ])->columns(2),

            Forms\Components\Section::make('SEO Settings')
                ->schema([
                    Forms\Components\TextInput::make('seo_title'),
                    Forms\Components\TextInput::make('focus_keyword'),
                    Forms\Components\Textarea::make('meta_description')->rows(2)->columnSpanFull(),
                    Forms\Components\TextInput::make('canonical_url'),
                ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'published',
                        'warning' => 'draft',
                        'danger' => 'archived',
                    ]),
                Tables\Columns\TextColumn::make('published_at')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['published' => 'Published', 'draft' => 'Draft']),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\PostResource\Pages\ListPosts::route('/'),
            'create' => \App\Filament\Resources\PostResource\Pages\CreatePost::route('/create'),
            'edit' => \App\Filament\Resources\PostResource\Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
