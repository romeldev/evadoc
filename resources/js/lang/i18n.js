
import Vue from 'vue';
import VueI18n from 'vue-i18n';
import es from './es_PE';

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: 'es',
    fallbackLocale: 'es',
    messages: {
        'en': {},
        'es': es,
    },
    silentTranslationWarn : true
});

export default i18n