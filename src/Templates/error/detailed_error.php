<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Occurred</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; background-color: #f9f9f9; }
        .error-container { padding: 20px; max-width: 1000px; margin: 50px auto; background: #fff; border-radius: 5px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        .error-header { border-bottom: 1px solid #ddd; margin-bottom: 20px; }
        .error-header h1 { margin: 0; font-size: 24px; }
        .error-details { margin: 20px 0; }
        .stack-trace { margin: 20px 0; padding: 10px; background: #f5f5f5; font-family: monospace; white-space: pre-wrap; }
        .code-snippet { margin: 20px 0; padding: 10px; background: #f5f5f5; font-family: monospace; white-space: pre-wrap; border-left: 5px solid #ffcccb; }
        .debug-bar { position: fixed; bottom: 0; left: 0; right: 0; background: #222; color: #fff; padding: 10px; font-size: 12px; display: flex; justify-content: space-between; }
        .debug-bar span { margin-right: 20px; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-header">
            <h1>Error: {{message}}</h1>
            <p>File: {{file}} on line {{line}}</p>
            <p>Occurred at: {{timestamp}}</p>
        </div>
        <div class="error-details">
            <h2>Details</h2>
            <div class="code-snippet">{{code}}</div>
            <h2>Stack Trace</h2>
            <div class="stack-trace">{{trace}}</div>
        </div>
    </div>
    <div class="debug-bar">
        <span>Execution Time: {{execution_time}} ms</span>
        <span>Memory Usage: {{memory_usage}} MB</span>
        <span>Active Listeners: {{event_count}}</span>
    </div>
</body>
</html>