Dear {{ $response->vacancy->user->name }},<br>
<br>
you received a new response to your vacancy '{{ $response->vacancy->title }}'. Please find the details below:
<br>
<br>
    Response author: {{ $response->user->name }}<br>
    Total number of responses: {{ $responsesCount }}<br>
    Response recieved at: {{ $response->created_at }}<br>
    <br>
    {{ $response->text }}

<br>
Regards,<br>
Mini-Upwork
