{{-- TODO: Подправить дизайн --}}
<h1>Надійшло нове повідомлення</h1>
<p>Від: {{ $feedback->user->name }} {{ $feedback->user->surname}}</p>
<p>Тема: {{$feedback->topic}}</p>
<p>Текст повідомлення: {{ $feedback->text }}</p>
