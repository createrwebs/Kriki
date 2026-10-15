import { copyFileSync } from 'node:fs'
import { spawnSync } from 'node:child_process'
import { fileURLToPath } from 'node:url'
import { dirname, resolve } from 'node:path'

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..')
const result = spawnSync(process.execPath, [
    resolve(root, 'node_modules/vite/bin/vite.js'), 'build',
    '--config', 'resources/js/lab/runtime/vite.lib.config.js',
], { cwd: root, stdio: 'inherit' })
if (result.error) throw result.error
if (result.status !== 0) process.exit(result.status ?? 1)
copyFileSync(resolve(root, 'resources/js/lab/runtime/dist/__worker__.js'), resolve(root, 'public/__worker__.js'))
console.log('Lab runtime and public worker rebuilt.')
