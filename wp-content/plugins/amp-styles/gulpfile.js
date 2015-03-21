var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();
var browserSync = require('browser-sync');

// Global Config Values
var config = require('./bower_components/css/gulp.json');

// Browser Sync Task
gulp.task('browser-sync', function () {
    browserSync.init(null, {
        proxy: 'localhost/ampcms'
    });
});

// Sass task
gulp.task('sass', function () {
    gulp.src([config.sass_path + '/**/*.scss'])
        .pipe(plugins.rubySass(config.sassOptions))
        .pipe(plugins.autoprefixer(config.browsers))
        .pipe(gulp.dest(config.css_path))
        .pipe(plugins.filter('**/*.css'))
        .pipe(plugins.livereload());
});

gulp.task('lint', function() {
    gulp.src(config.sass_path + '/**/*.scss')
        .pipe(plugins.scssLint(config.lintOptions))
});

// Default gulp task
gulp.task('default', ['sass'], function () {
    gulp.watch('**/*.scss', ['sass', 'lint']);
});

// Sass Watch task
gulp.task('watch', ['sass', 'lint' ], function () {
    gulp.watch('**/*.scss', ['sass', 'lint']);
});