/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    fontFamily: {
      sans: ['"PT Sans"', 'sans-serif']
    },
    colors : {
      'black': '#000',
      'white': '#fff',
      'regal-blue': '#243c5a',
      'red' : '#ff0000',
    },
    screens: {
      sm: '480px',
      md: '768px',
      lg: '976px',
      xl: '1440px',
    },
    extend: {  
    },
  },
  plugins: [],
}