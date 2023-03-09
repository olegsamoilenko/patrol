import { initializeApp } from "firebase/app";
import { getMessaging, getToken } from "firebase/messaging";
console.log(initializeApp)
import "firebase/analytics";
import "firebase/messaging";
import axios from "axios";

export function useFirebase() {
    function firebaseInit() {
        const firebaseConfig = {
            apiKey: "AIzaSyAbHA2BJKWJm5vOZ4b8Gr3TOM0ItexPyMg",
            authDomain: "dftg-bf946.firebaseapp.com",
            projectId: "dftg-bf946",
            storageBucket: "dftg-bf946.appspot.com",
            messagingSenderId: "820407309046",
            appId: "1:820407309046:web:62a0eb528b90ed90bdd2d7",
            measurementId: "G-ZCHTHXY6DR"
        };
        const firebaseApp = initializeApp(firebaseConfig);
        console.log('firebaseApp', firebaseApp)
        // initializeApp(firebaseConfig);
        if ("Notification" in window /*&& getMessaging(firebaseApp).isSupported()*/) {
            const messaging = getMessaging(firebaseApp);
            console.log('messaging', messaging)
            try {
                messaging
                    .getToken({
                        vapidKey:
                            "BDpY-blKQzri9s_pB5Vv2dW_RJbLM_2-KXfzSzLLgNRf_zdASbvGSq5KbKE4kwVTLFzAkznaYRJ9OshGA9qAXvk",
                    })
                    .then((currentToken) => {
                        if (currentToken) {
                            this.sendTokenToServer(currentToken);
                        } else {
                            console.warn("Failed to get token.");
                        }
                    })
                    .catch((err) => {
                        console.log(
                            "An error occurred while retrieving token. ",
                            err
                        );
                        this.setTokenSentToServer(false);
                    });
            } catch (e) {
                console.log(e);
            }

            messaging.onMessage((payload) => {
                console.log("Message received. firebase.js ", payload);
                new Notification(
                    payload.notification.title,
                    payload.notification
                );
            });
        }
    }

    function isTokenSentToServer (currentToken) {
        return (
            window.localStorage.getItem("sentFirebaseMessagingToken") ===
            currentToken
        );
    }
    function setTokenSentToServer (currentToken) {
        window.localStorage.setItem(
            "sentFirebaseMessagingToken",
            currentToken ? currentToken : ""
        );
    }
    function sendTokenToServer (currentToken) {
        if (!this.isTokenSentToServer(currentToken)) {
            axios
                .post("rest/device/token", { token: currentToken })
                .then((data) => {
                    if (data.data.status) {
                        this.setTokenSentToServer(currentToken);
                    }
                });
        }
    }

    return {firebaseInit, isTokenSentToServer, setTokenSentToServer, sendTokenToServer};
}

// let firebaseInit = {
//     methods: {
//         firebaseInit: function () {
//             const firebaseConfig = {
//                 apiKey: "AIzaSyAbHA2BJKWJm5vOZ4b8Gr3TOM0ItexPyMg",
//                 authDomain: "dftg-bf946.firebaseapp.com",
//                 projectId: "dftg-bf946",
//                 storageBucket: "dftg-bf946.appspot.com",
//                 messagingSenderId: "820407309046",
//                 appId: "1:820407309046:web:62a0eb528b90ed90bdd2d7",
//                 measurementId: "G-ZCHTHXY6DR"
//             };
//             initializeApp.initializeApp(firebaseConfig);
//             if ("Notification" in window && initializeApp.messaging.isSupported()) {
//                 const messaging = initializeApp.messaging();
//                 try {
//                     messaging
//                         .getToken({
//                             vapidKey:
//                                 "BDpY-blKQzri9s_pB5Vv2dW_RJbLM_2-KXfzSzLLgNRf_zdASbvGSq5KbKE4kwVTLFzAkznaYRJ9OshGA9qAXvk",
//                         })
//                         .then((currentToken) => {
//                             if (currentToken) {
//                                 this.sendTokenToServer(currentToken);
//                             } else {
//                                 console.warn("Failed to get token.");
//                             }
//                         })
//                         .catch((err) => {
//                             console.log(
//                                 "An error occurred while retrieving token. ",
//                                 err
//                             );
//                             this.setTokenSentToServer(false);
//                         });
//                 } catch (e) {
//                     console.log(e);
//                 }
//
//                 messaging.onMessage((payload) => {
//                     console.log("Message received. firebase.js ", payload);
//                     new Notification(
//                         payload.notification.title,
//                         payload.notification
//                     );
//                 });
//             }
//         },
//         isTokenSentToServer: function (currentToken) {
//             return (
//                 window.localStorage.getItem("sentFirebaseMessagingToken") ===
//                 currentToken
//             );
//         },
//         setTokenSentToServer: function (currentToken) {
//             window.localStorage.setItem(
//                 "sentFirebaseMessagingToken",
//                 currentToken ? currentToken : ""
//             );
//         },
//         sendTokenToServer: function (currentToken) {
//             if (!this.isTokenSentToServer(currentToken)) {
//                 axios
//                     .post("rest/device/token", { token: currentToken })
//                     .then((data) => {
//                         if (data.data.status) {
//                             this.setTokenSentToServer(currentToken);
//                         }
//                     });
//             }
//         },
//     },
// };
//
// export default {
//     firebaseInit,
// };
