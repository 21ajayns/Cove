// Stages the Laravel app + a PHP runtime into build-stage/ for electron-builder.
const fs = require('fs');
const path = require('path');
const { execFileSync, execSync } = require('child_process');

const root = path.resolve(__dirname, '..');
const stage = path.join(root, 'build-stage');
const appDir = path.join(stage, 'app');
const phpDir = path.join(stage, 'php');

const APP_ITEMS = ['app', 'bootstrap', 'config', 'public', 'routes', 'artisan', 'composer.json', 'composer.lock'];
const PHP_EXTS = ['mbstring', 'openssl', 'pdo_sqlite', 'sqlite3', 'fileinfo', 'curl'];
const SKIP_PHP_DLLS = /^(php8apache|php8phpdbg|php8embed)/i;

function copy(src, dest, filter) {
    fs.cpSync(src, dest, { recursive: true, filter });
}

if (!fs.existsSync(path.join(root, 'public', 'build', 'manifest.json')) &&
    !fs.existsSync(path.join(root, 'public', 'build', '.vite', 'manifest.json'))) {
    throw new Error('public/build is missing - run "npm run build" first.');
}

fs.rmSync(stage, { recursive: true, force: true });
fs.mkdirSync(appDir, { recursive: true });

for (const item of APP_ITEMS) {
    copy(path.join(root, item), path.join(appDir, item), (src) => {
        const rel = path.relative(root, src).replace(/\\/g, '/');
        return !rel.startsWith('bootstrap/cache/') && rel !== 'public/hot';
    });
}
fs.mkdirSync(path.join(appDir, 'bootstrap', 'cache'), { recursive: true });

for (const dir of ['database/migrations', 'database/seeders', 'database/factories', 'resources/views', 'resources/lang']) {
    if (fs.existsSync(path.join(root, dir))) copy(path.join(root, dir), path.join(appDir, dir));
}
copy(
    path.join(root, 'vendor', 'laravel', 'framework', 'src', 'Illuminate', 'Foundation', 'resources', 'server.php'),
    path.join(appDir, 'server.php')
);

// Laravel treats cache-path env vars as relative unless they start with / or \,
// so teach the staged copy (only) that Windows drive letters are absolute.
const bootstrapFile = path.join(appDir, 'bootstrap', 'app.php');
const bootstrapSrc = fs.readFileSync(bootstrapFile, 'utf8');
if (!/\nreturn \$app;/.test(bootstrapSrc)) throw new Error('bootstrap/app.php: could not find "return $app;"');
fs.writeFileSync(bootstrapFile, bootstrapSrc.replace(
    /\nreturn \$app;/,
    "\nforeach (range('A', 'Z') as $drive) {\n    $app->addAbsoluteCachePathPrefix($drive.':');\n}\n\nreturn $app;"
));

// Production-only vendor directory.
try {
    execFileSync('composer', ['install', '--no-dev', '--no-scripts', '--optimize-autoloader', '--no-interaction'], {
        cwd: appDir, stdio: 'inherit', shell: true,
    });
} catch (e) {
    console.warn('composer install --no-dev failed, copying full vendor/ instead');
    copy(path.join(root, 'vendor'), path.join(appDir, 'vendor'));
}

// PHP runtime, taken from whichever php is on PATH.
const phpExe = execSync('where php', { encoding: 'utf8' }).split(/\r?\n/)[0].trim();
const phpSrc = path.dirname(phpExe);
fs.mkdirSync(path.join(phpDir, 'ext'), { recursive: true });
for (const f of fs.readdirSync(phpSrc)) {
    if (/\.dll$/i.test(f) && !SKIP_PHP_DLLS.test(f)) fs.copyFileSync(path.join(phpSrc, f), path.join(phpDir, f));
}
fs.copyFileSync(phpExe, path.join(phpDir, 'php.exe'));
for (const ext of PHP_EXTS) {
    fs.copyFileSync(path.join(phpSrc, 'ext', `php_${ext}.dll`), path.join(phpDir, 'ext', `php_${ext}.dll`));
}
console.log('Staged app + PHP in', stage);
