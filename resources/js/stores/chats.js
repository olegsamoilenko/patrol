import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useChatsStore = defineStore('chats', () => {
    const allMessages = ref(null);
    const isLoadedChats = ref(false)

    function getMessages() {
        axios
            .get("/api/messages")
            .then(({data}) => {
                allMessages.value = data.messages;
            })
            .catch((error) => {
                console.log('error', error);
            })
            .finally(() => {
                isLoadedChats.value = true;
            });
    }

    function storeMessage(data, config) {
        axios
            .post("/api/message", data, config)
            .then(({data}) => {

            })
            .catch((error) => {
                console.log('error', error);
            })
            .finally(() => {

            });
    }


    return { allMessages, isLoadedChats, getMessages, storeMessage }
})
