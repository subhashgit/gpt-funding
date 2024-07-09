/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    defaultTheme: 'default',
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
        'node_modules/preline/dist/**/*.js',
        "./node_modules/tw-elements/dist/js/**/*.js"
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('preline/plugin'),
        require("tw-elements/dist/plugin.cjs")
    ],
}

