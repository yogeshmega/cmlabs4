// Dependencies
var gulp = require('gulp');
var sass = require('gulp-sass');
var sassGlob = require('gulp-sass-glob');
var browserSync = require('browser-sync').create();
var postcss = require('gulp-postcss');
var assets = require('postcss-assets');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
// Image optimization
var imagemin = require('gulp-imagemin'),
    imageminPngquant = require('imagemin-pngquant');

var imageminPlugins = [
    imagemin.gifsicle(),
    imagemin.jpegtran(),
    imageminPngquant(),
    imagemin.svgo()
]

// Variables
var sassDir = 'wp-content/themes/hpjmaster/assets/styles/sass/';
var cssDir = 'wp-content/themes/hpjmaster/assets/styles/css/';
var imageDir = ['./wp-content/uploads/', 'wp-content/themes/hpjmaster/assets/images/'];

// Static Server + watching scss/html files
gulp.task('serve', ['sass'], function() {

    browserSync.init({
        proxy: "cmlabs.dev.hpj",
        notify: false,
    });

    gulp.watch(sassDir + "**/*.scss", ['sass']);
});

// Compile sass into CSS & auto-inject into browsers
gulp.task('sass', function() {
    return gulp.src(sassDir + "**/*.scss")
        .pipe(sourcemaps.init())
        .pipe(sassGlob())
        .pipe(sass({
            includePaths: [
                'node_modules/bootstrap-sass/assets/stylesheets',
                'node_modules/sass-mq',
            ],
            outputStyle: 'compressed',
        }))
        .on('error', sass.logError)
        .pipe(autoprefixer())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(cssDir))
        .pipe(postcss([assets({
            loadPaths: ['images/']
        })]))
        .pipe(browserSync.stream());
});

gulp.task('prebuild', ['sass'], function(){
    imageDir.forEach(function(dir) {
        gulp.src([dir + '**/*.gif', dir + '**/*.jpeg', dir + '**/*.png'])
        .pipe(imagemin(imageminPlugins))
        .pipe(gulp.dest(dir));
    });
});

gulp.task('default', ['serve']);