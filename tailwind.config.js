/** @type {import('tailwindcss').Config} */
module.exports = {
  purge: ['./templates/**/*.html.twig', './assets/**/*.js'], // Adjust the paths according to your structure
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [],
}

