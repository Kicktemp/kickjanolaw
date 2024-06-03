import chokidar from 'chokidar';
import fs from "fs-extra";
import {glob} from "glob";
import {config} from '../../config.js';
import {renameDest} from './util.js';
import {copyFile} from "./copy-file.js";
const log = console.log.bind(console);

export const watch = () => {
  // Joomla 3
  const copyconfig = config.paths.copy[0];
  log(copyconfig.glob)
  chokidar.watch(copyconfig.src, {
    persistent: true,
    ignoreInitial: true
  }).on('all', async (event, file) => {
    const copy = ['change', 'add'];
    const replaceDataFiles = await glob.sync(copyconfig.replaceGlob, {dot: true});
    const dest = await renameDest(file, copyconfig);

    if (copy.includes(event)) {
      const stat = await fs.promises.lstat(file);

      if (stat.isFile()) {
        await copyFile(file, dest, replaceDataFiles.includes(file));
        log(`File ${dest} has been change`)
      }
    }

    if (event === 'unlink') {
      fs.promises.unlink(dest)
          .then(
              b => log(`File ${dest} has been removed`)
          )
          .catch((err) => {
            return console.log(err);
          });
    }

    if (event === 'unlinkDir') {
      fs.rmSync(dest, { recursive: true, force: true });
      log(`Path ${dest} has been removed`)
    }
  });

  // Joomla 4
  const copyconfig1 = config.paths.copy[1];
  log(copyconfig1.glob)
  chokidar.watch(copyconfig1.src, {
    persistent: true,
    ignoreInitial: true
  }).on('all', async (event, file) => {
    const copy = ['change', 'add'];
    const replaceDataFiles = await glob.sync(copyconfig1.replaceGlob, {dot: true});
    const dest = await renameDest(file, copyconfig1);

    if (copy.includes(event)) {
      const stat = await fs.promises.lstat(file);

      if (stat.isFile()) {
        await copyFile(file, dest, replaceDataFiles.includes(file));
        log(`File ${dest} has been change`)
      }
    }

    if (event === 'unlink') {
      fs.promises.unlink(dest)
          .then(
              b => log(`File ${dest} has been removed`)
          )
          .catch((err) => {
            return console.log(err);
          });
    }

    if (event === 'unlinkDir') {
      fs.rmSync(dest, { recursive: true, force: true });
      log(`Path ${dest} has been removed`)
    }
  });

  // Joomla 5
  const copyconfig2 = config.paths.copy[2];
  log(copyconfig2.glob)
  chokidar.watch(copyconfig2.src, {
    persistent: true,
    ignoreInitial: true
  }).on('all', async (event, file) => {
    const copy = ['change', 'add'];
    const replaceDataFiles = await glob.sync(copyconfig2.replaceGlob, {dot: true});
    const dest = await renameDest(file, copyconfig2);

    if (copy.includes(event)) {
      const stat = await fs.promises.lstat(file);

      if (stat.isFile()) {
        await copyFile(file, dest, replaceDataFiles.includes(file));
        log(`File ${dest} has been change`)
      }
    }

    if (event === 'unlink') {
      fs.promises.unlink(dest)
          .then(
              b => log(`File ${dest} has been removed`)
          )
          .catch((err) => {
            return console.log(err);
          });
    }

    if (event === 'unlinkDir') {
      fs.rmSync(dest, { recursive: true, force: true });
      log(`Path ${dest} has been removed`)
    }
  });
}

watch();
