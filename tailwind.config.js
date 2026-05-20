/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                midnight: '#121212',
                cloud: '#F5F5F5',
            },
            backdropBlur: {
                xs: '2px',
            }
        },
    },
    plugins: [],
}
