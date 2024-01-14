import {defineStore} from "pinia";
import {reactive} from "vue";
import axios from "axios";

export const useAuthStore = defineStore('auth', () => {
    const user = reactive({
        logged: false
    })

    function handleLogin(phone) {
        axios.post('/login', phone)
            .then((response) => {
            console.log(response.data)})
            .catch((error) => {
            console.error(error)
            alert(error.response.data.message)
        })
    }

    function handleVerification(credentials) {
        axios.post('/login/verify',credentials).then((response) => {
            localStorage.setItem('token',response.data);
        }).catch((error) => {
            console.error(error)
            alert(error.response.data.message)
        })
    }


    return { user, handleLogin, handleVerification }
})

