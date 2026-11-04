// Builds build/icon.ico and build/icon.png from public/images/favicon.svg.
// Uses headless Chrome as the rasteriser, so there are no extra dependencies.
// Run with: node desktop/make-icons.cjs
const fs = require('fs');
const os = require('os');
const path = require('path');
const { spawnSync } = require('child_process');

const root = path.resolve(__dirname, '..');
const out = path.join(root, 'build');
const CHROME = [
    process.env.CHROME_PATH,
    'C:/Program Files/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
].find((p) => p && fs.existsSync(p));
if (!CHROME) throw new Error('Chrome or Edge is needed to render the icons (or set CHROME_PATH).');

const ICO_SIZES = [16, 24, 32, 48, 64, 128, 256];
const svg = fs.readFileSync(path.join(root, 'public', 'images', 'favicon.svg'), 'utf8');
const svgUri = 'data:image/svg+xml;base64,' + Buffer.from(svg).toString('base64');

const html = `<!doctype html><body><pre id="out"></pre><script>
const SIZES = ${JSON.stringify([...ICO_SIZES, 512])};
const img = new Image();
img.onload = () => {
  const res = {};
  for (const s of SIZES) {
    const c = document.createElement('canvas'); c.width = c.height = s;
    const g = c.getContext('2d'); g.imageSmoothingQuality = 'high'; g.drawImage(img, 0, 0, s, s);
    res[s] = c.toDataURL('image/png');
  }
  document.getElementById('out').textContent = 'RESULT' + JSON.stringify(res) + 'END';
};
img.onerror = () => { document.getElementById('out').textContent = 'ERR could not load the SVG'; };
img.src = ${JSON.stringify(svgUri)};
</script></body>`;

const tmp = fs.mkdtempSync(path.join(os.tmpdir(), 'cove-icons-'));
const page = path.join(tmp, 'render.html');
fs.writeFileSync(page, html);
const r = spawnSync(CHROME, [
    '--headless=new', '--disable-gpu', '--no-sandbox', '--user-data-dir=' + path.join(tmp, 'profile'),
    '--virtual-time-budget=10000', '--dump-dom', require('url').pathToFileURL(page).href,
], { encoding: 'utf8', maxBuffer: 128 * 1024 * 1024 });
const m = /RESULT(\{[\s\S]*?\})END/.exec(r.stdout || '');
if (!m) { console.error('Render failed:', (r.stdout || '').match(/ERR [^<]*/)?.[0] || (r.stderr || '').slice(0, 400)); process.exit(1); }
const data = JSON.parse(m[1]);
fs.rmSync(tmp, { recursive: true, force: true });

fs.mkdirSync(out, { recursive: true });
const png = (s) => Buffer.from(data[s].split(',')[1], 'base64');

// icon.ico: PNG-encoded images
const dir = Buffer.alloc(6); dir.writeUInt16LE(1, 2); dir.writeUInt16LE(ICO_SIZES.length, 4);
let offset = 6 + 16 * ICO_SIZES.length;
const entries = ICO_SIZES.map((s) => {
    const e = Buffer.alloc(16);
    e.writeUInt8(s === 256 ? 0 : s, 0); e.writeUInt8(s === 256 ? 0 : s, 1);
    e.writeUInt16LE(1, 4); e.writeUInt16LE(32, 6); e.writeUInt32LE(png(s).length, 8); e.writeUInt32LE(offset, 12);
    offset += png(s).length;
    return e;
});
fs.writeFileSync(path.join(out, 'icon.ico'), Buffer.concat([dir, ...entries, ...ICO_SIZES.map(png)]));
fs.writeFileSync(path.join(out, 'icon.png'), png(512));
console.log('wrote build/icon.ico (' + ICO_SIZES.join(', ') + ') and build/icon.png');
