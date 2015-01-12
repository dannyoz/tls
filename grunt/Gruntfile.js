module.exports = function(grunt) {

    // CONFIG 
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        ngtemplates:  {
            app: {
                options: {
                    module: "tls",
                    bootstrap: function(module, script) {
                        return '.run(["$templateCache", function($templateCache) {' + script + '}])';
                    },
                    htmlmin: {
                        collapseBooleanAttributes:      true,
                        collapseWhitespace:             true,
                        removeAttributeQuotes:          true,
                        removeEmptyAttributes:          true,
                        removeRedundantAttributes:      true,
                        removeScriptTypeAttributes:     true,
                        removeStyleLinkTypeAttributes:  true
                    },
                    url : function(path){
                        var template = path.substr(path.lastIndexOf('/') + 1);
                        return template
                    }
                },
                src:      'DEV/app/**/**.html',
                dest:     'DEV/app/templates/templates.js'
            }
        },

        concat: {
            dist: {
                src: [
                    'DEV/js/libs/angular.js',
                    'DEV/js/libs/angular-route.js',
                    'DEV/js/libs/angular-sanitize.js',
                    'DEV/js/libs/angular-touch.js',
                    'DEV/app/app.js',
                    'DEV/app/templates/templates.js',
                    'DEV/app/**/*.js'
                ],
                //dest: 'DEV/js/main.js',
                dest: '../wp-content/themes/tls/js/main.min.js'
            }
        },

        uglify: {
            build: {
                src: 'DEV/js/main.js',
                dest: '../wp-content/themes/tls/js/main.min.js'
            }
        },

        watch: {
            scripts: {
                files: [
                    'DEV/js/libs/angular.js',
                    'DEV/js/libs/angular-route.js',
                    'DEV/app/**/*.js',
                    '../wp-content/themes/tls/*.php'
                ],
                //tasks: ['ngtemplates','concat', 'uglify'],
                tasks: ['ngtemplates','concat'],
                options: {
                    spawn: false
                },
            },

            css: {
                files: [
                    'DEV/app/**/*.scss'
                ],
                tasks: ['compass'],
                options: {
                    spawn: false,
                }
            },

            html: {
                files:[
                    'DEV/app/**/*'
                ],
                tasks: ['copy']
            },

            livereload: {
                options: { livereload: true },
                files: [
                    '../wp-content/themes/tls/css/*.css',
                    'DEV/app/**/*',
                    '../wp-content/themes/tls/*.php'
                ],
            }
        },

        compass: {                  
            dist: {                
                options: {          
                    sassDir: 'DEV/app',
                    cssDir: '../wp-content/themes/tls/css',
                    noLineComments : true,
                    environment: 'development'
                }
            }
        },

        copy: {
            main: {
                expand: true, 
                flatten: true,
                cwd: 'DEV/app/', 
                src: [
                    '**/*.html'
                    ], 
                dest: '../wp-content/themes/tls/ng-views/', 
                filter: 'isFile'
            }
        },

        karma: {
            unit: {
                configFile: 'karma.conf.js'
            }
        }

    });

    // PACKAGES
    grunt.loadNpmTasks('grunt-angular-templates');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-karma');

    // RUN GRUNT 
    //grunt.registerTask('default', ['ngtemplates','concat', 'uglify', 'watch', 'compass', 'copy']);
    grunt.registerTask('default', ['ngtemplates','concat', 'watch', 'compass', 'copy']);

};