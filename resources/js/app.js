import { createApp } from 'vue';
import axios from 'axios';
import CustomerTable from './components/CustomerTable.vue';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp({});
app.component('customer-table', CustomerTable);
app.mount('#app');