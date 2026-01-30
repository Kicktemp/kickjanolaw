import fs from 'fs-extra';
import archiver from 'archiver';
import {config, pjson} from '../../config.js';

const log = console.log.bind(console);

// Task
export const buildArchives = () => {
  log('[archives] start');
  const builder = async () => {
    for (const archivesetup of config.archiver) {
      await build(archivesetup);
    }
  };

  builder()
    .then(() => log('[archives] done'))
    .catch((e) => {
      console.error(`[archives] fatal error: ${e?.message || e}`);
      process.exitCode = 1;
    });
}

const build = (archivesetup) => {
  return new Promise((resolve, reject) => {
    try {
      if (!fs.existsSync(archivesetup.destination)) {
        fs.mkdirSync(archivesetup.destination, { recursive: true });
      }

      let finished = 0;

      archivesetup.types.forEach(function (item) {
        try {
          let extensionname = archivesetup.destination + archivesetup.name + item.extension;
          if (archivesetup.suffixversion) {
            extensionname = archivesetup.destination + archivesetup.name + '_' + pjson.version + item.extension;
          }
          log(`[archives] building: ${extensionname} (${item.type})`);

          let output = fs.createWriteStream(extensionname);
          const archive = archiver(String(item.type), item.options);

          output.on('close', function ()
          {
            finished++;
            log(`[archives] finalized: ${extensionname} (${archive.pointer()} bytes)`);
            if (finished === archivesetup.types.length) {
              resolve();
            }
          });

          output.on('end', function () {
            log('[archives] stream drained');
          });

          archive.on('warning', function (err) {
            if (err.code === 'ENOENT') {
              log(`[archives] warning: ${err?.message || err}`);
            } else {
              reject(err);
            }
          });

          archive.on('error', function (err) {
            reject(err);
          });

          archive.pipe(output);

          archivesetup.folders.forEach(function (folder) {
            archive.directory(folder, false);
          });

          archive.finalize();
        } catch (e) {
          reject(e);
        }
      });
    } catch (e) {
      reject(e);
    }
  });
}

buildArchives();
