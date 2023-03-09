importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyAbHA2BJKWJm5vOZ4b8Gr3TOM0ItexPyMg",
    projectId: "dftg-bf946",
    messagingSenderId: "820407309046",
    appId: "1:820407309046:web:62a0eb528b90ed90bdd2d7",
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
