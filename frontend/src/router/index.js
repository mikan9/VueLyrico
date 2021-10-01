import Vue from "vue";
import VueRouter from "vue-router";
import store from '@/store';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/:query?',
            component: () => import('@/views/Home'),
        }
    ]
})