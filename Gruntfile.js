const replace = require('@rollup/plugin-replace')
const resolve = require('@rollup/plugin-node-resolve')
const commonjs = require('@rollup/plugin-commonjs')
const svelte = require('rollup-plugin-svelte')
const {terser} = require('rollup-plugin-terser')
const sass = require('sass')
const tildeImporter = require('grunt-sass-tilde-importer')

const production = process.env.NODE_ENV === 'production'

module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            options: {
                implementation: sass,
                sourceMap: !production,
                importer: tildeImporter
            },
            default: {
                files: {
                    './static/admin.css': './admin/assets/scss/styles.scss'
                }
            }
        },
        terser: {
            vendor: {
                options: {
                    output: {
                        comments: false
                    },
                    mangle: false,
                    sourceMap: !production
                },
                files: {
                    'static/vendor.js': [
                        'node_modules/jquery/dist/jquery.js',
                        'vendor/yiisoft/yii2/assets/yii.js',
                        'vendor/yiisoft/yii2/assets/yii.gridView.js',
                        'node_modules/@popperjs/core/dist/umd/popper.js'
                    ]
                }
            }
        },
        rollup: {
            options: {
                external: [
                    'jquery'
                ],
                globals: {
                    jquery: '$'
                },
                plugins: [
                    svelte({
                        compilerOptions: {
                            dev: !production
                        }
                    }),
                    resolve.default({
                        browser: true,
                        dedupe: ['svelte']
                    }),
                    commonjs(),
                    replace({
                        preventAssignment: true,
                        'process.env.NODE_ENV': JSON.stringify(production ? 'production' : 'development')
                    }),
                    production && terser()
                ]
            },
            default: {
                options: {
                    name: 'MP',
                    sourcemap: !production,
                    format: 'iife'
                },
                files: {
                    'static/admin.js': 'admin/assets/js/script.js'
                }
            }
        },
        svg_sprite: {
            admin: {
                // Target basics
                expand: true,
                cwd: 'admin/assets/svg',
                src: '*.svg',
                dest: 'static',
                // Target options
                options: {
                    shape: {
                        transform: [
                            {
                                svgo: {
                                    plugins: [
                                        {
                                            name: 'preset-default',
                                            params: {
                                                overrides: {
                                                    // customize default plugin options
                                                    inlineStyles: {
                                                        onlyMatchedOnce: false
                                                    },

                                                    // or disable plugins
                                                    removeDoctype: false
                                                }
                                            }
                                        }
                                    ]
                                }
                            }
                        ],
                        dimension: { // Set maximum dimensions
                            maxWidth: 24,
                            maxHeight: 24
                        },
                        spacing: { // Add padding
                            padding: 0
                        },
                        dest: 'svg'
                    },
                    svg: {
                        namespaceClassnames: false
                    },
                    mode: {
                        symbol: { // Activate the symbol mode
                            dest: '.',
                            inline: false,
                            sprite: 'admin.svg',
                            dimensions: false
                        }
                    }
                }
            }
        },
        csso: {
            admin: {
                src: 'static/admin.css'
            }
        },
        realFavicon: {
            admin: {
                src: 'admin/assets/svg/logo.svg',
                dest: 'admin/web',
                options: {
                    iconsPath: '/',
                    html: ['admin/views/layouts/base.php'],
                    design: {
                        ios: {
                            pictureAspect: 'backgroundAndMargin',
                            backgroundColor: '#ffffff',
                            margin: '14%',
                            assets: {
                                ios6AndPriorIcons: false,
                                ios7AndLaterIcons: false,
                                precomposedIcons: true,
                                declareOnlyDefaultIcon: true
                            },
                            appName: 'Market'
                        },
                        desktopBrowser: {
                            design: 'raw'
                        },
                        windows: {
                            pictureAspect: 'noChange',
                            backgroundColor: '#da532c',
                            onConflict: 'override',
                            assets: {
                                windows80Ie10Tile: false,
                                windows10Ie11EdgeTiles: {
                                    small: false,
                                    medium: true,
                                    big: false,
                                    rectangle: false
                                }
                            },
                            appName: 'Market'
                        },
                        androidChrome: {
                            pictureAspect: 'backgroundAndMargin',
                            margin: '17%',
                            backgroundColor: '#ffffff',
                            themeColor: '#ffffff',
                            manifest: {
                                name: 'Market',
                                display: 'browser',
                                orientation: 'notSet',
                                onConflict: 'override',
                                declared: true
                            },
                            assets: {
                                legacyIcon: false,
                                lowResolutionIcons: false
                            }
                        },
                        safariPinnedTab: {
                            pictureAspect: 'silhouette',
                            themeColor: '#5bbad5'
                        }
                    },
                    settings: {
                        scalingAlgorithm: 'Mitchell',
                        errorOnImageTooSmall: false,
                        readmeFile: false,
                        htmlCodeFile: false,
                        usePathAsIs: false
                    }
                }
            }
        }
    })

    grunt.loadNpmTasks('grunt-terser')
    grunt.loadNpmTasks('grunt-rollup')
    grunt.loadNpmTasks('grunt-sass')
    grunt.loadNpmTasks('grunt-svg-sprite')
    grunt.loadNpmTasks('grunt-csso')
    grunt.loadNpmTasks('grunt-contrib-copy')
    grunt.loadNpmTasks('grunt-real-favicon')

    const defaultTasks = ['terser', 'rollup', 'sass', 'svg_sprite']
    if (production) {
        defaultTasks.push('csso')
    }
    grunt.registerTask('default', defaultTasks)
}
