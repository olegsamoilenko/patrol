importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyAbHA2BJKWJm5vOZ4b8Gr3TOM0ItexPyMg",
    authDomain: "dftg-bf946.firebaseapp.com",
    projectId: "dftg-bf946",
    storageBucket: "dftg-bf946.appspot.com",
    messagingSenderId: "820407309046",
    appId: "1:820407309046:web:62a0eb528b90ed90bdd2d7",
    measurementId: "G-ZCHTHXY6DR"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
