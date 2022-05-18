export const pjson = require('../package.json');

export const config = {
  browserSyncConfig: {
    ghostMode: {
      clicks: true,
      scroll: true,
      links: true,
      forms: true
    },
    server: {
      baseDir: ['/dist/']
    },
    proxy: pjson.buildconfigs.proxy,
    https: false,
    open: false,
    debugInfo: false,
    watchTask: false,
    notify: {
      styles: [
        'padding: 8px 16px;',
        'position: fixed;',
        'font-size: 12px;',
        'font-weight: bold',
        'z-index: 9999;',
        'top: inherit',
        'border-radius: 0',
        'right: 0;',
        'top: 0;',
        'color: #f4f8f9;',
        'background-color: #026277;',
        'text-transform: uppercase'
      ]
    }
  },
  paths: {
    src: './src/',
    dest: './dist/',
    copy: {
      src: ['src/structure/**/**', 'src/structure/**/.*', '!src/structure/**/*.{php,html,xml,ini,less,json,js,css}', '!src/structure/**/.*.{php,html,xml,ini,less,json,js,css}'],
      replacesrc: ['src/structure/**/**.{php,html,xml,ini,less,json,js,css}', 'src/structure/**/.*.{php,html,xml,ini,less,json,js,css}'],
      dest: 'dist/',
      watch: ['src/structure/**/*.{php,html,xml,ini,less,json,js,css}'],
    },
    copyrelease: {
      src: ['src/structure/**/**', 'src/structure/**/.*', '!src/structure/**/*.{php,html,xml,ini,less,json,js,css}', '!src/structure/**/.*.{php,html,xml,ini,less,json,js,css}'],
      replacesrc: ['src/structure/**/**.{php,html,xml,ini,less,json,js,css}', 'src/structure/**/.*.{php,html,xml,ini,less,json,js,css}'],
      dest: 'releasefiles/'
    },
    cleaner: {
      releasefiles: 'releasefiles/',
      sourcefiles: 'sourcefiles/',
      archives: 'archives/',
      packages: 'package/'
    },
    updateXML: {
      src: 'update.xml',
      rename: 'oldupdate.xml',
      template: 'updatetemplate.xml',
      dest: './'
    },

  },
  packagefiles: [
    {
      src: './releasefiles/plugins/system/kickjanolaw/**/**',
      dest: 'sourcefiles/plg_system_kickjanolaw'
    },
    {
      src: './releasefiles/media/plg_system_kickjanolaw/**/**',
      dest: 'sourcefiles/plg_system_kickjanolaw/media'
    },
  ],
  archiver: [
    {
      destination : 'archives/',
      name: 'plg_system_kickjanolaw',
      suffixversion: true,
      types: [
        {
          extension: '.zip',
          type: 'zip'
        }
      ],
      folders: [
        'sourcefiles'
      ],
      files: [
      ]
    }
  ]
};

export const isProd = process.env.NODE_ENV === 'production';

export const stringsreplace = extend({}, {"[VERSION]": pjson.version} , pjson.placeholder);

function extend(target) {
  var sources = [].slice.call(arguments, 1);
  sources.forEach(function (source) {
    for (var prop in source) {
      target[prop] = source[prop];
    }
  });
  return target;
}
