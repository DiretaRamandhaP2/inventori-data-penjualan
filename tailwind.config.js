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
        background: '#F8F9FA',
        surface: '#FFFFFF',
        border: '#E5E7EB',
        textPrimary: '#1A1D23',
        textSecondary: '#6B7280',
        primary: {
          DEFAULT: '#2563EB',
          dark: '#1E40AF',
        },
        sidebar: '#1E293B',
        success: '#16A34A',
        warning: '#F59E0B',
        danger: '#DC2626',
      },
    },
  },
  plugins: [],
}
