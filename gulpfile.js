'use strict';

var gulp = require('gulp');
var gPlugins = require('gulp-load-plugins')();

var path = require('path');
var fs = require('fs');
var shell = require('shelljs');
var del = require('del');

var es = require('event-stream');
var _ = require('lodash');

var environments = [
  'development',
  'production',
  'local'
];

var bases = {
  repo: __dirname
};

var dirs = {
  src: path.join(bases.repo, 'src'),
  app: path.join(bases.repo, 'app'),
  wordpress: path.join(bases.repo, 'app', 'wordpress')
};

var taskConfig = {};

function shellWrapper(cmdTemplate, data) {
  return shell.exec(_.template(cmdTemplate)(data));
}

gulp.task('default', function() {
  fs.readFile('README.md', 'utf8', function(err, data) {
    if (err) {
      throw err;
    }
    console.log(data);
  });
});

gulp.task('sanitycheck', function() {
  _.assign(taskConfig, {
    env: (gPlugins.util.env.env || 'local').toLowerCase()
  });

  if (!(_.includes(environments, taskConfig.env))) {
    gPlugins.util.log(gPlugins.util.colors.red('[Error]',
      'unknown env:',
      taskConfig.env));
    process.exit(1);
  }
});

gulp.task('clean', function() {
  gPlugins.util.log('Removing ', dirs.app);
  del.sync([dirs.app]);
});

gulp.task('copy:config', ['sanitycheck', 'clean'], function() {
  var srcDir = dirs.src;

  return gulp.src([
    'app.yaml', 'cron.yaml', 'php.ini'
  ], {
    cwd: srcDir
  })
    .pipe(gulp.dest(dirs.app));
});

gulp.task('copy:wp', ['copy:config'], function() {
  var srcRoot = path.join(dirs.src, 'wp');

  return gulp.src('**/*',
    {
      cwd: srcRoot
    })
    .pipe(gulp.dest(dirs.wordpress));
});

gulp.task('copy:wp-overridden', ['copy:wp'], function() {
  var mainSrc = ['**/*',
    '!environments{,/**}'];
  var configSrc = path.join('environments', taskConfig.env, 'brithon-config.php');
  var srcRoot = path.join(dirs.src, 'wp-overridden');

  return es.merge(gulp.src(mainSrc,
    {
      cwd: srcRoot,
      cwdbase: true,
      dot: true
    }),
    gulp.src(configSrc,
      {
        cwd: srcRoot
      }))
    .pipe(gulp.dest(dirs.wordpress));
});

gulp.task('build', ['copy:wp-overridden'], function() {});

gulp.task('deploy', ['build'], function() {
  shellWrapper('appcfg.py update <%= app %>', dirs);
});
