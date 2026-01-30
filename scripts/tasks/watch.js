import chokidar from 'chokidar';
import fs from "fs-extra";
import {glob} from 'glob';
import {config} from '../../config.js';
import {renameDest} from './util.js';
import {copyFile} from "./copy-file.js";
const log = console.log.bind(console);

export const watch = () => {
  const task = process.env.KICK_CONFIG || 'copy';
  let settings = config.paths[task];
  if (!settings) {
    log(`No paths configured for task "${task}". Falling back to 'copy'.`);
    settings = config.paths.copy || [];
  }
  if (!Array.isArray(settings)) {
    settings = [settings];
  }

  settings.forEach((setting, index) => {
    const src = setting.src;
    const replaceGlob = setting.replaceGlob || `${src}**/**`;

    log(`[watch] (${task} #${index + 1}) Watching: ${src} -> ${setting.dest}`);

    chokidar
      .watch(src, {
        persistent: true,
        ignoreInitial: true,
      })
      .on('all', async (event, file) => {
        try {
          const copyEvents = ['change', 'add'];
          const replaceDataFiles = glob.sync(replaceGlob, { dot: true });
          const dest = await renameDest(file, setting);

          if (copyEvents.includes(event)) {
            const stat = await fs.promises.lstat(file);
            if (stat.isFile()) {
              await copyFile(file, dest, replaceDataFiles.includes(file));
              log(`[watch] (${task} #${index + 1}) ${event}: ${file} -> ${dest}`);
            }
          }

          if (event === 'unlink') {
            fs.promises
              .unlink(dest)
              .then(() => log(`[watch] (${task} #${index + 1}) removed: ${dest}`))
              .catch((err) => log(`[watch] (${task} #${index + 1}) unlink error for ${dest}: ${err?.message || err}`));
          }

          if (event === 'unlinkDir') {
            fs.rmSync(dest, { recursive: true, force: true });
            log(`[watch] (${task} #${index + 1}) removed dir: ${dest}`);
          }
        } catch (e) {
          log(`[watch] (${task} #${index + 1}) error: ${e?.message || e}`);
        }
      });
  });
};

watch();
