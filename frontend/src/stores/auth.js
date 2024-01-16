import {defineStore} from "pinia";
import {reactive} from "vue";
import axios from "axios";

export const useAuthStore = defineStore('auth', () => {
    const state = reactive({
        user_id: null,
        waitingOnVerification: false,
    })

    function handleLogin(credentials) {
        axios.post('/login', credentials)
            .then((response) => {
             state.waitingOnVerification = true})
            .catch((error) => {
                state.waitingOnVerification = false
            alert(error.response.data.message)
        })
    }

     function handleVerification(credentials) {
        axios.post('/login/verify',credentials).then((response) => {
            localStorage.setItem('token',response.data.token);
            state.user_id = response.data.id
        }).catch((error) => {
            console.error(error)
            alert(error.response.data.message)
        })
    }

    async function initialAuthStatusCheck() {
        if (!state.user_id) {
            try {
                const res = await axios.get('/user')
                state.user_id = res.data.id
                console.log(state.user_id)
            } catch (error) {
                state.user_id = null;
            }
        }
    }

    return { state, handleLogin, handleVerification , initialAuthStatusCheck}
})

