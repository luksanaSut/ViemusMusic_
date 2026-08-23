import { execFileSync } from 'node:child_process';
import { mkdirSync } from 'node:fs';

export default function globalSetup(): void {
    mkdirSync('playwright/.auth', { recursive: true });

    execFileSync('php', ['artisan', 'config:clear', '--env=playwright'], {
        cwd: process.cwd(),
        stdio: 'inherit',
    });
    execFileSync('php', ['artisan', 'migrate:fresh', '--env=playwright', '--force'], {
        cwd: process.cwd(),
        stdio: 'inherit',
    });
    execFileSync('php', ['artisan', 'db:seed', '--env=playwright', '--class=PlaywrightSeeder', '--force'], {
        cwd: process.cwd(),
        stdio: 'inherit',
    });
}
