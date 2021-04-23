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
    'scss/**/*.scss',
  ],
  scssout: 'css/',
  cssin: [
    'css/*.css',
  ],
  cssout: upath.joinSafe(distPath, 'css/'),
  cssoutname: 'app.css',
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
    // server: {
    //     baseDir: config.src
    // },
    // notify: false,
    // browser: "google chrome",
    proxy: "http://127.0.0.1:8000"
  });
  done();
};

const watchFiles = () => {
  gulp.watch(upath.joinSafe(config.scssin[0]), gulp.series(appStyle, browserReload))
};

export const build = gulp.parallel(appStyle);
export const watching = gulp.parallel(watchFiles, serve);

gulp.task('watch', watching);
gulp.task('app-style', appStyle);
export default build;
