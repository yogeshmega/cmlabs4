var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer');
browserSync = require('browser-sync');
watch = require('gulp-watch');

// SASS
gulp.task('sass', function() {
    gulp.src('assets/styles/sass/*.scss')
        .pipe(sass({
                outputStyle: 'compressed',
            })
            .on('error', sass.logError))
        .pipe(autoprefixer({ browsers: ['last 2 versions'] }))
        .pipe(gulp.dest('assets/styles/css/'));
});

// WATCH
gulp.task('watch', function() {
    gulp.watch('assets/styles/**/*.scss', ['sass']);
});

// BROWSER SYNC
gulp.task('browser-sync', function() {
    browserSync({
        files: "assets/styles/css/*.css",
        proxy: "cmlabs.dev.hpj"
    });
});

gulp.task('default', ['sass', 'watch', 'browser-sync']);