import Vue from "vue";
import VueI18n from "vue-i18n";

Vue.use(VueI18n);

function getMessages() {
    const locales = require.context('./locales', false, /\.json$/);
    const messages = {};
    locales.keys().forEach(key => {
        messages[key.split('/').pop().split('.')[0]] = locales(key);
    })
    return messages;
}

export default new VueI18n({
    locale: 'en',
    messages: getMessages(),
});