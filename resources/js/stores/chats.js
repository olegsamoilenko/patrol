import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useChatsStore = defineStore('chats', () => {
    const allChats = ref(null);
    const isLoadedChats = ref(false)

    function getChats() {
        axios
            .get("/api/get-all-chats")
            .then(({data}) => {
                allChats.value = data.chats;
                console.log('allChats', allChats.value)
            })
            .catch((error) => {
                console.log('error', error);
            })
            .finally(() => {
                isLoadedChats.value = true;
            });
    }

    function storeChat(data, config) {
        axios
            .post("/api/store-chat", data, config)
            .then(({data}) => {
                console.log('data', data)
            })
            .catch((error) => {
                console.log('error', error);
            })
            .finally(() => {

            });
    }


    return { allChats, isLoadedChats, getChats, storeChat }
})
