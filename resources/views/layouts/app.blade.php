<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DFTG</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{--    @vite('resources/css/app.css')--}}
    @vite('resources/js/app.js')
</head>
<body>
<div id="app">
{{--    {{ dd(Auth::user()) }}--}}
</div>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
    https://firebase.google.com/docs/web/setup#available-libraries -->

<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyAbHA2BJKWJm5vOZ4b8Gr3TOM0ItexPyMg",
        authDomain: "dftg-bf946.firebaseapp.com",
        projectId: "dftg-bf946",
        storageBucket: "dftg-bf946.appspot.com",
        messagingSenderId: "820407309046",
        appId: "1:820407309046:web:62a0eb528b90ed90bdd2d7",
        measurementId: "G-ZCHTHXY6DR"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
        messaging.requestPermission().then(function () {
            return messaging.getToken()
        }).then(function(token) {

            axios.post("{{ route('fcmToken') }}",{
                _method:"PATCH",
                token
            }).then(({data})=>{
                console.log(data)
            }).catch(({response:{data}})=>{
                console.error(data)
            })

        }).catch(function (err) {
            console.log(`Token Error :: ${err}`);
        });
    }

    initFirebaseMessagingRegistration();

    messaging.onMessage(function({data:{body,title}}){
        new Notification(title, {body});
    });
</script>
</body>
</html>
