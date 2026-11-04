// Cove desktop app: runs the Laravel app on a private local PHP server and shows it in a window.
// Everything stays on this computer: the database is a SQLite file in the user's app-data folder.
const { app, BrowserWindow, Menu, dialog, screen, session, shell } = require('electron');
const { spawn } = require('child_process');
const crypto = require('crypto');
const fs = require('fs');
const net = require('net');
const os = require('os');
const path = require('path');

app.setName('Cove');
// Lets tests (or a portable setup) keep the data somewhere else.
if (process.env.COVE_DATA_DIR) app.setPath('userData', process.env.COVE_DATA_DIR);

const PHP_EXTS = ['mbstring', 'openssl', 'pdo_sqlite', 'sqlite3', 'fileinfo', 'curl'];
// A fixed port keeps the page's origin stable, so settings kept in the browser (theme, Work/Personal) survive restarts.
const PREFERRED_PORT = 48743;
const BACKUPS_KEPT = 14;
const SQLITE_HEADER = 'SQLite format 3\0';

const resourcesRoot = app.isPackaged ? process.resourcesPath : path.join(__dirname, '..', 'build-stage');
const phpDir = path.join(resourcesRoot, 'php');
const appDir = path.join(resourcesRoot, 'app');
const iconPath = app.isPackaged ? path.join(process.resourcesPath, 'icon.ico') : path.join(__dirname, '..', 'build', 'icon.ico');
const dataDir = app.getPath('userData');
const dbFile = path.join(dataDir, 'cove.sqlite');
const backupsDir = path.join(dataDir, 'backups');
const stateFile = path.join(dataDir, 'window-state.json');

let phpProcess = null;
let mainWindow = null;
let baseUrl = null;
let launchEnv = null;

// ---------------------------------------------------------------- PHP

function phpArgs(...rest) {
    return [
        '-n',
        '-d', `extension_dir=${path.join(phpDir, 'ext')}`,
        ...PHP_EXTS.flatMap((e) => ['-d', `extension=${e}`]),
        '-d', 'memory_limit=512M',
        '-d', 'date.timezone=UTC',
        ...rest,
    ];
}

function runPhp(args, env) {
    return new Promise((resolve) => {
        const child = spawn(path.join(phpDir, 'php.exe'), phpArgs(...args), { cwd: appDir, env, windowsHide: true });
        let output = '';
        child.stdout.on('data', (d) => { output += d; });
        child.stderr.on('data', (d) => { output += d; });
        child.on('error', (e) => resolve({ code: -1, output: String(e) }));
        child.on('close', (code) => resolve({ code, output }));
    });
}

function loadAppKey() {
    const file = path.join(dataDir, 'app-key.txt');
    if (!fs.existsSync(file)) {
        fs.writeFileSync(file, 'base64:' + crypto.randomBytes(32).toString('base64'));
    }
    return fs.readFileSync(file, 'utf8').trim();
}

function buildEnv(port, token) {
    const storage = path.join(dataDir, 'storage');
    const cache = path.join(dataDir, 'cache');
    for (const d of ['framework/cache/data', 'framework/sessions', 'framework/views', 'logs']) {
        fs.mkdirSync(path.join(storage, d), { recursive: true });
    }
    fs.mkdirSync(cache, { recursive: true });
    if (!fs.existsSync(dbFile)) fs.writeFileSync(dbFile, '');

    return {
        ...process.env,
        APP_NAME: 'Cove',
        APP_ENV: 'production',
        APP_DEBUG: 'false',
        APP_KEY: loadAppKey(),
        APP_URL: `http://127.0.0.1:${port}`,
        DB_CONNECTION: 'sqlite',
        DB_DATABASE: dbFile,
        SESSION_DRIVER: 'file',
        CACHE_DRIVER: 'file',
        LOG_CHANNEL: 'single',
        COVE_LOCAL: 'true',
        COVE_LOCAL_TOKEN: token,
        COVE_LOCAL_NAME: os.userInfo().username || 'Me',
        LARAVEL_STORAGE_PATH: storage,
        APP_SERVICES_CACHE: path.join(cache, 'services.php'),
        APP_PACKAGES_CACHE: path.join(cache, 'packages.php'),
        APP_CONFIG_CACHE: path.join(cache, 'config.php'),
        APP_ROUTES_CACHE: path.join(cache, 'routes.php'),
        APP_EVENTS_CACHE: path.join(cache, 'events.php'),
    };
}

function isPortFree(port) {
    return new Promise((resolve) => {
        const srv = net.createServer();
        srv.once('error', () => resolve(false));
        srv.listen(port, '127.0.0.1', () => srv.close(() => resolve(true)));
    });
}

async function pickPort() {
    if (await isPortFree(PREFERRED_PORT)) return PREFERRED_PORT;
    return new Promise((resolve, reject) => {
        const srv = net.createServer();
        srv.once('error', reject);
        srv.listen(0, '127.0.0.1', () => {
            const { port } = srv.address();
            srv.close(() => resolve(port));
        });
    });
}

function waitForServer(port, timeoutMs = 30000) {
    const start = Date.now();
    return new Promise((resolve, reject) => {
        const attempt = () => {
            const sock = net.connect(port, '127.0.0.1');
            sock.once('connect', () => { sock.destroy(); resolve(); });
            sock.once('error', () => {
                sock.destroy();
                if (Date.now() - start > timeoutMs) reject(new Error('The local server did not start in time.'));
                else setTimeout(attempt, 120);
            });
        };
        attempt();
    });
}

// ---------------------------------------------------------------- backups

function stamp(date = new Date()) {
    const p = (n) => String(n).padStart(2, '0');
    return `${date.getFullYear()}-${p(date.getMonth() + 1)}-${p(date.getDate())}`;
}

// A consistent copy of the database, made by SQLite itself (VACUUM INTO), safe while the app is running.
async function snapshotDatabase(dest) {
    if (fs.existsSync(dest)) fs.rmSync(dest);
    const code = "$p = new PDO('sqlite:' . $argv[1]); $p->exec('VACUUM INTO ' . $p->quote($argv[2]));";
    const result = await runPhp(['-r', code, '--', dbFile, dest], launchEnv || process.env);
    if (result.code !== 0 || !fs.existsSync(dest)) {
        throw new Error('Could not copy the database.\n' + result.output);
    }
}

async function dailyBackup() {
    if (!fs.existsSync(dbFile) || fs.statSync(dbFile).size === 0) return;
    fs.mkdirSync(backupsDir, { recursive: true });
    const today = path.join(backupsDir, `cove-${stamp()}.sqlite`);
    if (!fs.existsSync(today)) await snapshotDatabase(today);

    const old = fs.readdirSync(backupsDir).filter((f) => /^cove-\d{4}-\d{2}-\d{2}\.sqlite$/.test(f)).sort().reverse().slice(BACKUPS_KEPT);
    for (const f of old) fs.rmSync(path.join(backupsDir, f), { force: true });
}

async function backupNow() {
    const { canceled, filePath } = await dialog.showSaveDialog(mainWindow, {
        title: 'Back up your Cove data',
        defaultPath: path.join(app.getPath('documents'), `Cove-backup-${stamp()}.sqlite`),
        filters: [{ name: 'Cove backup', extensions: ['sqlite'] }],
    });
    if (canceled || !filePath) return;
    try {
        await snapshotDatabase(filePath);
        dialog.showMessageBox(mainWindow, { type: 'info', message: 'Backup saved', detail: filePath });
    } catch (e) {
        dialog.showErrorBox('Backup failed', String(e.message || e));
    }
}

async function restoreBackup() {
    const { canceled, filePaths } = await dialog.showOpenDialog(mainWindow, {
        title: 'Restore a Cove backup',
        defaultPath: backupsDir,
        properties: ['openFile'],
        filters: [{ name: 'Cove backup', extensions: ['sqlite'] }],
    });
    if (canceled || !filePaths[0]) return;

    const chosen = filePaths[0];
    const head = Buffer.alloc(16);
    const fd = fs.openSync(chosen, 'r');
    fs.readSync(fd, head, 0, 16, 0);
    fs.closeSync(fd);
    if (head.toString('latin1') !== SQLITE_HEADER) {
        dialog.showErrorBox('Restore failed', 'That file is not a Cove backup.');
        return;
    }

    const answer = await dialog.showMessageBox(mainWindow, {
        type: 'warning',
        buttons: ['Restore', 'Cancel'],
        defaultId: 1,
        cancelId: 1,
        message: 'Replace your current notes, tasks and commands with this backup?',
        detail: 'A copy of your current data is saved first, so you can undo this from the backups folder.',
    });
    if (answer.response !== 0) return;

    try {
        fs.mkdirSync(backupsDir, { recursive: true });
        await snapshotDatabase(path.join(backupsDir, `before-restore-${Date.now()}.sqlite`));
        fs.copyFileSync(chosen, dbFile);
        const mig = await runPhp(['artisan', 'migrate', '--force'], launchEnv);
        if (mig.code !== 0) throw new Error(mig.output);
        mainWindow.webContents.reloadIgnoringCache();
    } catch (e) {
        dialog.showErrorBox('Restore failed', String(e.message || e));
    }
}

// ---------------------------------------------------------------- backend

async function startBackend() {
    const port = await pickPort();
    const token = crypto.randomBytes(24).toString('hex');
    launchEnv = buildEnv(port, token);

    await dailyBackup().catch(() => {});

    const mig = await runPhp(['artisan', 'migrate', '--force'], launchEnv);
    if (mig.code !== 0) throw new Error('Database setup failed:\n' + mig.output);

    // Same as "artisan serve": run from public/ with the framework's router script, which serves files from the working directory.
    phpProcess = spawn(path.join(phpDir, 'php.exe'), phpArgs('-S', `127.0.0.1:${port}`, path.join(appDir, 'server.php')), {
        cwd: path.join(appDir, 'public'), env: launchEnv, windowsHide: true,
    });
    phpProcess.on('exit', (code) => {
        if (!app.isQuitting && code) dialog.showErrorBox('Cove', `The local server stopped (exit code ${code}). Please restart Cove.`);
    });
    await waitForServer(port);

    baseUrl = `http://127.0.0.1:${port}`;
    // Only this window knows the token, so nothing else on the computer can talk to the server.
    await session.defaultSession.cookies.set({
        url: baseUrl, name: 'cove_local', value: token, httpOnly: true, sameSite: 'strict',
    });
    return baseUrl;
}

function stopBackend() {
    app.isQuitting = true;
    if (phpProcess && !phpProcess.killed) phpProcess.kill();
}

// ---------------------------------------------------------------- window

function readWindowState() {
    const fallback = { width: 1280, height: 860 };
    try {
        const s = JSON.parse(fs.readFileSync(stateFile, 'utf8'));
        const visible = screen.getAllDisplays().some((d) => {
            const b = d.workArea;
            return s.x >= b.x - 50 && s.y >= b.y - 50 && s.x < b.x + b.width - 100 && s.y < b.y + b.height - 100;
        });
        return { ...fallback, width: s.width || fallback.width, height: s.height || fallback.height, ...(visible ? { x: s.x, y: s.y } : {}), maximized: !!s.maximized };
    } catch (e) {
        return fallback;
    }
}

function trackWindowState(win) {
    let timer = null;
    const save = () => {
        if (win.isDestroyed()) return;
        const maximized = win.isMaximized();
        const b = maximized ? (win.getNormalBounds ? win.getNormalBounds() : win.getBounds()) : win.getBounds();
        try { fs.writeFileSync(stateFile, JSON.stringify({ ...b, maximized })); } catch (e) { /* not critical */ }
    };
    const later = () => { clearTimeout(timer); timer = setTimeout(save, 400); };
    win.on('resize', later);
    win.on('move', later);
    win.on('close', save);
}

function loadingPage(message, detail) {
    const html = `<!doctype html><meta charset="utf-8"><title>Cove</title>
<style>
  :root { color-scheme: light; --bg:#f7fbfa; --fg:#13292a; --muted:#4c6b69; }
  html,body { height:100%; margin:0; background:var(--bg); color:var(--fg); font-family:'Segoe UI',system-ui,sans-serif; }
  body { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:14px; text-align:center; padding:24px; box-sizing:border-box; }
  svg { width:72px; height:72px; animation:breathe 1.8s ease-in-out infinite; }
  @keyframes breathe { 50% { opacity:.55; transform:scale(.96); } }
  p { margin:0; color:var(--muted); font-size:13px; max-width:520px; white-space:pre-wrap; }
  b { font-size:15px; letter-spacing:.2em; font-weight:600; }
</style>
<svg viewBox="0 0 64 64"><defs><linearGradient id="g" gradientUnits="userSpaceOnUse" x1="8" y1="8" x2="56" y2="56"><stop offset="0" stop-color="#55b8a5"/><stop offset="1" stop-color="#1f7a6b"/></linearGradient></defs>
<path d="M44 12 A23 23 0 1 0 44 52" fill="none" stroke="url(#g)" stroke-width="4.5" stroke-linecap="round"/>
<path d="M24 27 q4.5 -5 9 0 t9 0" fill="none" stroke="#55b8a5" stroke-width="3.2" stroke-linecap="round"/>
<path d="M24 37 q4.5 -5 9 0 t9 0" fill="none" stroke="#1f7a6b" stroke-width="3.2" stroke-linecap="round"/></svg>
<b>COVE</b><p>${message}</p>${detail ? `<p>${detail.replace(/&/g, '&amp;').replace(/</g, '&lt;')}</p>` : ''}`;
    return 'data:text/html;charset=utf-8,' + encodeURIComponent(html);
}

function buildMenu() {
    const template = [
        {
            label: 'File',
            submenu: [
                { label: 'Back up data…', accelerator: 'CmdOrCtrl+Shift+B', click: () => backupNow() },
                { label: 'Restore from backup…', click: () => restoreBackup() },
                { type: 'separator' },
                { label: 'Open data folder', click: () => shell.openPath(dataDir) },
                { label: 'Open automatic backups', click: () => { fs.mkdirSync(backupsDir, { recursive: true }); shell.openPath(backupsDir); } },
                { type: 'separator' },
                { role: 'quit' },
            ],
        },
        { label: 'Edit', submenu: [{ role: 'undo' }, { role: 'redo' }, { type: 'separator' }, { role: 'cut' }, { role: 'copy' }, { role: 'paste' }, { role: 'selectAll' }] },
        {
            label: 'View',
            submenu: [
                { role: 'reload' },
                { type: 'separator' },
                { role: 'resetZoom' }, { role: 'zoomIn' }, { role: 'zoomOut' },
                { type: 'separator' },
                { role: 'togglefullscreen' },
                ...(app.isPackaged ? [] : [{ type: 'separator' }, { role: 'toggleDevTools' }]),
            ],
        },
        {
            label: 'Help',
            submenu: [{
                label: 'About Cove',
                click: () => dialog.showMessageBox(mainWindow, {
                    type: 'info', title: 'About Cove', message: `Cove ${app.getVersion()}`,
                    detail: `Notes, tasks and commands that stay on this computer.\n\nYour data: ${dbFile}`,
                    icon: iconPath,
                }),
            }],
        },
    ];
    Menu.setApplicationMenu(Menu.buildFromTemplate(template));
}

async function createWindow() {
    const state = readWindowState();
    const win = new BrowserWindow({
        width: state.width, height: state.height, x: state.x, y: state.y,
        minWidth: 900, minHeight: 600,
        show: false,
        autoHideMenuBar: true,
        title: 'Cove',
        icon: iconPath,
        backgroundColor: '#f7fbfa', // light is the default theme
        webPreferences: { contextIsolation: true, sandbox: true, spellcheck: false },
    });
    mainWindow = win;
    if (state.maximized) win.maximize();
    trackWindowState(win);
    win.once('ready-to-show', () => win.show());
    win.on('closed', () => { mainWindow = null; });
    win.webContents.on('render-process-gone', (_e, details) => {
        dialog.showErrorBox('Cove', `The window stopped working (${details.reason}). Please restart Cove.`);
    });

    win.loadURL(loadingPage('Starting…'));

    try {
        const url = await startBackend();
        win.webContents.setWindowOpenHandler(({ url: target }) => {
            if (/^https?:\/\//i.test(target)) shell.openExternal(target);
            return { action: 'deny' };
        });
        win.webContents.on('will-navigate', (event, target) => {
            if (!target.startsWith(url)) {
                event.preventDefault();
                if (/^https?:\/\//i.test(target)) shell.openExternal(target);
            }
        });
        await win.loadURL(url);
    } catch (err) {
        win.loadURL(loadingPage('Cove could not start.', String(err.message || err)));
    }
}

// ---------------------------------------------------------------- lifecycle

if (!app.requestSingleInstanceLock()) {
    app.quit();
} else {
    app.on('second-instance', () => {
        if (mainWindow) {
            if (mainWindow.isMinimized()) mainWindow.restore();
            mainWindow.focus();
        }
    });
    app.whenReady().then(() => {
        // Deny everything except writing to the clipboard, which the Copy buttons need.
        const allowed = new Set(['clipboard-sanitized-write']);
        session.defaultSession.setPermissionRequestHandler((_wc, permission, callback) => callback(allowed.has(permission)));
        session.defaultSession.setPermissionCheckHandler((_wc, permission) => allowed.has(permission));
        buildMenu();
        return createWindow();
    }).catch((err) => {
        dialog.showErrorBox('Cove failed to start', String(err.message || err));
        app.quit();
    });
    app.on('window-all-closed', () => app.quit());
    app.on('before-quit', stopBackend);
}
