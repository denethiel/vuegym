

const state = {
    token: localStorage.getItem('token') || '',
    status:''
}

const getters = {
    isAuthenticated: state => !!state.token,
    authStatus: state => state.status
}

const actions = {
    [AUTH_REQUEST]:({commit, dispatch}, user) =>{
        return new Promise((resolve, reject) => {
            commit(AUTH_REQUEST)
            axios({url:'v1/users/login',data:user,method:'POST'})
                .then(resp =>{
                    console.log(resp);
                    commit(AUTH_SUCCESS, token)
                    dispatch(USER_REQUEST)
                    resolve(resp)
                })
                .catch(err => {
                    commit(AUTH_ERROR, err)
                    localStorage.removeItem('token')
                    reject(err)
                })
        })
    },
    [AUTH_LOGOUT]:({commit, dispatch}) => {
        return new Promise((resolve, reject) => {
            commit(AUTH_LOGOUT)
            localStorage.removeItem('token')
            resolve()
        })
    }
}

const mutations = {
    [AUTH_REQUEST]:(state) =>{
        state.status = 'loading'
    },
    [AUTH_SUCCESS]:(state, token) =>{
        state.state = 'success'
        state.token = token
    },
    [AUTH_ERROR]:(state) => {
        state.status = 'error'
    },
    [AUTH_LOGOUT]:(state) => {
        state.state = ''
    }
}
