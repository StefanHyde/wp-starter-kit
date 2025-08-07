import { src, dest, watch, series } from "gulp";
import gulpSass from "gulp-sass";
import * as sass from "sass";
import sourcemaps from "gulp-sourcemaps";
import autoprefixer from "gulp-autoprefixer";
import uglify from "gulp-uglify";
import concat from "gulp-concat";

const sassCompiler = gulpSass(sass);

// Paths
const paths = {
    scss: "assets/styles/**/*.scss",
    js: "assets/scripts/**/*.js",
    cssOutput: "dist/css",
    jsOutput: "dist/js",
};

// Compile SCSS to CSS
function compileSCSS() {
    return src(paths.scss)
        .pipe(sourcemaps.init())
        .pipe(
            sassCompiler({ outputStyle: "compressed" }).on(
                "error",
                sassCompiler.logError
            )
        )
        .pipe(autoprefixer())
        .pipe(sourcemaps.write("."))
        .pipe(dest(paths.cssOutput));
}

// Minify and combine JS
function minifyJS() {
    return src(paths.js)
        .pipe(sourcemaps.init())
        .pipe(concat("scripts.js"))
        .pipe(uglify())
        .pipe(sourcemaps.write("."))
        .pipe(dest(paths.jsOutput));
}

// Watch files
function watchFiles() {
    watch(paths.scss, compileSCSS);
    watch(paths.js, minifyJS);
}

// Default task
export default series(compileSCSS, minifyJS, watchFiles);