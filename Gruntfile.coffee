module.exports = (grunt) ->

  # Project configuration.
  grunt.initConfig
    pkg: grunt.file.readJSON("package.json")

    # Clean out the source directory
    clean: ["assets/src/js/"]

    # Generate documentation for the coffee.
    groc:
      coffeescript: [
        "assets/coffee/**/*.coffee"
      ],
      options:
        "out": "doc/"

    # Compress and minify
    uglify:
      options:
        banner: "/*! <%= pkg.name %> v<%= pkg.version %> */\n"
        mangle:
          except: ["jQuery", "Backbone"]

      structure:
        files:
          "public/admin/js/structure.min.js": ["public/admin/js/structure.js"]

      app:
        files:
          "public/admin/js/app.min.js": ["public/admin/js/app.js"]

    # Compile coffee files to src/json
    coffee:
      glob_to_multiple:
        options:
          bare: true
        expand: true
        cwd: 'assets/coffee'
        src: ['**/*.coffee']
        dest: 'assets/src/js/'
        ext: '.js'

    # Compile our less styles
    less:
      development:
        options:
          paths: ["assets/less"]
        files:
          "public/admin/style.css": "assets/less/style.less"
      production:
        options:
          paths: ["assets/less"]
          yuicompress: true
        files:
          "public/admin/style.min.css": "assets/less/style.less"

    # Concat all our src files
    concat:
      structure:
        src: [
          'assets/vendor/plugins/html5shiv.js'
          'assets/vendor/backbone/underscore.js'
          'assets/vendor/backbone/backbone.js'
          'assets/vendor/backbone-plugins/backbone.marionette.js'
          'assets/vendor/plugins/dates/moment.js'
          'assets/vendor/plugins/dates/*.js'
          'assets/vendor/plugins/editor/*.js'
          'assets/vendor/plugins/md5.js'
          'assets/vendor/plugins/qtip.js'
          'assets/vendor/plugins/bootstrap/*.js'
          'assets/vendor/plugins/*.js'
        ]
        dest: 'public/admin/js/structure.js'

      app:
        src: [
          'assets/src/js/templates.js'
          'assets/src/js/config/**/*.js'
          'assets/src/js/app.js'
          'assets/src/js/entities/_base/*.js'
          'assets/src/js/entities/*.js'
          'assets/src/js/controllers/**/*.js'
          'assets/src/js/views/**/*.js'
          'assets/src/js/utilities/bugsnag.js'
          'assets/src/js/*.js'
          'assets/src/js/helpers/*.js'
          'assets/src/js/**/*.js'
        ]
        dest: 'public/admin/js/app.js'

    # Compile the templates
    jst:
      compile:
        options:
          # templateSettings:
          #   interpolate : /\{\{(.+?)\}\}/g
          processName: (fileName) ->
            return fileName.replace("assets/coffee/apps/", "")
        files:
          "assets/src/js/templates.js": ["assets/coffee/apps/**/*.html"]

    watch:
      coffee:
        files: 'assets/coffee/**/*.coffee'
        tasks: ["clean", "jst", "coffee", "concat"]
        options:
          interrupt: true
      html:
        files: 'assets/coffee/**/*.html'
        tasks: ["jst", "concat"]
        options:
          interrupt: true
      less:
        files: 'assets/**/*.less'
        tasks: ["less"]
        options:
          interrupt: true
      src:
        files: 'assets/vendor/**/*.js'
        tasks: ["concat", "livereload"]
        options:
          interrupt: true

  # Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks "grunt-contrib-uglify"
  grunt.loadNpmTasks "grunt-contrib-concat"
  grunt.loadNpmTasks "grunt-contrib-coffee"
  grunt.loadNpmTasks "grunt-contrib-watch"
  grunt.loadNpmTasks "grunt-contrib-clean"
  grunt.loadNpmTasks "grunt-contrib-less"
  grunt.loadNpmTasks "grunt-contrib-jst"
  grunt.loadNpmTasks "grunt-contrib-livereload"
  grunt.loadNpmTasks "grunt-groc"

  # Default task(s).
  # grunt.registerTask('watch', ['livereload-start', 'regarde']);
  grunt.registerTask "default", ["clean", "less", "coffee", "jst", "concat", "uglify"]
  grunt.registerTask "deploy", ["clean", "less", "coffee", "jst", "concat", "uglify", "groc"]
  grunt.registerTask "docs", ["groc"]
