<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Occurred</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #212529;
            padding: 20px;
        }
        .error-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .error-header {
            background-color: #dc3545;
            color: #fff;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            font-size: 18px;
            text-align: center;
        }
        .error-details {
            padding: 10px 20px;
        }
        .code-snippet {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            overflow-x: auto;
            margin-top: 10px;
            font-family: monospace;
        }
        .trace {
            margin-top: 20px;
            background: #e9ecef;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .powered-by {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-header">
            An Error Occurred
        </div>
        <div class="error-details">
            <p><strong>Error:</strong> {{message}}</p>
            <p><strong>File:</strong> {{file}}</p>
            <p><strong>Line:</strong> {{line}}</p>
            <p><strong>Timestamp:</strong> {{timestamp}}</p>
            <div class="code-snippet">
                <strong>Code Snippet:</strong>
                <pre>{{code}}</pre>
            </div>
        </div>
        <div class="trace">
            <strong>Stack Trace:</strong>
            <pre>{{trace}}</pre>
        </div>
        <div class="powered-by">
            Powered by Artex Essence Debugger
        </div>
    </div>
</body>
</html>