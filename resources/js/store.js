import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)


const store = new Vuex.Store({
    state: {
        user: null,
        menus: [],
        token: localStorage.getItem('access_token') || null,
        appName: 'Evadoc',
    },

    getters: {
        user( state ) {
            return state.user!=null? state.user: {}
        },

        loggedIn( state ) {
            return state.token !== null;
        },

        appName( state )
        {
            return state.appName;
        },

        menus( state ) {
            return state.menus
        }
    },

    mutations: {

        retrieveToken( state, token ) { 
            state.token = token 
        },

        destroyToken( state ) { 
            state.token = null 
        },

        retrieveUser(state, user){
            state.user = user
        },

        retrieveMenus(state, menus){
            state.menu = []
            state.menus = menus
        }
    },

    actions: {

        retrieveToken( context, credentials ) {
            return new Promise( (resolve, reject) => {
                axios.post('/api/login', {
                    'username': credentials.username,
                    'password': credentials.password,
                    'remember': credentials.remember,
                })
                .then( res => {
                    const token = res.data.access_token
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
                    localStorage.setItem('access_token', token)
                    context.commit('retrieveToken', token)
                    this.dispatch('retrieveUser')
                    this.dispatch('retrieveMenus')
                    resolve(res)
                })
                .catch( err => {
                    reject(err);
                })
            })
        },

        destroyToken( context ){

            if( context.getters.loggedIn ) {
                return new Promise((resolve, reject) => {
                    axios.post('/api/logout')
                    .then(res => {
                        axios.defaults.headers.common['Authorization'] = null
                        localStorage.removeItem('access_token')
                        context.commit('destroyToken')
                        resolve(res)
                    })
                    .catch(err => {
                        //console.log(error)
                        localStorage.removeItem('access_token')
                        context.commit('destroyToken')
                        reject(err)
                    })
                })
            }
        },

        retrieveUser( context ){
            return new Promise( (resolve, reject) => {
                axios.get('/api/user').then( res => {
                    context.commit('retrieveUser', res.data)
                    resolve(res)
                }).catch( err => reject(err))
            })
        },

        retrieveMenus( context ) {
            return new Promise( (resolve, reject) => {
                axios.get('/api/menus').then( res => {
                    context.commit('retrieveMenus', res.data.data)
                    resolve(res)
                }).catch( err => reject(err))
            })
        },

        logError( {context}, {err, keys=[]}) {
            console.error('err', err.response);
            let message = '';
            if( err.response.status != 422 ){
                message = `Error ${err.response.status}: ${err.response.statusText}`;
                alert( message);

            }else {
                let errors = [];
                if( keys.length > 0){
                    Object.keys(err.response.data.errors).forEach( key => {
                        if( keys.find( ikey => ikey==key ) ){
                            err.response.data.errors[key].forEach( error=>errors.push(error));
                        } 
                    })
                }
                if( errors.length > 0 ){
                    message = `Error ${err.response.status}: ${err.response.statusText}`;
                    errors.forEach( error => message += '\n'+error )
                    alert( message);
                }
            }
        },
    },

    modules: {

    }
})

export default store
