module.exports = {
    env: {
        browser: true,
        es2021: true
    },
    extends: 'standard',
    overrides: [
        {
            files: ['*.svelte'],
            processor: 'svelte3/svelte3'
        }
    ],
    parserOptions: {
        ecmaVersion: 'latest',
        sourceType: 'module'
    },
    rules: {
        'no-new': 0,
        indent: ['warn', 4],
        'no-multiple-empty-lines': ['error', {max: 1, maxBOF: 2, maxEOF: 0}],
        'space-before-function-paren': 0,
        'object-curly-spacing': 0
    },
    plugins: [
        'svelte3',
        'unicorn'
    ],
    ignorePatterns: [
        'vendor/*',
        'node_modules/*',
        'static/*',
        'console/*',
        '*/web/assets/*'
    ]
}
