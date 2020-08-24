import Vue from 'vue';
import moment from 'moment';

Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})

Vue.filter('dateEs', function(date) {
    moment.locale('es')
    return moment(date).format('MMMM Do YYYY')
})

Vue.filter('datePE', function(date) {
    moment.locale('es')
    return moment(date).format('DD-MM-YYYY')
})