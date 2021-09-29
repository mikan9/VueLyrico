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
            props: true,
            beforeEnter: (to, from, next) => {
                if (!!to.query) {
                    store.dispatch("updateSpotify", { ...to.query });
                }

                next();
            }
        }
    ]
})