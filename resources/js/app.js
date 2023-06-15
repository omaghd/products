import './bootstrap';

import { createApp } from 'vue'

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import Products from "@/Pages/Products.vue";

const app = createApp(Products)

app.use(Toast);

app.mount('#app')
