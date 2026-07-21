<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class BriefsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'مدیریت پرسشنامه‌ها';

    protected static ?string $clusterBreadcrumb = 'پرسشنامه‌ها';

    protected static ?int $navigationSort = 2;
}
