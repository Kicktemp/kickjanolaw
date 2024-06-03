import {createRequire} from "module";
import {extend} from "./scripts/tasks/util.js";

const require = createRequire(import.meta.url);
export const pjson = require('./package.json');

export const config = {
  paths: {
    copy: [
      {
        casesensitive: false,
        src: 'src/structure/',
        glob: 'src/structure/**/**',
        replaceGlob: 'src/structure/**/**.{php,html,xml,php,ini,less,json,js,css}',
        dest: '../joomla/dist/',
      },
      {
        casesensitive: false,
        src: 'src/structure/',
        glob: 'src/structure/**/**',
        replaceGlob: 'src/structure/**/**.{php,html,xml,php,ini,less,json,js,css}',
        dest: '../joomla/dist4/',
      },
      {
        casesensitive: false,
        src: 'src/structure/',
        glob: 'src/structure/**/**',
        replaceGlob: 'src/structure/**/**.{php,html,xml,php,ini,less,json,js,css}',
        dest: '../joomla/dist5/',
      }
    ],
    release: [
      {
        src: 'src/structure/',
        glob: 'src/structure/**/**',
        replaceGlob: 'src/structure/**/**.{php,html,xml,php,ini,less,json,js,css}',
        dest: 'releasefiles/',
      }
    ],
    package: [
      {
        src: 'releasefiles/plugins/system/kickjanolaw/',
        glob: 'releasefiles/plugins/system/kickjanolaw/**/**',
        dest: 'sourcefiles/plg_system_kickjanolaw/'
      },
      {
        src: 'releasefiles/media/plg_system_kickjanolaw/',
        glob: 'releasefiles/media/plg_system_kickjanolaw/**/**',
        dest: 'sourcefiles/plg_system_kickjanolaw/media/'
      },
    ],
    cleaner: [
      'releasefiles/',
      'sourcefiles/',
      'archives/',
      'package/'
    ],
    updateXML: {
      src: 'update.xml',
      rename: 'oldupdate.xml',
      template: 'updatetemplate.xml',
      dest: './'
    },
  },
  archiver: [
    {
      destination : 'archives/',
      name: 'plg_system_kickjanolaw',
      suffixversion: true,
      types: [
        {
          extension: '.zip',
          type: 'zip',
          options: {
            zlib: { 'level': 9 }
          }
        }
      ],
      folders: [
        'sourcefiles/plg_system_kickjanolaw'
      ],
      files: [
      ]
    }
  ]
};

export const stringsreplace = extend({}, {"[VERSION]": pjson.version}, pjson.placeholder);
