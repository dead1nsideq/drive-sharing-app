import {defineStore} from "pinia";
import {reactive} from "vue";
import axios from "axios";

export const useAuthStore = defineStore('auth', () => {
    const state = reactive({
        logged: false,
        waitingOnVerification: false,
    })

    function handleLogin(phone) {
        axios.post('/login', phone)
            .then((response) => {
             state.waitingOnVerification = true})
            .catch((error) => {
                state.waitingOnVerification = false
            alert(error.response.data.message)
        })
    }

     function handleVerification(credentials) {
        axios.post('/login/verify',credentials).then((response) => {
            localStorage.setItem('token',response.data);
            state.logged = true;
        }).catch((error) => {
            console.error(error)
            alert(error.response.data.message)
        })
    }

    async function initialAuthStatusCheck() {
        if (!state.logged) {
            try {
                const res = await axios.get('/user')
                state.logged = res.status === 200;
            } catch (error) {
                state.logged = false;
            }
        }
    }

    return { state, handleLogin, handleVerification , initialAuthStatusCheck}
})

