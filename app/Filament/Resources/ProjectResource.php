<?php

namespace App\Filament\Resources;

use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Showcase';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Project Overview')
                ->schema([
                    Forms\Components\TextInput::make('title')->required()->live(onBlur: true)->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('title_ar'),
                    Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('category')->default('Full Stack'),
                    Forms\Components\TextInput::make('technologies')->placeholder('Laravel, Flutter, MySQL'),
                    Forms\Components\Select::make('status')->options(['Completed' => 'Completed', 'In Progress' => 'In Progress', 'Archived' => 'Archived'])->default('Completed'),
                    Forms\Components\TextInput::make('project_date')->default('2026'),
                    Forms\Components\Toggle::make('is_featured')->default(true),
                    Forms\Components\TextInput::make('github_url')->url(),
                    Forms\Components\TextInput::make('live_url')->url(),
                    Forms\Components\TextInput::make('main_image'),
                    Forms\Components\Textarea::make('short_description')->rows(2)->columnSpanFull(),
                    Forms\Components\Textarea::make('description')->rows(5)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\BadgeColumn::make('status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
