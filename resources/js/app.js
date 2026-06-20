import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Toggle mobile nav, dark mode, dsb. ditambahkan saat membangun komponen.
