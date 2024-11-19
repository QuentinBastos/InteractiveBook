/** @type {import('tailwindcss').Config} */
module.exports = {
  purge: ['./templates/**/*.html.twig', './assets/**/*.js'], // Adjust the paths according to your structure
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.js',
    './assets/**/*.scss',
  ],
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [],
}

