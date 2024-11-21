<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error: {{message}}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        h1 { color: #cc0000; }
        pre { background: #f4f4f4; padding: 1rem; overflow-x: auto; }
        .code { background: #ffe4e1; padding: 1rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <h1>Error: {{message}}</h1>
    <p><strong>File:</strong> {{file}}</p>
    <p><strong>Line:</strong> {{line}}</p>
    <p><strong>Timestamp:</strong> {{timestamp}}</p>
    <div class="code">
        <strong>Code Snippet:</strong><br>
        {{code}}
    </div>
    <h2>Stack Trace:</h2>
    <pre>{{trace}}</pre>
    <footer>Powered by Artex Essence Debugger</footer>
</body>
</html>