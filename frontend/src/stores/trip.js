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


    const driver = reactive({
        id: null,
        year: null,
        make: null,
        model: null,
        license_plate: null,
        user: {
            name: null,
        }
    })

    const status = ref('not_started')

    function resetState() {
        id.value = null
        user_id.value = null
        origin.lat = null
        origin.lng = null
        destination.lat = null
        destination.lng = null
        destination_name.value = ''

        driver.id = null
        driver.year = null
        driver.make = null
        driver.model = null
        driver.license_plate = null
        driver.user.name = null

        status.value = 'not_started'
    }

    return { id, user_id, origin, destination, destination_name,driver,status, resetState }
})