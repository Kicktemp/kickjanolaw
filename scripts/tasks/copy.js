import {glob} from 'glob';
import {config} from '../../config.js';
import {renameDest} from './util.js'
import {copyFile} from "./copy-file.js";
import fs from "fs-extra";

const log = console.log.bind(console);

export const copyFiles = async (task = process.env.KICK_CONFIG || 'copy') => {
  try {
    let settings = config.paths[task];
    if (!settings) {
      log(`[copy] No paths configured for task "${task}". Falling back to 'copy'.`);
      settings = config.paths.copy || [];
    }
    if (!Array.isArray(settings)) settings = [settings];

    for (let i = 0; i < settings.length; i++) {
      const setting = settings[i];
      const globPattern = setting.glob !== undefined ? setting.glob : setting.src + '**/**';
      const files = glob.sync(globPattern, { dot: true });
      const replaceDataFiles = setting.replaceGlob ? glob.sync(setting.replaceGlob, { dot: true }) : [];

      log(`[copy] (${task} #${i + 1}) from ${setting.src} -> ${setting.dest}`);
      log(`[copy] (${task} #${i + 1}) files found: ${files.length}`);

      for (const file of files) {
        try {
          const dest = await renameDest(file, setting);
          const stat = await fs.promises.lstat(file);
          if (stat.isFile()) {
            await copyFile(file, dest, replaceDataFiles.includes(file));
          }
        } catch (e) {
          log(`[copy] (${task} #${i + 1}) error processing ${file}: ${e?.message || e}`);
        }
      }

      log(`[copy] (${task} #${i + 1}) done.`);
    }
  } catch (e) {
    console.error(`[copy] fatal error: ${e?.message || e}`);
    process.exitCode = 1;
  }
}

copyFiles();
