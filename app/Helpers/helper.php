<?php

use Carbon\Carbon;
use Illuminate\Http\Response;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

function formatDate($data): string
{
    return Carbon::parse($data)->format('Y-m-d');
}

function getSettings(string $key, string $type = 'text', string $model = 'admin'): string
{
    $key = strtoupper(str_replace(' ', '_', $key));
    $setting = Setting::where('model', $model)->where('key', $key)->first();

    if ($type === 'image') {
        return ($setting && $setting->type === 'image') ? $setting->getImageUrl($key) : '';
    }

    return $setting?->value ?? '';
}

function getLogo(): string
{
    $key = strtoupper(str_replace(' ', '_', 'logo'));
    $setting = Setting::where('model','admin')->where('key',$key)->first();
    return ($setting && $setting->type === 'image') ? $setting->getImageUrl($key) : '';
}

function getLogoLogin(): string
{
    $key = strtoupper(str_replace(' ', '_', 'login_logo'));
    $setting = Setting::where('model', 'admin')->where('key', $key)->first();
    return ($setting && $setting->type === 'image') ? $setting->getImageUrl($key) : '';
}

function clearString($key): array|string
{
    $key = str_replace(' ','_',$key);
    $key = 'dashboard.'.strtolower($key);
    $translated = __($key);

    return ucfirst($translated);
}
