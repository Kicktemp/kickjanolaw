import fs from 'fs-extra';
import {dirname} from 'path';
import {stringsreplace} from '../../config.js';

export const copyFile = async (src, dest, manipulateData = false) => {
  try {
    const dataRaw = await fs.promises.readFile(src, 'utf8');
    let data = dataRaw;

    if (manipulateData) {
      for (let [key, value] of Object.entries(stringsreplace)) {
        const safeKey = key.replace('[', '\\[').replace(']', '\\]');
        const re = new RegExp(safeKey, 'g');
        data = data.replace(re, value);
      }
    }

    await fs.promises.mkdir(dirname(dest), { recursive: true });
    await fs.promises.writeFile(dest, data, 'utf8');
  } catch (err) {
    console.error(`[copy-file] error copying ${src} -> ${dest}: ${err?.message || err}`);
    throw err;
  }
}
