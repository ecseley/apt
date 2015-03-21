'use strict';
module.exports = function(grunt) {

  grunt.initConfig({
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        'assets/js/*.js',
        '!assets/js/scripts.min.js'
      ]
    },
    autoprefixer: {
      options: {
        browsers: ['last 3 versions', "BlackBerry 10", "Android 4" ]
      },
      no_dest: {
        src: 'assets/css/main.min.css' // globbing is also possible here
      },
    },
 
    imagemin: {
      dynamic: { // Another target
        files: [{
          expand: true, // Enable dynamic expansion
          cwd: 'assets/', // Src matches are relative to this path
          src: ['**/*.{png,jpg,gif}'], // Actual patterns to match
          dest: 'assets/' // Destination path prefix
        }]
      }
 
    },
    sass: {
      dist: {
        options: {
          style: 'compressed',
          compass: true,
          // Source maps are available, but require Sass 3.3.0 to be installed
          // https://github.com/gruntjs/grunt-contrib-sass#sourcemap
          sourcemap: false
        },
        files: {
          'assets/css/main.min.css': [
            'assets/sass/apt-styles.scss'
          ]
        }
      }
    },
    uglify: {
      dist: {
        files: {
          'assets/js/scripts.min.js': [
            'assets/js/_*.js'
          ]
        },
        options: {
          // JS source map: to enable, uncomment the lines below and update sourceMappingURL based on your install
          // sourceMap: 'assets/js/scripts.min.js.map',
          // sourceMappingURL: '/app/themes/roots/assets/js/scripts.min.js.map'
        }
      }
    },
    watch: {
      sass: {
        files: [
          'assets/sass/*.scss'
        ],
        tasks: ['sass']
      },
      js: {
        files: [
          '<%= jshint.all %>'
        ],
        tasks: ['jshint', 'uglify']
      },
      livereload: {
        // Browser live reloading
        // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
        options: {
          livereload: false
        },
        files: [
          'assets/css/main.min.css',
          'assets/js/scripts.min.js',
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

  // Load tasks
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-sass');
  // grunt.loadNpmTasks('grunt-wp-version');
  grunt.loadNpmTasks('grunt-contrib-imagemin');

  // Register tasks
  grunt.registerTask('default', [
    'clean',
    'sass',
    'autoprefixer',
    'uglify',
    // 'version',
    'imagemin'
  ]);
  grunt.registerTask('dev', [
    'watch'
  ]);

};
