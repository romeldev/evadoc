import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: JSON.parse(localStorage.getItem('user')) || null,

        token: localStorage.getItem('access_token') || null,

        appName: 'Evadoc',


    },

    getters: {
        user( state ) {
            return state.user;
        },

        loggedIn( state ) {
            return state.user !== null;
        },

        appName( state )
        {
            return state.appName;
        }
    },

    mutations: {

        retriveToken( state, token ) { state.token = token },

        destroyToken( state ) { state.token = null },


        // retrieveUser( state, user){
        //     state.user = user;
        // },

        // destroyUser( state ) {
        //     state.user = null;
        // }
    },

    actions: {
        retrieveUser( context, credentials ) {
            return new Promise( (resolve, reject) => {
                axios.post('/login', {
                    'username': credentials.username,
                    'password': credentials.password,
                    'remember': credentials.remember,
                })
                .then( res => {
                    let user = res.data;
                    localStorage.setItem('user', JSON.stringify(user) );
                    context.commit('retrieveUser', user )
                    resolve(res);
                })
                .catch( err => {
                    reject(err);
                })
            })
        },

        destroyUser( context ){

            if( context.getters.loggedIn ) {
                return new Promise((resolve, reject) => {
                    axios.post('/logout')
                    .then(response => {
                        // console.log('destroy user', response)
                        localStorage.removeItem('user')
                        context.commit('destroyUser')
                        resolve(response)
                    })
                    .catch(error => {
                        //console.log(error)
                        localStorage.removeItem('access_token')
                        context.commit('destroyToken')
                        reject(error)
                    })
                })
            }
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
