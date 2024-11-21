<!DOCTYPE html>
<html lang="en">
<head>
    <title>Error: {{message}}</title>
</head>
<body>
    <h1>Error: {{message}}</h1>
    <p><strong>File:</strong> {{file}}</p>
    <p><strong>Line:</strong> {{line}}</p>
    <p><strong>Occurred at:</strong> {{current_time}}</p>
    <p><strong>Execution Time:</strong> {{execution_time}}</p>
    <p><strong>Memory Usage:</strong> {{memory_usage}}</p>
    <p><strong>Event Count:</strong> {{event_count}}</p>
    <pre>{{trace}}</pre>
</body>
</html>