// TODO сохранять поездку что бы не терялась в процессе,
//  возможно делать это в locationStore,смотреть
//  если ли сейчас активная поезда у пользователя или у водителя


import {defineStore} from "pinia";
import {reactive, ref} from "vue";

export const useTripStore = defineStore('trip', () => {
    const id = ref(null);
    const user_id = ref(null);

    const origin = reactive({
        lat: null,
        lng: null
    })

    const destination = reactive({
        lat: null,
        lng: null
    })

    const destination_name = ref('')

    return { id, user_id, origin, destination, destination_name }
})