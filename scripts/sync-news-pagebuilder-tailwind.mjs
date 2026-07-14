import { copyFile, mkdir, stat } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const projectRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const source = path.resolve(
    projectRoot,
    '..',
    'regulierungs-check-admin',
    'public',
    'build',
    'css',
    'tailwind.min.css',
);
const target = path.resolve(
    projectRoot,
    'public',
    'adminresources',
    'css',
    'pagebuilder-tailwind.min.css',
);

try {
    await stat(source);
} catch {
    throw new Error(`Admin-Tailwind wurde nicht gefunden: ${source}`);
}

await mkdir(path.dirname(target), { recursive: true });
await copyFile(source, target);

const targetStats = await stat(target);

console.log(`News-PageBuilder-Tailwind synchronisiert (${targetStats.size} Bytes): ${target}`);
