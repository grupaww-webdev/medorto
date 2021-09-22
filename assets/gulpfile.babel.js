import browserSync from 'browser-sync';
import upath from 'upath';
import gulp from 'gulp';
import sourcemaps from 'gulp-sourcemaps';
import gulpif from 'gulp-if';
import uglify from 'gulp-uglify';
import sass from 'gulp-sass';
import concat from 'gulp-concat';
import livereload from 'gulp-livereload';
import order from 'gulp-order';
import merge from 'merge-stream';
const babel = require('gulp-babel');
const imagemin = require('gulp-imagemin');

const env = process.env.GULP_ENV;
const options = {
  minify: env === 'prod',
  sourcemaps: env !== 'prod',
};
const vendorUiPath = upath.normalizeSafe('../vendor/sylius/sylius/src/Sylius/Bundle/UiBundle');
const nodeModulesPath = upath.normalizeSafe('../node_modules');
const distPath = "../public/assets/shop/";
const config = {
  src: './',
  scssin: [
    'shop/scss/**/*.scss',
  ],
  scssout: 'css/',
  cssin: [
    'shop/css/*.css',
  ],
  cssout: upath.joinSafe(distPath, 'css/'),
  cssoutname: 'style.css',
  jsin: [
    'shop/js/*.js',
  ],
  jsvendors: [
    'shop/js/plugin/*.js',
  ],
  jsout: upath.joinSafe(distPath, 'js/'),
  jsoutname: 'app.js',
  imgin: [
    'shop/img/**/*.{jpg,jpeg,png,gif,svg,ico}',
    upath.joinSafe(vendorUiPath, 'Resources/private/img/**'),
  ],
  imgout: upath.joinSafe(distPath, 'img/'),
  fontsin: 'shop/fonts/*.*',
  fontsout: upath.joinSafe(distPath, 'fonts/'),
};
const appStyle = () => {
  const sassStream = gulp.src(config.scssin)
    .pipe(gulpif(options.sourcemaps, sourcemaps.init()))
    .pipe(sass({
      outputStyle: 'compressed',
      includePaths: ['../node_modules']
    }))
    .pipe(concat('sass-files.scss'));
  const cssStream = gulp.src(config.cssin)
    .pipe(gulpif(options.sourcemaps, sourcemaps.init()))
    .pipe(concat('css-files.css'));
  return merge(cssStream, sassStream)
    .pipe(order(['css-files.css', 'sass-files.scss']))
    .pipe(concat(config.cssoutname))
    .pipe(gulpif(options.minify, uglify()))
    .pipe(gulpif(options.sourcemaps, sourcemaps.write()))
    .pipe(gulp.dest(config.cssout))
    .pipe(livereload());
};

const browserReload = (done) => {
  browserSync.reload();
  done();
};

const serve = async (done) => {
  browserSync({
    proxy: "http://127.0.0.1:8000"
  });
  done();
};

const watchFiles = () => {
  gulp.watch(upath.joinSafe(config.scssin[0]), gulp.series(appStyle, browserReload))
};

const appImages = () => {
  return gulp.src(config.imgin)
    // .pipe(imagemin())
    .pipe(gulp.dest(config.imgout));
};

const appFonts = () => {
  return gulp.src(config.fontsin)
    .pipe(gulp.dest(config.fontsout));
};

const appJs = () => {
  return gulp.src(config.jsin)
    .pipe(babel())
    .pipe(gulp.src(config.jsvendors))
    .pipe(gulp.dest(config.jsout));
};

export const build = gulp.parallel(appStyle, appImages, appFonts, appJs);
export const watching = gulp.parallel(watchFiles, serve);

gulp.task('watch', watching);
gulp.task('app-style', appStyle);
gulp.task('app-js', appJs);
gulp.task('app-img', appImages);
gulp.task('app-fonts', appFonts);

export default build;
