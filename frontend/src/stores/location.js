import {defineStore} from "pinia";
import {reactive} from "vue";

export const useLocationStore = defineStore('location',() => {
    const destination = reactive({
        name: '',
        address: '',
        geometry: {
            lat: null,
            lng: null
        }
    })
    return { destination }
})