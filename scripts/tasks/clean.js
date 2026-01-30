import fs from 'fs-extra';
import {config} from '../../config.js';

const log = console.log.bind(console);

try {
  let removed = 0;
  (config.paths.cleaner || []).forEach((path) => {
    try {
      fs.rmSync(path, { recursive: true, force: true });
      removed++;
      log(`[clean] removed: ${path}`);
    } catch (e) {
      log(`[clean] error removing ${path}: ${e?.message || e}`);
    }
  });
  log(`[clean] done. paths processed: ${removed}`);
} catch (e) {
  console.error(`[clean] fatal error: ${e?.message || e}`);
  process.exitCode = 1;
}
