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
                },
                src:      'DEV/app/**/**.html',
                dest:     'DEV/app/templates/templates.js',
            }
        },

        concat: {
            dist: {
                src: [
                    'DEV/js/libs/angular.js',
                    'DEV/js/libs/angular-route.js',
                    'DEV/js/libs/angular-touch.js',
                    'DEV/app/app.js',
                    'DEV/app/templates/templates.js',
                    'DEV/app/global/*.js',
                    'DEV/app/global/*/*.js',
                    'DEV/app/global/*/*/*.js',
                    'DEV/app/templates/*.js',
                    'DEV/app/templates/*/*.js',
                    'DEV/app/templates/*/*/*.js'
                ],
                dest: 'DEV/js/main.js',
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
                    'DEV/app/app.js',
                    'DEV/app/global/*',
                    'DEV/app/global/*/*',
                    'DEV/app/global/*/*/*',
                    'DEV/app/templates/*',
                    'DEV/app/templates/*/*',
                    'DEV/app/templates/*/*/*'
                ],
                tasks: ['concat', 'uglify'],
                options: {
                    spawn: false
                },
            },

            css: {
                files: [
                    'DEV/app/*.scss',
                    'DEV/app/*/*.scss',
                    'DEV/app/*/*/*.scss',
                    'DEV/app/*/*/*/*.scss'
                ],
                tasks: ['compass'],
                options: {
                    spawn: false,
                }
            },

            html: {
                files:[
                    'DEV/app/*',
                    'DEV/app/*/*',
                    'DEV/app/*/*/*',
                    'DEV/app/*/*/*/*'
                ],
                tasks: ['copy']
            },

            livereload: {
                options: { livereload: true },
                files: [
                    '../wp-content/themes/tls/css/*.css',
                    'DEV/app/*',
                    'DEV/app/*/*',
                    'DEV/app/*/*/*',
                    'DEV/app/*/*/*/*'
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
                    '**.html',
                    '*/*.html',
                    '*/*/*.html',
                    '*/*/*/*.html'
                    ], 
                dest: '../wp-content/themes/tls/ng-views/', 
                filter: 'isFile'
            },
        },

        express: {
            options: {
                // Override defaults here
            },
            dev: {
                options: {
                    script: 'server.js'
                }
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
    grunt.loadNpmTasks('grunt-express-server');
    grunt.loadNpmTasks('grunt-contrib-copy');

    // RUN GRUNT 
    grunt.registerTask('default', ['ngtemplates','concat', 'uglify', 'express:dev', 'watch', 'compass', 'copy']);

};