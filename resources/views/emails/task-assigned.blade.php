<!DOCTYPE html>
<html>
<head>
    <title>Task Assigned</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; color: #333; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
        <div style="background-color: #4caf50; color: #fff; padding: 15px; text-align: center; font-size: 18px; font-weight: bold;">
            Task Assignment Notification
        </div>

        <div style="padding: 20px;">
            <p style="font-size: 16px; margin: 0 0 10px;">Hello, <strong>{{ $user['name'] }}</strong>,</p>
            <p style="margin: 0 0 15px;">You have been assigned a new task. Please find the details below:</p>

            <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                <p style="margin: 0 0 5px;"><strong>Title:</strong> {{ $task['title'] }}</p>
                @if (!empty($task['description']))
                    <p style="margin: 0 0 5px;"><strong>Description:</strong> {{ $task['description'] }}</p>
                @endif
                @if (!empty($task['due_date']))
                    <p style="margin: 0 0 5px;"><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($task['due_date'])->format('F j, Y, g:i A') }}</p>
                @endif
            </div>

            <p style="margin: 0;">Thank you!</p>
        </div>
    </div>
</body>
</html>
