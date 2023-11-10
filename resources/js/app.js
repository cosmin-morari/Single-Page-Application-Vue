
import './bootstrap';
import { createApp } from 'vue';


const app = createApp({});

import IndexComponent from './route.vue';
app.component('index-component', IndexComponent);

app.mount('.index');
