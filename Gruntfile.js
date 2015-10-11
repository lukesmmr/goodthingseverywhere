'use strict';
module.exports = function(grunt) {

  // Load all tasks
  require('load-grunt-tasks')(grunt);

  var jsFileList = [
    'assets/js/plugins/bootstrap/transition.js',
    'assets/js/plugins/bootstrap/alert.js',
    'assets/js/plugins/bootstrap/button.js',
    'assets/js/plugins/bootstrap/carousel.js',
    'assets/js/plugins/bootstrap/collapse.js',
    'assets/js/plugins/bootstrap/dropdown.js',
    'assets/js/plugins/bootstrap/modal.js',
    'assets/js/plugins/bootstrap/tooltip.js',
    'assets/js/plugins/bootstrap/popover.js',
    // 'assets/js/plugins/bootstrap/scrollspy.js',
    // 'assets/js/plugins/bootstrap/tab.js',
    // 'assets/js/plugins/bootstrap/affix.js',
    'assets/js/plugins/*.js',
    'assets/js/_*.js'
  ];

  var cssFileList = [
    'assets/css/bootstrap-select.css',
    'assets/css/app.css'
  ];

  grunt.initConfig({
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        'assets/js/*.js',
        '!assets/js/scripts.js',
        '!assets/**/*.min.*'
      ]
    },
    less: {
      dist: {
        files: {
          'assets/css/app.css': [
            'assets/less/app.less'
          ]
        },
        options: {
          compress: false,
          // LESS source map
          // To enable, set sourceMap to true and update sourceMapRootpath based on your install
          sourceMap: true,
          sourceMapFilename: 'assets/css/app.css.map',
          sourceMapRootpath: '/app/themes/roots/'
        }
      },
    },
    concat: {
      options: {
        separator: ';',
      },
      js: {
        src: [jsFileList],
        dest: 'assets/js/scripts.js',
      },
      css: {
        src: [cssFileList],
        dest: 'assets/css/main.css'
      }
    },
    cssmin: {
      options: {
        keepSpecialComments: 0
      },
      target: {
        files: {
          'assets/css/main.min.css': 'assets/css/main.css'
        }
      }
    },
    uglify: {
      dist: {
        files: {
          'assets/js/scripts.min.js': [jsFileList]
        }
      }
    },
    version: {
      default: {
        options: {
          format: true,
          length: 32,
          manifest: 'assets/manifest.json',
          querystring: {
            style: 'roots_css',
            script: 'roots_js'
          }
        },
        files: {
          'lib/scripts.php': 'assets/{css,js}/{main,scripts}.min.{css,js}'
        }
      }
    },
    watch: {
      less: {
        files: [
          'assets/less/*.less',
          'assets/less/bootstrap/*.less'
        ],
        tasks: ['less', 'concat:css']
      },
      js: {
        files: [
          '<%= jshint.all %>'
        ],
        tasks: ['jshint', 'concat']
      },
      livereload: {
        // Browser live reloading
        // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
        options: {
          livereload: true
        },
        files: [
          'assets/css/main.css',
          'assets/js/scripts.js',
          'templates/*.php',
          '*.php'
        ]
      }
    },
    clean: {
      dist: [
        'assets/css/main.min.css',
        'assets/js/scripts.min.js'
      ]
    }
  });

  // Register tasks
  grunt.registerTask('default', [
    'dev'
  ]);
  grunt.registerTask('dev', [
    'jshint',
    'less',
    'concat'
  ]);
  grunt.registerTask('build', [
    'clean',
    'jshint',
    'less',
    'concat:css',
    'cssmin',
    'uglify',
    'version'
  ]);

};
