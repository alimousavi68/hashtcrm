<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationLabel = 'پروژه‌ها';
    protected static ?string $pluralModelLabel = 'پروژه‌ها';
    protected static ?string $modelLabel = 'پروژه';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات اصلی پروژه')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('عنوان پروژه')
                                ->required(),
                            Forms\Components\Select::make('client_id')
                                ->label('مشتری')
                                ->relationship(
                                    name: 'client',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $query) => $query->where('role', 'client')
                                )
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('status')
                                ->label('وضعیت پروژه')
                                ->options([
                                    'draft' => 'پیش‌نویس اولیه',
                                    'brief' => 'تکمیل بریف نیازمندی‌ها',
                                    'contract' => 'امضای قرارداد و امور مالی',
                                    'in_progress' => 'در حال طراحی و توسعه',
                                    'review' => 'بازنگری و ثبت نظرات (دمو)',
                                    'ready_handover' => 'آماده‌سازی بسته تحویل',
                                    'completed' => 'تحویل نهایی و خاتمه',
                                ])
                                ->default('draft')
                                ->required(),
                            Forms\Components\DateTimePicker::make('feedback_deadline')
                                ->label('مهلت ارسال بازنگری و فیدبک')
                                ->nullable(),
                        ])
                    ]),

                Forms\Components\Section::make('بریف مشتری')
                    ->description('اطلاعات و بریف ثبت‌شده توسط مشتری برای این پروژه')
                    ->collapsible()
                    ->collapsed()
                    ->relationship('briefAnswer')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('business_name')
                                ->label('نام برند / کسب‌وکار')
                                ->disabled(),
                            Forms\Components\TextInput::make('design_style')
                                ->label('سبک طراحی')
                                ->disabled(),
                            Forms\Components\Textarea::make('business_description')
                                ->label('توصیف کسب‌وکار')
                                ->columnSpanFull()
                                ->disabled(),
                            Forms\Components\Textarea::make('target_audience')
                                ->label('مخاطبان هدف')
                                ->columnSpanFull()
                                ->disabled(),
                            Forms\Components\Textarea::make('competitors')
                                ->label('رقبای اصلی')
                                ->columnSpanFull()
                                ->disabled(),
                            Forms\Components\TextInput::make('color_preferences')
                                ->label('رنگ‌های ترجیحی')
                                ->disabled(),
                            Forms\Components\CheckboxList::make('features_required')
                                ->label('امکانات مورد نیاز وب‌سایت')
                                ->options([
                                    'e_commerce' => 'فروشگاه آنلاین و درگاه پرداخت',
                                    'blog' => 'وبلاگ و بخش اخبار/مقالات',
                                    'portfolio' => 'گالری تصاویر / نمونه کارها',
                                    'user_panel' => 'ثبت‌نام و پنل اختصاصی کاربران',
                                    'support_ticket' => 'سیستم تیکت پشتیبانی و چت آنلاین',
                                    'multi_language' => 'پشتیبانی از چند زبان',
                                    'custom' => 'سایر امکانات اختصاصی',
                                ])
                                ->columns(2)
                                ->disabled(),
                            Forms\Components\Textarea::make('extra_notes')
                                ->label('توضیحات تکمیلی')
                                ->columnSpanFull()
                                ->disabled(),
                        ])
                    ]),

                Forms\Components\Section::make('اطلاعات دسترسی و دارایی‌ها')
                    ->description('اطلاعات محرمانه و دسترسی‌های ثبت‌شده توسط مشتری (رمزنگاری شده در دیتابیس)')
                    ->collapsible()
                    ->collapsed()
                    ->relationship('credential')
                    ->schema([
                        Forms\Components\Tabs::make('CredentialsTabs')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('host_tab')
                                    ->label('دسترسی هاست')
                                    ->schema([
                                        Forms\Components\Grid::make(2)->schema([
                                            Forms\Components\TextInput::make('host_provider')
                                                ->label('ارائه‌دهنده هاست')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('host_panel_url')
                                                ->label('آدرس پنل هاست')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('host_username')
                                                ->label('نام کاربری هاست')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('host_password')
                                                ->label('کلمه عبور هاست')
                                                ->password()
                                                ->revealable()
                                                ->disabled(),
                                        ])
                                    ]),
                                Forms\Components\Tabs\Tab::make('domain_tab')
                                    ->label('دسترسی دامنه')
                                    ->schema([
                                        Forms\Components\Grid::make(2)->schema([
                                            Forms\Components\TextInput::make('domain_provider')
                                                ->label('ثبت‌کننده دامنه')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('domain_panel_url')
                                                ->label('آدرس پنل دامنه')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('domain_username')
                                                ->label('نام کاربری دامنه')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('domain_password')
                                                ->label('کلمه عبور دامنه')
                                                ->password()
                                                ->revealable()
                                                ->disabled(),
                                        ])
                                    ]),
                                Forms\Components\Tabs\Tab::make('admin_tab')
                                    ->label('دسترسی مدیریت سایت')
                                    ->schema([
                                        Forms\Components\Grid::make(3)->schema([
                                            Forms\Components\TextInput::make('admin_panel_url')
                                                ->label('آدرس پنل مدیریت')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('admin_username')
                                                ->label('نام کاربری مدیریت')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('admin_password')
                                                ->label('کلمه عبور مدیریت')
                                                ->password()
                                                ->revealable()
                                                ->disabled(),
                                        ])
                                    ]),
                                Forms\Components\Tabs\Tab::make('other_tab')
                                    ->label('سایر اطلاعات دسترسی')
                                    ->schema([
                                        Forms\Components\Textarea::make('other_credentials')
                                            ->label('جزئیات سایر دسترسی‌ها')
                                            ->disabled(),
                                    ]),
                            ])
                            ->columnSpanFull()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان پروژه')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('مشتری')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت فاز فعلی')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'brief' => 'warning',
                        'contract' => 'info',
                        'in_progress' => 'primary',
                        'review' => 'danger',
                        'ready_handover' => 'success',
                        'completed' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'پیش‌نویس اولیه',
                        'brief' => 'تکمیل بریف نیازمندی‌ها',
                        'contract' => 'امضای قرارداد و امور مالی',
                        'in_progress' => 'در حال طراحی و توسعه',
                        'review' => 'بازنگری و ثبت نظرات (دمو)',
                        'ready_handover' => 'آماده‌سازی بسته تحویل',
                        'completed' => 'تحویل نهایی و خاتمه',
                    }),
                Tables\Columns\TextColumn::make('feedback_deadline')
                    ->label('مهلت فیدبک')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ تعریف پروژه')
                    ->dateTime('Y/m/d')
                    ->sortable(),
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
            RelationManagers\ContractRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
