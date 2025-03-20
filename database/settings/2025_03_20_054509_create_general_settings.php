<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', config('app.name'));
        $this->migrator->add('general.site_logo');
        $this->migrator->add('general.phone', '+880 1234-567890');
        $this->migrator->add('general.address', 'Mirpur, Dhaka-1206, Bangladesh');
    }
};
